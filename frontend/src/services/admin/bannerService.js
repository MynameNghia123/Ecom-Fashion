import http from '@/services/shared/http'

const BASE = '/admin/banners'

export const bannerService = {
  /**
   * Lấy danh sách banner (phân trang + lọc)
   * @param {Object} params - { search?, position?, page?, per_page? }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Lấy chi tiết một banner
   * @param {number} id
   */
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },

  /**
   * Tạo banner mới
   * @param {{ title, image_url, target_url?, position, display_order?, is_active, start_date?, end_date? }} data
   */
  create(data) {
    return http.post(BASE, data)
  },

  /**
   * Cập nhật banner
   * @param {number} id
   */
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  },

  /**
   * Xóa banner
   * @param {number} id
   */
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  },
}
