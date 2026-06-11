import http from '@/services/shared/http'

const BASE = '/admin/attributes'

export const attributeService = {
  /**
   * Lấy danh sách thuộc tính
   * @param {Object} params - { search, page, per_page }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Lấy chi tiết một thuộc tính
   * @param {number} id
   */
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },

  /**
   * Tạo thuộc tính mới
   * @param {{ name: string }} data
   */
  create(data) {
    return http.post(BASE, data)
  },

  /**
   * Cập nhật thuộc tính
   * @param {number} id
   * @param {{ name: string }} data
   */
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  },

  /**
   * Xóa thuộc tính
   * @param {number} id
   */
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  },
}
