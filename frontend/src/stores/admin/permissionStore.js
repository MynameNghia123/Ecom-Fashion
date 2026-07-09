import { defineStore } from 'pinia'
import { ref } from 'vue'
import { permissionService } from '@/services/admin/permissionService'

export const usePermissionStore = defineStore('permission', () => {
  const permissionList = ref([])
  const allPermissions = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 20,
    total: 0,
    last_page: 1,
  })
  const loading = ref(false)
  const error = ref(null)

  async function fetchPermissions(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await permissionService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      permissionList.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      error.value = e.response?.data?.message || e.message
    } finally {
      loading.value = false
    }
  }

  async function fetchAllPermissions() {
    loading.value = true
    error.value = null
    try {
      const res = await permissionService.getAllList()
      allPermissions.value = res.data.data
    } catch (e) {
      error.value = e.response?.data?.message || e.message
    } finally {
      loading.value = false
    }
  }

  return {
    permissionList,
    allPermissions,
    meta,
    loading,
    error,
    fetchPermissions,
    fetchAllPermissions,
  }
})
