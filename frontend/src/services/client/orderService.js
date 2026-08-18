import api from '@/services/shared/http'

export const orderService = {
  /**
   * Tạo đơn hàng mới.
   * @param {Object} payload
   * @param {string} payload.shipping_name
   * @param {string} payload.shipping_phone
   * @param {string} payload.shipping_address
   * @param {number} payload.shipping_fee
   * @param {string} payload.payment_method  - 'cod' | 'vnpay'
   * @param {Array}  payload.items           - [{ product_variant_id, quantity }]
   */
  createOrder(payload) {
    return api.post('/client/orders', payload)
  },

  /** Lấy danh sách đơn hàng của khách hàng hiện tại */
  getOrders() {
    return api.get('/client/orders')
  },

  /** Lấy chi tiết đơn hàng theo mã code */
  getOrder(orderCode) {
    return api.get(`/client/orders/${orderCode}`)
  },

  /** Xác minh kết quả thanh toán VNPAY (frontend gọi sau khi bị redirect về) */
  verifyVNPay(queryParams) {
    return api.get('/client/vnpay/return', { params: queryParams })
  },
}

