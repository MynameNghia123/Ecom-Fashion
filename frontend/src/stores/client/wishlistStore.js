import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

const STORAGE_KEY = 'ecom_wishlist'

export const useWishlistStore = defineStore('clientWishlist', () => {
  // Items array stored locally
  const items = ref(JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'))

  // Save items to localStorage whenever state updates
  const saveToStorage = () => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items.value))
  }

  const count = computed(() => items.value.length)

  // Check if product is in wishlist
  const isInWishlist = (productId) => {
    if (!productId) return false
    return items.value.some(item => String(item.id) === String(productId))
  }

  // Toggle product in wishlist
  const toggleWishlist = (product) => {
    if (!product || !product.id) return false

    const index = items.value.findIndex(item => String(item.id) === String(product.id))
    
    if (index > -1) {
      // Remove item
      items.value.splice(index, 1)
      saveToStorage()
      return false // now not in wishlist
    } else {
      // Add item
      const newItem = {
        id: product.id,
        name: product.name || 'Sản phẩm',
        currentPrice: product.currentPrice || (product.price ? `${new Intl.NumberFormat('vi-VN').format(product.price)} đ` : '0 đ'),
        originalPrice: product.originalPrice || null,
        image: product.image || product.thumbnail || 'https://images.unsplash.com/photo-1618015358954-115ef1ed1815?q=80&w=800&auto=format&fit=crop',
        description: product.description || product.category?.name || 'THỜI TRANG CAO CẤP',
        slug: product.slug || ''
      }
      items.value.push(newItem)
      saveToStorage()
      return true // now in wishlist
    }
  }

  // Remove single item by ID
  const removeItem = (productId) => {
    items.value = items.value.filter(item => String(item.id) !== String(productId))
    saveToStorage()
  }

  // Clear all items
  const clearAll = () => {
    items.value = []
    saveToStorage()
  }

  return {
    items,
    count,
    isInWishlist,
    toggleWishlist,
    removeItem,
    clearAll
  }
})
