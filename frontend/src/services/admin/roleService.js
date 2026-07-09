import http from '@/services/shared/http'

const BASE = '/admin/roles'

export const roleService = {
  getAll(params = {}) {
    return http.get(BASE, { params })
  },
  getAllDropdown() {
    return http.get(`${BASE}/all`)
  },
  getById(id) {
    return http.get(`${BASE}/${id}`)
  },
  create(data) {
    return http.post(BASE, data)
  },
  update(id, data) {
    return http.put(`${BASE}/${id}`, data)
  },
  delete(id) {
    return http.delete(`${BASE}/${id}`)
  },
  syncPermissions(id, permissionIds) {
    return http.post(`${BASE}/${id}/sync-permissions`, { permission_ids: permissionIds })
  }
}
