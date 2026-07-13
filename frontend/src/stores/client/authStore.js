import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/services/client/authService'

export const useClientAuthStore = defineStore('clientAuth', () => {
  // ═══════════════════════════════════════════════════════════════════
  // State
  // ═══════════════════════════════════════════════════════════════════
  const user = ref(null)
  const token = ref(localStorage.getItem('customer_token') || null)
  const loading = ref(false)
  const error = ref(null)
  
  // OTP flow state
  const otpEmail = ref('')
  const resetToken = ref('')

  // ═══════════════════════════════════════════════════════════════════
  // Getters (Computed)
  // ═══════════════════════════════════════════════════════════════════
  const isAuthenticated = computed(() => !!token.value && !!user.value)

  // ═══════════════════════════════════════════════════════════════════
  // Actions
  // ═══════════════════════════════════════════════════════════════════
  
  const clearError = () => {
    error.value = null
  }

  /**
   * Đăng ký tài khoản mới (Register)
   */
  const register = async (formData) => {
    loading.value = true
    clearError()
    try {
      const response = await authService.register(formData)
      const data = response.data
      
      token.value = data.token
      user.value = data.user
      localStorage.setItem('customer_token', data.token)
      
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Đăng ký thất bại!'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  /**
   * Đăng nhập (Login)
   */
  const login = async (formData) => {
    loading.value = true
    clearError()
    try {
      const response = await authService.login(formData)
      const data = response.data
      
      token.value = data.token
      user.value = data.user
      localStorage.setItem('customer_token', data.token)
      
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Đăng nhập thất bại!'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  /**
   * Đăng xuất (Logout)
   */
  const logout = async () => {
    loading.value = true
    clearError()
    try {
      await authService.logout()
    } catch (err) {
      console.error('Lỗi khi gọi API logout:', err)
    } finally {
      user.value = null
      token.value = null
      localStorage.removeItem('customer_token')
      loading.value = false
    }
  }

  /**
   * Lấy thông tin User hiện tại (Fetch Me)
   */
  const fetchMe = async () => {
    if (!token.value) return
    
    loading.value = true
    clearError()
    try {
      const response = await authService.me()
      user.value = response.data.user
    } catch (err) {
      if (err.response?.status === 401) {
        logout()
      }
      error.value = err.response?.data?.message || 'Không thể tải thông tin cá nhân!'
    } finally {
      loading.value = false
    }
  }

  /**
   * Khởi tạo xác thực khi App bắt đầu (Init Auth)
   */
  const initAuth = async () => {
    if (token.value) {
      await fetchMe()
    }
  }

  /**
   * Quên mật khẩu - Gửi mail chứa OTP (Forgot Password)
   */
  const forgotPassword = async (email) => {
    loading.value = true
    clearError()
    try {
      const response = await authService.forgotPassword({ email })
      otpEmail.value = email
      return { success: true, message: response.data.message }
    } catch (err) {
      error.value = err.response?.data?.message || 'Không thể gửi mã OTP!'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  /**
   * Xác thực mã OTP (Verify OTP)
   */
  const verifyOtp = async (email, otp) => {
    loading.value = true
    clearError()
    try {
      const response = await authService.verifyOtp({ email, otp_code: otp })
      resetToken.value = response.data.reset_token
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Mã OTP không chính xác hoặc đã hết hạn!'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  /**
   * Đặt lại mật khẩu mới (Reset Password)
   */
  const resetPassword = async (payload) => {
    loading.value = true
    clearError()
    try {
      const response = await authService.resetPassword({
        token: resetToken.value,
        password: payload.password,
        password_confirmation: payload.password_confirmation
      })
      
      otpEmail.value = ''
      resetToken.value = ''
      
      return { success: true, message: response.data.message }
    } catch (err) {
      error.value = err.response?.data?.message || 'Đặt lại mật khẩu thất bại!'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    loading,
    error,
    otpEmail,
    resetToken,
    register,
    login,
    logout,
    fetchMe,
    initAuth,
    forgotPassword,
    verifyOtp,
    resetPassword,
    clearError
  }
})