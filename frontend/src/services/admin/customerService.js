import http from '@/services/shared/http'

const BASE = '/admin/customers'

export const customerService = {
  /**
   * Lấy danh sách khách hàng
   * @param {Object} params - { search?, page?, per_page? }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Tìm kiếm khách hàng theo chuỗi
   * @param {string} keyword
   */
  searchByString(keyword) {
    return http.get(`${BASE}/search`, { params: { q: keyword } })
  },

  /**
   * Lấy chi tiết một khách hàng
   * @param {number} id
   */
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },

  /**
   * Tạo khách hàng mới
   * @param {Object} data
   */
  create(data) {
    return http.post(BASE, data)
  },

  /**
   * Cập nhật khách hàng
   * @param {number} id
   * @param {Object} data
   */
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  },

  /**
   * Xóa khách hàng
   * @param {number} id
   */
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  },
}
