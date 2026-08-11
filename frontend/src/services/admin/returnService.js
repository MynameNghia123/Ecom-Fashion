import http from '@/services/shared/http'

export const returnService = {
  // Lấy danh sách yêu cầu
  getReturnRequests(params) {
    return http.get('/admin/return-requests', { params })
  },
  
  // Cập nhật trạng thái yêu cầu
  updateStatus(id, data) {
    return http.patch(`/admin/return-requests/${id}/status`, data)
  }
}

