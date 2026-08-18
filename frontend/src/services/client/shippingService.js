import api from '@/services/shared/http'

export const shippingService = {
  /**
   * Lấy danh sách tỉnh/thành từ GHN (proxy qua backend)
   */
  getProvinces() {
    return api.get('/client/shipping/provinces')
  },

  /**
   * Lấy danh sách quận/huyện theo tỉnh
   * @param {number} provinceId
   */
  getDistricts(provinceId) {
    return api.get('/client/shipping/districts', { params: { province_id: provinceId } })
  },

  /**
   * Lấy danh sách phường/xã theo quận
   * @param {number} districtId
   */
  getWards(districtId) {
    return api.get('/client/shipping/wards', { params: { district_id: districtId } })
  },

  /**
   * Lấy các dịch vụ vận chuyển khả dụng đến quận
   * @param {number} districtId
   */
  getServices(districtId) {
    return api.get('/client/shipping/services', { params: { district_id: districtId } })
  },

  /**
   * Tính phí vận chuyển GHN
   * @param {Object} payload - { district_id, ward_code, service_id, weight? }
   */
  calculateFee(payload) {
    return api.post('/client/shipping/fee', payload)
  },
}
