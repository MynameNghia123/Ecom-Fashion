import { defineStore } from 'pinia'
import { ref, watch } from 'vue'
import { aiService } from '@/services/client/aiService'

const COOKIE_NAME = 'ai_chat_history'

const getCookie = (name) => {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return parts.pop().split(';').shift()
  return null
}

const setCookie = (name, value, days = 7) => {
  const d = new Date()
  d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000)
  const expires = `expires=${d.toUTCString()}`
  document.cookie = `${name}=${encodeURIComponent(value)};${expires};path=/`
}

const deleteCookie = (name) => {
  document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`
}

export const useAiChatStore = defineStore('aiChat', () => {
  const isOpen = ref(false)
  const isLoading = ref(false)
  const messages = ref([
    {
      role: 'model',
      content: 'Xin chào! Tôi là Trợ lý thời trang AI của Ecom Fashion. Tôi có thể giúp gì cho bạn hôm nay?'
    }
  ])

  const defaultGreeting = {
    role: 'model',
    content: 'Xin chào! Tôi là Trợ lý thời trang AI của Ecom Fashion. Tôi có thể giúp gì cho bạn hôm nay?'
  }

  // Tải lịch sử chat
  const loadHistory = async () => {
    const token = localStorage.getItem('customer_token')

    if (token) {
      // 1. Kiểm tra xem có cookie tạm từ khách vãng lai trước khi login không
      const guestCookie = getCookie(COOKIE_NAME)
      if (guestCookie) {
        try {
          const cookieMsgs = JSON.parse(decodeURIComponent(guestCookie))
          if (Array.isArray(cookieMsgs) && cookieMsgs.length > 0) {
            // Gửi lên server đồng bộ vào DB
            await aiService.syncGuestHistory(cookieMsgs)
            deleteCookie(COOKIE_NAME)
          }
        } catch (e) {
          console.error('Error syncing guest cookie history:', e)
        }
      }

      // 2. Tải lịch sử chính thức từ Database
      try {
        const res = await aiService.getHistory()
        if (res.data && res.data.success && Array.isArray(res.data.data) && res.data.data.length > 0) {
          messages.value = [defaultGreeting, ...res.data.data]
          return
        }
      } catch (err) {
        console.error('Failed to load chat history from DB:', err)
      }
    } else {
      // 3. Khách chưa đăng nhập -> Đọc từ Cookie
      try {
        const savedCookie = getCookie(COOKIE_NAME)
        if (savedCookie) {
          const parsed = JSON.parse(decodeURIComponent(savedCookie))
          if (Array.isArray(parsed) && parsed.length > 0) {
            messages.value = parsed
            return
          }
        }
      } catch (e) {
        console.error('Failed to parse chat history from cookie:', e)
      }
    }
  }

  // Lưu lịch sử (Chỉ lưu Cookie nếu chưa Đăng nhập)
  const saveHistory = () => {
    const token = localStorage.getItem('customer_token')
    if (!token) {
      try {
        setCookie(COOKIE_NAME, JSON.stringify(messages.value), 7)
      } catch (e) {
        console.error('Failed to save chat history to cookie:', e)
      }
    }
  }

  watch(messages, () => {
    saveHistory()
  }, { deep: true })

  const toggleChat = () => {
    isOpen.value = !isOpen.value
    if (isOpen.value) {
      loadHistory()
    }
  }

  const sendMessage = async (text) => {
    if (!text.trim() || isLoading.value) return

    const userMsg = { role: 'user', content: text.trim() }
    messages.value.push(userMsg)
    isLoading.value = true

    try {
      const apiMessages = messages.value.map(m => ({
        role: m.role,
        content: m.content
      }))

      // Tự động phát hiện product_id từ URL (ví dụ: /products/7)
      let productId = null;
      const urlMatch = window.location.pathname.match(/\/products\/(\d+)/);
      if (urlMatch && urlMatch[1]) {
        productId = parseInt(urlMatch[1]);
      }

      const res = await aiService.sendMessage(apiMessages, productId)
      if (res.data && res.data.success) {
        messages.value.push({
          role: 'model',
          content: res.data.reply
        })
      } else {
        messages.value.push({
          role: 'model',
          content: res.data.message || 'Rất tiếc, đã có lỗi xảy ra. Vui lòng thử lại.'
        })
      }
    } catch (err) {
      const errorMsg = err.response?.data?.message || 'Không thể kết nối đến máy chủ AI. Vui lòng kiểm tra lại!'
      messages.value.push({
        role: 'model',
        content: errorMsg
      })
    } finally {
      isLoading.value = false
    }
  }

  const clearHistory = () => {
    messages.value = [defaultGreeting]
    deleteCookie(COOKIE_NAME)
  }

  // Khởi tạo
  loadHistory()

  return {
    isOpen,
    isLoading,
    messages,
    toggleChat,
    sendMessage,
    clearHistory,
    loadHistory
  }
})
