import api from '@/services/shared/http'

export const aiService = {
  /**
   * Gửi tin nhắn tới AI qua backend proxy
   * @param {Array} messages - [{ role: 'user'|'model', content: string }]
   */
  sendMessage(messages, productId = null) {
    const payload = { messages }
    if (productId) {
      payload.product_id = productId
    }
    return api.post('/client/ai/chat', payload)
  },

  /**
   * Lấy lịch sử chat của customer đã đăng nhập từ DB
   */
  getHistory() {
    return api.get('/client/ai/history')
  },

  /**
   * Đồng bộ mảng tin nhắn từ Cookie vào DB khi customer vừa đăng nhập
   * @param {Array} messages 
   */
  syncGuestHistory(messages) {
    return api.post('/client/ai/sync-guest-history', { messages })
  }
}
