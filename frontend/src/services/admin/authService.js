import http from '@/services/shared/http'

/**
 * Service xử lý xác thực admin (JWT)
 */
export const authService = {
  /**
   * Đăng nhập — gọi POST /api/admin/auth/login
   * @param {string} email
   * @param {string} password
   * @returns {Promise<{token, data}>}
   */
  login(email, password) {
    return http.post('/admin/auth/login', { email, password })
  },

  /**
   * Đăng xuất — gọi POST /api/admin/auth/logout (yêu cầu token)
   */
  logout() {
    return http.post('/admin/auth/logout')
  },

  /**
   * Lấy thông tin staff đang đăng nhập
   */
  me() {
    return http.get('/admin/auth/me')
  },

  /**
   * Làm mới token sắp hết hạn
   */
  refresh() {
    return http.post('/admin/auth/refresh')
  },
}
