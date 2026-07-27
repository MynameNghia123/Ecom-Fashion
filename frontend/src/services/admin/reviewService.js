import http from '@/services/shared/http'

const BASE = '/admin/reviews'

export const reviewService = {
  /**
   * Lấy danh sách đánh giá của hệ thống
   * @param {Object} params - { search?, rating?, page?, per_page? }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Xóa nhận xét (Spam / Vi phạm)
   * @param {number|string} id
   */
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  }
}
