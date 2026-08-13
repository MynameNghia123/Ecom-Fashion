import api from '@/plugins/axios'

export const cartService = {
  /** Lấy giỏ hàng từ server (yêu cầu đăng nhập) */
  getCart() {
    return api.get('/client/cart')
  },

  /** Thêm item vào giỏ hàng server */
  addItem(productVariantId, quantity = 1) {
    return api.post('/client/cart/items', { product_variant_id: productVariantId, quantity })
  },

  /** Cập nhật số lượng item */
  updateItem(itemId, quantity) {
    return api.put(`/client/cart/items/${itemId}`, { quantity })
  },

  /** Xóa item */
  removeItem(itemId) {
    return api.delete(`/client/cart/items/${itemId}`)
  },

  /** Đồng bộ giỏ hàng từ localStorage lên server */
  syncCart(items) {
    return api.post('/client/cart/sync', { items })
  },
}
