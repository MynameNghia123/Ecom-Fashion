import http from '@/services/shared/http'

const BASE = '/admin/blogs'

export const blogService = {
  /**
   * Lấy danh sách bài viết blog (phân trang + lọc)
   * @param {Object} params - { search?, status?, page?, per_page? }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Lấy chi tiết một bài viết
   * @param {number} id
   */
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },

  /**
   * Tạo bài viết mới
   * @param {{ name, slug, description, image, status }} data
   */
  create(data) {
    return http.post(BASE, data)
  },

  /**
   * Cập nhật bài viết
   * @param {number} id
   * @param {{ name?, slug?, description?, image?, status? }} data
   */
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  },

  /**
   * Xóa bài viết
   * @param {number} id
   */
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  },
}
