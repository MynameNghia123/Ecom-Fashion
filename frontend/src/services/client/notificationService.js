import axios from '@/services/shared/http'

export const notificationService = {
  getNotifications(page = 1, perPage = 10) {
    return axios.get(`/client/notifications?page=${page}&per_page=${perPage}`)
  },
  
  getUnreadCount() {
    return axios.get('/client/notifications/unread-count')
  },
  
  markAsRead(id) {
    return axios.patch(`/client/notifications/${id}/read`)
  },
  
  markAllAsRead() {
    return axios.patch('/client/notifications/read-all')
  }
}
