import http from '@/services/shared/http'

const BASE = '/admin/orders'

export const orderService = {
  /**
   * Lấy danh sách đơn hàng cho admin
   * @param {Object} params - { search?, status?, payment_status?, page?, per_page? }
   */
  getAll(params = {}) {
    return http.get(BASE, { params })
  },

  /**
   * Lấy chi tiết đơn hàng
   * @param {number} id
   */
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },

  /**
   * Cập nhật thông tin/trạng thái đơn hàng
   * @param {number} id
   * @param {Object} data - { status?, payment_status?, shipping_name?, shipping_phone?, shipping_address? }
   */
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  }
}
