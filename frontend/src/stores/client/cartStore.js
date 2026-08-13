import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { cartService } from '@/services/client/cartService'
import { useClientAuthStore } from '@/stores/client/authStore'
import { useToast } from 'vue-toastification'

const STORAGE_KEY = 'ef_cart_items'

export const useCartStore = defineStore('cart', () => {
  // ─── State ──────────────────────────────────────────────────────────────────
  const items = ref([])
  const loading = ref(false)

  // ─── Getters ────────────────────────────────────────────────────────────────
  const totalQuantity = computed(() =>
    items.value.reduce((sum, item) => sum + item.quantity, 0)
  )

  const totalPrice = computed(() =>
    items.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
  )

  const isEmpty = computed(() => items.value.length === 0)

  // ─── Selected Items (dùng chung cho Cart, MiniCart, Checkout) ──────────────
  const selectedIds = ref(new Set())

  // Mặc định chọn tất cả khi có items
  const initSelection = () => {
    selectedIds.value = new Set(items.value.map(i => i.product_variant_id))
  }

  const isAllSelected = computed(() =>
    items.value.length > 0 &&
    items.value.every(i => selectedIds.value.has(i.product_variant_id))
  )

  const selectedItems = computed(() =>
    items.value.filter(i => selectedIds.value.has(i.product_variant_id))
  )

  const selectedTotal = computed(() =>
    selectedItems.value.reduce((sum, i) => sum + i.price * i.quantity, 0)
  )

  function toggleSelect(productVariantId) {
    const next = new Set(selectedIds.value)
    next.has(productVariantId) ? next.delete(productVariantId) : next.add(productVariantId)
    selectedIds.value = next
  }

  function toggleSelectAll() {
    if (isAllSelected.value) {
      selectedIds.value = new Set()
    } else {
      selectedIds.value = new Set(items.value.map(i => i.product_variant_id))
    }
  }

  // ─── Helpers ────────────────────────────────────────────────────────────────
  const isAuthenticated = () => {
    const authStore = useClientAuthStore()
    return authStore.isAuthenticated
  }

  function persistLocal() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items.value))
    } catch {
      // bỏ qua nếu localStorage đầy
    }
  }

  function loadLocal() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY)
      return raw ? JSON.parse(raw) : []
    } catch {
      return []
    }
  }

  function updateItems(newItems) {
    items.value = newItems
    // Cleanup selection: keep only ids that still exist
    const nextIds = new Set()
    for (const id of selectedIds.value) {
      if (items.value.find(i => i.product_variant_id === id)) {
        nextIds.add(id)
      }
    }
    selectedIds.value = nextIds
  }

  // ─── Actions ────────────────────────────────────────────────────────────────

  /**
   * Fetch cart items.
   * If logged in, fetch from DB. Else, load from localStorage.
   */
  async function fetchCart() {
    if (isAuthenticated()) {
      loading.value = true
      try {
        const res = await cartService.getCart()
        if (res.data?.success && res.data.data?.items) {
          updateItems(res.data.data.items)
        }
      } catch (err) {
        console.error('Failed to fetch cart', err)
      } finally {
        loading.value = false
      }
    } else {
      updateItems(loadLocal())
    }
  }

  /**
   * Đồng bộ từ localStorage lên DB khi vừa đăng nhập
   */
  async function syncCart() {
    const localItems = loadLocal()
    if (localItems.length > 0) {
      try {
        // Prepare payload for sync
        const payload = localItems.map(i => ({
          product_variant_id: i.product_variant_id,
          quantity: i.quantity
        }))
        
        await cartService.syncCart(payload)
      } catch (err) {
        console.error('Failed to sync cart', err)
      }
    }
    // Clear localStorage after sync attempt
    localStorage.removeItem(STORAGE_KEY)
    await fetchCart()
  }

  /**
   * Thêm hoặc tăng số lượng sản phẩm trong giỏ.
   */
  async function addItem(itemData) {
    const { product_variant_id, quantity = 1, stock_quantity } = itemData
    
    if (isAuthenticated()) {
      loading.value = true
      try {
        const res = await cartService.addItem(product_variant_id, quantity)
        if (res.data?.success) {
          if (res.data.data?.items) updateItems(res.data.data.items)
          selectedIds.value.add(product_variant_id)
        }
        return res.data
      } catch (err) {
        throw err
      } finally {
        loading.value = false
      }
    } else {
      // Logic LocalStorage cho Guest
      const existing = items.value.find(i => i.product_variant_id === product_variant_id)
      if (existing) {
        const newQty = existing.quantity + quantity
        existing.quantity = Math.min(newQty, stock_quantity)
      } else {
        items.value.push({
          ...itemData,
          quantity: Math.min(quantity, stock_quantity)
        })
      }
      selectedIds.value.add(product_variant_id)
      persistLocal()
      return { success: true }
    }
  }

  /**
   * Cập nhật số lượng
   */
  async function updateQuantity(productVariantId, quantity) {
    const item = items.value.find(i => i.product_variant_id === productVariantId)
    if (!item) return

    if (quantity <= 0) {
      return removeItem(productVariantId)
    }

    if (isAuthenticated()) {
      // Find the cart_item id from backend if possible, wait backend expects cart item id
      // Wait, the backend API is PUT /client/cart/items/{id} where id is the cart_item id!
      // But the frontend only passes product_variant_id everywhere. 
      // Let's check `cartService.updateItem(itemId, quantity)`.
      // The `item` object from backend has `id` which is cart_item.id
      loading.value = true
      try {
        const res = await cartService.updateItem(item.id, quantity)
        if (res.data?.success && res.data.data?.items) {
          updateItems(res.data.data.items)
        }
      } catch (err) {
        console.error('Failed to update quantity', err)
      } finally {
        loading.value = false
      }
    } else {
      item.quantity = Math.min(quantity, item.stock_quantity)
      persistLocal()
    }
  }

  /**
   * Xóa item
   */
  async function removeItem(productVariantId) {
    const item = items.value.find(i => i.product_variant_id === productVariantId)
    if (!item) return

    if (isAuthenticated()) {
      loading.value = true
      try {
        const res = await cartService.removeItem(item.id)
        if (res.data?.success && res.data.data?.items) {
          updateItems(res.data.data.items)
        }
      } catch (err) {
        console.error('Failed to remove item', err)
      } finally {
        loading.value = false
      }
    } else {
      items.value = items.value.filter(i => i.product_variant_id !== productVariantId)
      selectedIds.value.delete(productVariantId)
      persistLocal()
    }
  }

  /**
   * Xóa toàn bộ giỏ (logout hoặc đặt hàng xong)
   */
  function clearCart() {
    items.value = []
    selectedIds.value = new Set()
    // Không xóa localStorage ở đây vì logout thì localStorage trống nhưng nếu khách thêm thì lại có.
    // Thực ra khách logout thì cart trống, guest cart = rỗng. Đúng ý user.
  }

  /**
   * Chuyển các sản phẩm ĐƯỢC CHỌN sang định dạng backend cần khi đặt hàng.
   */
  function toOrderItems() {
    return selectedItems.value.map(i => ({
      product_variant_id: i.product_variant_id,
      quantity: i.quantity,
    }))
  }

  // Khởi tạo ban đầu
  fetchCart().then(() => initSelection())

  return {
    items,
    loading,
    totalQuantity,
    totalPrice,
    isEmpty,
    selectedIds,
    selectedItems,
    selectedTotal,
    isAllSelected,
    toggleSelect,
    toggleSelectAll,
    fetchCart,
    syncCart,
    addItem,
    updateQuantity,
    removeItem,
    clearCart,
    toOrderItems,
  }
})
