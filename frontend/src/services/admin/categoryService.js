import http from '@/services/shared/http'

const BASE = '/admin/categories'

export const categoryService = {
  /**
   * Lấy danh sách danh mục
   * @param {Object} params - { search?, page?, per_page? }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Lấy chi tiết một danh mục
   * @param {number} id
   */
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },

  /**
   * Tạo danh mục mới
   * @param {{ name: string, slug?: string, description?: string, parent_id?: number|null }} data
   */
  create(data) {
    return http.post(BASE, data)
  },

  /**
   * Cập nhật danh mục
   * @param {number} id
   * @param {{ name?: string, slug?: string, description?: string, parent_id?: number|null }} data
   */
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  },

  /**
   * Xóa danh mục
   * @param {number} id
   */
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  },
}