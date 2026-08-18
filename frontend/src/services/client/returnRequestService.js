import api from '@/services/shared/http'

export default {
  /**
   * Lấy danh sách yêu cầu hoàn trả của customer (có phân trang)
   * @param {Object} params - Các query params như page, per_page
   * @returns {Promise}
   */
  getReturnRequests(params = {}) {
    return api.get('/client/return-requests', { params })
  },

  /**
   * Lấy chi tiết 1 yêu cầu hoàn trả
   * @param {Number} id - ID của return request
   * @returns {Promise}
   */
  getReturnRequestDetail(id) {
    return api.get(`/client/return-requests/${id}`)
  },

  /**
   * Tạo yêu cầu hoàn trả mới (có upload ảnh)
   * @param {FormData} formData - Chứa order_detail_id, reason, customer_note, evidence_images[]
   * @returns {Promise}
   */
  createReturnRequest(formData) {
    return api.post('/client/return-requests', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  }
}
