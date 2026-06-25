import http from '@/services/shared/http'

const BASE = '/admin/permissions'

export const permissionService = {
  getAll(params = {}) {
    return http.get(BASE, { params })
  },
  getAllList() {
    return http.get(`${BASE}/all`)
  }
}
