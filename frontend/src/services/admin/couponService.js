import http from '@/services/shared/http'

const BASE = '/admin/coupons'

export const couponService = {
  /**
   * Lấy danh sách mã giảm giá
   * @param {Object} params - { search?, page?, per_page?, type?, is_active? }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Lấy chi tiết một mã giảm giá
   * @param {number} id
   */
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },

  /**
   * Tạo mã giảm giá mới
   * @param {Object} data
   */
  create(data) {
    return http.post(BASE, data)
  },

  /**
   * Cập nhật mã giảm giá
   * @param {number} id
   * @param {Object} data
   */
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  },

  /**
   * Xóa mã giảm giá
   * @param {number} id
   */
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  },

  /**
   * Kiểm tra mã giảm giá
   * @param {Object} data - { code, order_total }
   */
  check(data) {
    return http.post(`${BASE}/check`, data)
  },
}
