import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/services/admin/authService'

export const useAuthStore = defineStore('admin-auth', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const token       = ref(localStorage.getItem('admin_token') || null)
  const user        = ref(JSON.parse(localStorage.getItem('admin_user')        || 'null'))
  const permissions = ref(JSON.parse(localStorage.getItem('admin_permissions') || '[]'))

  // ─── Getters ─────────────────────────────────────────────────────────────
  const isAuthenticated = computed(() => !!token.value)

  /**
   * Kiểm tra xem staff có quyền thực hiện action trên module không.
   * @param {string} module  - VD: 'products', 'orders'
   * @param {string} action  - VD: 'read', 'create', 'update', 'delete'
   */
  function can(module, action) {
    return permissions.value.includes(`${module}:${action}`)
  }

  /**
   * Kiểm tra có BẤT KỲ quyền nào trong danh sách không.
   * @param  {...string} perms - VD: 'products:read', 'orders:read'
   */
  function canAny(...perms) {
    return perms.some(p => permissions.value.includes(p))
  }

  /**
   * Kiểm tra có TẤT CẢ quyền trong danh sách không.
   * @param  {...string} perms
   */
  function canAll(...perms) {
    return perms.every(p => permissions.value.includes(p))
  }

  // ─── Actions ─────────────────────────────────────────────────────────────

  /**
   * Đăng nhập: gọi API, lưu token + thông tin user + permissions vào localStorage
   */
  async function login(email, password) {
    const res = await authService.login(email, password)
    const { token: jwt, data: staff, permissions: perms } = res.data

    token.value       = jwt
    user.value        = staff
    permissions.value = perms || []

    localStorage.setItem('admin_token',       jwt)
    localStorage.setItem('admin_user',        JSON.stringify(staff))
    localStorage.setItem('admin_permissions', JSON.stringify(perms || []))
  }

  /**
   * Đăng xuất: gọi API invalidate token, xóa localStorage
   */
  async function logout() {
    try {
      await authService.logout()
    } catch {
      // Bỏ qua lỗi từ server (token đã hết hạn v.v.)
    } finally {
      _clearAuth()
    }
  }

  /**
   * Kiểm tra xác thực khi app khởi động / F5
   * Gọi GET /admin/auth/me để xác nhận token còn hợp lệ + refresh permissions
   */
  async function checkAuth() {
    if (!token.value) return false
    try {
      const res = await authService.me()
      const { data: staff, permissions: perms } = res.data

      user.value        = staff
      permissions.value = perms || []

      localStorage.setItem('admin_user',        JSON.stringify(staff))
      localStorage.setItem('admin_permissions', JSON.stringify(perms || []))
      return true
    } catch {
      _clearAuth()
      return false
    }
  }

  // ─── Private helpers ─────────────────────────────────────────────────────
  function _clearAuth() {
    token.value       = null
    user.value        = null
    permissions.value = []
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    localStorage.removeItem('admin_permissions')
  }

  return {
    // State
    token,
    user,
    permissions,
    // Getters
    isAuthenticated,
    can,
    canAny,
    canAll,
    // Actions
    login,
    logout,
    checkAuth,
  }
})
