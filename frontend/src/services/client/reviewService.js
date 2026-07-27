import api from '@/plugins/axios'

export const reviewService = {
  /** Gửi đánh giá sản phẩm */
  submitReview(payload) {
    return api.post('/client/reviews', payload)
  },

  /** Lấy danh sách đánh giá của khách hàng hiện tại */
  getMyReviews() {
    return api.get('/client/reviews')
  },

  /** Lấy danh sách đánh giá của sản phẩm */
  getProductReviews(productId) {
    return api.get(`/client/products/${productId}/reviews`)
  }
}
