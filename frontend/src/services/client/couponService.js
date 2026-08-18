import api from '@/services/shared/http'

export const couponService = {
  applyCoupon(code, orderTotal) {
    return api.post('/client/coupons/apply', { code, order_total: orderTotal })
  }
}
