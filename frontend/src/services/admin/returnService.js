import api from '@/services/api'

export const returnService = {
  // Lấy danh sách yêu cầu
  getReturnRequests(params) {
    return api.get('/admin/return-requests', { params })
  },
  
  // Cập nhật trạng thái yêu cầu
  updateStatus(id, data) {
    return api.patch(`/admin/return-requests/${id}/status`, data)
  }
}
