import { defineStore } from 'pinia'
import { ref } from 'vue'
import { notificationService } from '@/services/client/notificationService'

export const useNotificationStore = defineStore('client_notification', () => {
  const notifications = ref([])
  const unreadCount = ref(0)
  const loading = ref(false)
  
  const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0
  })

  const fetchUnreadCount = async () => {
    try {
      const res = await notificationService.getUnreadCount()
      if (res.data?.success) {
        unreadCount.value = res.data.data.count
      }
    } catch (err) {
      console.error('Lỗi tải số lượng thông báo chưa đọc:', err)
    }
  }

  const fetchNotifications = async (page = 1) => {
    loading.value = true
    try {
      const res = await notificationService.getNotifications(page, meta.value.per_page)
      if (res.data?.success) {
        if (page === 1) {
          notifications.value = res.data.data
        } else {
          notifications.value = [...notifications.value, ...res.data.data]
        }
        meta.value = res.data.meta
      }
    } catch (err) {
      console.error('Lỗi tải thông báo:', err)
    } finally {
      loading.value = false
    }
  }

  const markAsRead = async (id) => {
    const notification = notifications.value.find(n => n.id === id)
    if (notification && !notification.is_read) {
      notification.is_read = true
      unreadCount.value = Math.max(0, unreadCount.value - 1)
      
      try {
        await notificationService.markAsRead(id)
      } catch (err) {
        // Rollback on fail
        notification.is_read = false
        unreadCount.value += 1
      }
    }
  }

  const markAllAsRead = async () => {
    if (unreadCount.value === 0) return
    
    // Optimistic update
    notifications.value.forEach(n => { n.is_read = true })
    const prevCount = unreadCount.value
    unreadCount.value = 0

    try {
      await notificationService.markAllAsRead()
    } catch (err) {
      // Rollback on fail
      unreadCount.value = prevCount
      console.error('Lỗi đánh dấu tất cả đã đọc:', err)
    }
  }
  
  const reset = () => {
    notifications.value = []
    unreadCount.value = 0
    meta.value = { current_page: 1, last_page: 1, per_page: 10, total: 0 }
  }

  return {
    notifications,
    unreadCount,
    loading,
    meta,
    fetchUnreadCount,
    fetchNotifications,
    markAsRead,
    markAllAsRead,
    reset
  }
})
