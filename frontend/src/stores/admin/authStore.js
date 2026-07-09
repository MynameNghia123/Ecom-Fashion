import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/services/admin/authService'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('admin_token') || null)
  const user = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // Try to parse user from localStorage on init
  const savedUser = localStorage.getItem('admin_user')
  if (savedUser) {
    try {
      user.value = JSON.parse(savedUser)
    } catch (e) {
      localStorage.removeItem('admin_user')
    }
  }

  const isAuthenticated = computed(() => !!token.value)

  async function login(credentials) {
    loading.value = true
    error.value = null
    try {
      const res = await authService.login(credentials)
      const data = res.data
      
      token.value = data.token
      user.value = data.user

      localStorage.setItem('admin_token', data.token)
      localStorage.setItem('admin_user', JSON.stringify(data.user))
      
      return data
    } catch (e) {
      error.value = e.response?.data?.message || e.message
      throw e
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    loading.value = true
    try {
      await authService.logout()
    } catch (e) {
      console.error('Logout error on server:', e)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_user')
      loading.value = false
    }
  }

  async function fetchCurrentUser() {
    if (!token.value) return null
    loading.value = true
    try {
      const res = await authService.me()
      const userData = res.data.data
      user.value = userData
      localStorage.setItem('admin_user', JSON.stringify(userData))
      return userData
    } catch (e) {
      // If fetching user fails due to auth expiration, logout locally
      if (e.response?.status === 401) {
        token.value = null
        user.value = null
        localStorage.removeItem('admin_token')
        localStorage.removeItem('admin_user')
      }
      throw e
    } finally {
      loading.value = false
    }
  }

  /**
   * Check if the authenticated user has permission for a specific module and action
   */
  function hasPermission(module, action) {
    if (!user.value) return false
    
    // Active check: inactive users have no access
    if (user.value.is_active === false) return false

    // Super admin role check: name is 'admin'
    const roles = user.value.roles || []
    if (roles.some(r => r.name === 'admin')) {
      return true
    }

    // Direct permissions or role permissions check
    const permissions = user.value.permissions || []
    return permissions.some(p => p.module === module && p.action === action)
  }

  return {
    token,
    user,
    loading,
    error,
    isAuthenticated,
    login,
    logout,
    fetchCurrentUser,
    hasPermission,
  }
})
