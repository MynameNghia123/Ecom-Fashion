import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

const STORAGE_KEY = 'ef_cart_items'

/**
 * CartStore — Quản lý giỏ hàng phía client.
 *
 * Strategy:
 *  - Luôn lưu localStorage (cả guest lẫn đã đăng nhập).
 *  - Checkout yêu cầu đăng nhập (kiểm tra ở Checkout.vue).
 *  - Mỗi item: { product_variant_id, quantity, price, product_name, product_thumbnail,
 *               sku, stock_quantity, attributes: [{ attribute, value }] }
 */
export const useCartStore = defineStore('cart', () => {
  // ─── State ──────────────────────────────────────────────────────────────────
  const items = ref(loadFromStorage())

  // ─── Getters ────────────────────────────────────────────────────────────────
  const totalQuantity = computed(() =>
    items.value.reduce((sum, item) => sum + item.quantity, 0)
  )

  const totalPrice = computed(() =>
    items.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
  )

  const isEmpty = computed(() => items.value.length === 0)

  // ─── Actions ────────────────────────────────────────────────────────────────

  /**
   * Thêm hoặc tăng số lượng sản phẩm trong giỏ.
   * @param {Object} itemData - Toàn bộ thông tin variant đã resolve từ API
   */
  function addItem(itemData) {
    const {
      product_variant_id,
      quantity = 1,
      price,
      product_name,
      product_thumbnail,
      sku,
      stock_quantity,
      attributes = [],
    } = itemData

    const existing = items.value.find(i => i.product_variant_id === product_variant_id)

    if (existing) {
      const newQty = existing.quantity + quantity
      existing.quantity = Math.min(newQty, stock_quantity)
    } else {
      items.value.push({
        product_variant_id,
        quantity: Math.min(quantity, stock_quantity),
        price,
        product_name,
        product_thumbnail,
        sku,
        stock_quantity,
        attributes,
      })
    }
    persist()
  }

  /**
   * Cập nhật số lượng của một item (theo product_variant_id).
   */
  function updateQuantity(productVariantId, quantity) {
    const item = items.value.find(i => i.product_variant_id === productVariantId)
    if (!item) return
    if (quantity <= 0) {
      removeItem(productVariantId)
      return
    }
    item.quantity = Math.min(quantity, item.stock_quantity)
    persist()
  }

  /**
   * Xóa item khỏi giỏ theo product_variant_id.
   */
  function removeItem(productVariantId) {
    items.value = items.value.filter(i => i.product_variant_id !== productVariantId)
    persist()
  }

  /**
   * Xóa toàn bộ giỏ hàng (sau khi đặt hàng thành công).
   */
  function clearCart() {
    items.value = []
    persist()
  }

  /**
   * Chuyển giỏ hàng sang định dạng backend cần khi đặt hàng.
   * @returns {Array} [{ product_variant_id, quantity }]
   */
  function toOrderItems() {
    return items.value.map(i => ({
      product_variant_id: i.product_variant_id,
      quantity: i.quantity,
    }))
  }

  // ─── Helpers ────────────────────────────────────────────────────────────────

  function persist() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items.value))
    } catch {
      // bỏ qua nếu localStorage đầy
    }
  }

  function loadFromStorage() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY)
      return raw ? JSON.parse(raw) : []
    } catch {
      return []
    }
  }

  return {
    items,
    totalQuantity,
    totalPrice,
    isEmpty,
    addItem,
    updateQuantity,
    removeItem,
    clearCart,
    toOrderItems,
  }
})
