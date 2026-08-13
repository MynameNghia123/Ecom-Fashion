import api from '@/plugins/axios'

export const profileService = {
  /** Cập nhật thông tin cá nhân */
  updateProfile(data) {
    return api.put('/client/auth/profile', data)
  },

  /** Đổi mật khẩu */
  changePassword(data) {
    return api.put('/client/auth/change-password', data)
  },

  /** Lấy danh sách coupon còn hiệu lực */
  getCoupons() {
    return api.get('/client/coupons')
  },

  /** Kiểm tra và áp dụng mã giảm giá */
  applyCoupon(data) {
    return api.post('/client/coupons/apply', data)
  },

  /** Lấy danh sách coupon có thể lưu */
  getCollectableCoupons() {
    return api.get('/client/coupons/collectable')
  },

  /** Lưu mã giảm giá */
  collectCoupon(couponId) {
    return api.post('/client/coupons/collect', { coupon_id: couponId })
  },

  /** Lấy danh sách địa chỉ */
  getAddresses() {
    return api.get('/client/addresses')
  },

  /** Thêm mới địa chỉ */
  createAddress(data) {
    return api.post('/client/addresses', data)
  },

  /** Cập nhật địa chỉ */
  updateAddress(id, data) {
    return api.put(`/client/addresses/${id}`, data)
  },

  /** Xóa địa chỉ */
  deleteAddress(id) {
    return api.delete(`/client/addresses/${id}`)
  },

  /** Đặt địa chỉ mặc định */
  setDefaultAddress(id) {
    return api.patch(`/client/addresses/${id}/default`)
  },
}
