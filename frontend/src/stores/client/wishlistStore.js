import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/shared/http'
import { useClientAuthStore } from '@/stores/client/authStore'

const STORAGE_KEY = 'ecom_wishlist'

export const useWishlistStore = defineStore('clientWishlist', () => {
  const items = ref(JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'))
  const loading = ref(false)

  const saveToStorage = () => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items.value))
  }

  const count = computed(() => items.value.length)

  const isInWishlist = (productId) => {
    if (!productId) return false
    return items.value.some(item => String(item.id) === String(productId))
  }

  /**
   * Đồng bộ danh sách yêu thích từ database backend khi đăng nhập
   */
  const fetchWishlist = async () => {
    const authStore = useClientAuthStore()
    if (!authStore.isAuthenticated) return

    loading.value = true
    try {
      const res = await api.get('/client/wishlist')
      if (res.data && res.data.success && Array.isArray(res.data.data)) {
        const dbItems = res.data.data.map(w => {
          const p = w.product || w
          const firstVar = (p.product_variants || p.productVariants || [])[0]
          const priceVal = firstVar ? (firstVar.sale_price || firstVar.price) : (p.price || 0)
          const origPriceVal = firstVar && firstVar.sale_price ? firstVar.price : null
          const img = p.thumbnail || (p.product_images && p.product_images[0] ? p.product_images[0].image_url : '')
          
          return {
            id: p.id,
            name: p.name,
            currentPrice: priceVal ? `${new Intl.NumberFormat('vi-VN').format(priceVal)} đ` : '0 đ',
            originalPrice: origPriceVal ? `${new Intl.NumberFormat('vi-VN').format(origPriceVal)} đ` : null,
            image: img ? (img.startsWith('http') ? img : `http://localhost:8000/storage/${img}`) : 'https://images.unsplash.com/photo-1618015358954-115ef1ed1815?q=80&w=800&auto=format&fit=crop',
            description: p.brand || p.category?.name || 'THỜI TRANG CAO CẤP',
            slug: p.slug || String(p.id)
          }
        })
        items.value = dbItems
        saveToStorage()
      }
    } catch (err) {
      console.warn('Lỗi khi tải wishlist từ DB:', err)
    } finally {
      loading.value = false
    }
  }

  /**
   * Thêm hoặc xóa sản phẩm khỏi yêu thích (Đồng bộ với DB nếu đã đăng nhập)
   */
  const toggleWishlist = async (product) => {
    if (!product || !product.id) return false

    const authStore = useClientAuthStore()
    const index = items.value.findIndex(item => String(item.id) === String(product.id))
    let inWishlist = false

    if (index > -1) {
      // Remove local
      items.value.splice(index, 1)
      inWishlist = false
    } else {
      // Add local
      const newItem = {
        id: product.id,
        name: product.name || 'Sản phẩm',
        currentPrice: product.currentPrice || (product.price ? `${new Intl.NumberFormat('vi-VN').format(product.price)} đ` : '0 đ'),
        originalPrice: product.originalPrice || null,
        image: product.image || product.thumbnail || 'https://images.unsplash.com/photo-1618015358954-115ef1ed1815?q=80&w=800&auto=format&fit=crop',
        description: product.description || product.category?.name || 'THỜI TRANG CAO CẤP',
        slug: product.slug || String(product.id)
      }
      items.value.push(newItem)
      inWishlist = true
    }
    saveToStorage()

    // Sycn with Backend DB if logged in
    if (authStore.isAuthenticated) {
      try {
        await api.post('/client/wishlist/toggle', { product_id: product.id })
      } catch (err) {
        console.warn('Lỗi lưu wishlist vào DB:', err)
      }
    }

    return inWishlist
  }

  /**
   * Xóa sản phẩm khỏi yêu thích theo ID
   */
  const removeItem = async (productId) => {
    items.value = items.value.filter(item => String(item.id) !== String(productId))
    saveToStorage()

    const authStore = useClientAuthStore()
    if (authStore.isAuthenticated) {
      try {
        await api.delete(`/client/wishlist/${productId}`)
      } catch (err) {
        console.warn('Lỗi xóa wishlist từ DB:', err)
      }
    }
  }

  const clearAll = () => {
    items.value = []
    saveToStorage()
  }

  return {
    items,
    count,
    loading,
    isInWishlist,
    fetchWishlist,
    toggleWishlist,
    removeItem,
    clearAll
  }
})
