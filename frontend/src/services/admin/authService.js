import http from '@/services/shared/http'

const BASE = '/admin/auth'

export const authService = {
  login(credentials) {
    return http.post(`${BASE}/login`, credentials)
  },
  logout() {
    return http.post(`${BASE}/logout`)
  },
  me() {
    return http.get(`${BASE}/me`)
  }
}
