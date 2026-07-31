import { defineStore } from 'pinia'
import { ref } from 'vue'
import { roleService } from '@/services/admin/roleService'

export const useRoleStore = defineStore('role', () => {
  const roleList = ref([])
  const dropdownRoles = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
  })
  const loading = ref(false)
  const error = ref(null)

  async function fetchRoles(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await roleService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      roleList.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      error.value = e.response?.data?.message || e.message
    } finally {
      loading.value = false
    }
  }

  async function fetchDropdownRoles() {
    loading.value = true
    error.value = null
    try {
      const res = await roleService.getAllDropdown()
      dropdownRoles.value = res.data.data
    } catch (e) {
      error.value = e.response?.data?.message || e.message
    } finally {
      loading.value = false
    }
  }

  async function createRole(data) {
    loading.value = true
    error.value = null
    try {
      const res = await roleService.create(data)
      // Refresh cả 2 view để dropdown ở các trang khác cũng có role mới
      await Promise.all([
        fetchRoles({ page: meta.value.current_page }),
        fetchDropdownRoles(),
      ])
      return res.data
    } catch (e) {
      error.value = e.response?.data?.message || e.message
      throw e
    } finally {
      loading.value = false
    }
  }

  async function updateRole(id, data) {
    loading.value = true
    error.value = null
    try {
      const res = await roleService.update(id, data)
      // Refresh cả 2: danh sách phân trang VÀ dropdown cache
      // để các trang khác (Staff) thấy tên role mới ngay lập tức
      await Promise.all([
        fetchRoles({ page: meta.value.current_page }),
        fetchDropdownRoles(),
      ])
      return res.data
    } catch (e) {
      error.value = e.response?.data?.message || e.message
      throw e
    } finally {
      loading.value = false
    }
  }

  async function deleteRole(id) {
    loading.value = true
    error.value = null
    try {
      const res = await roleService.delete(id)
      const newPage = roleList.value.length === 1 && meta.value.current_page > 1
        ? meta.value.current_page - 1
        : meta.value.current_page
      // Refresh cả 2 view để dropdown không còn chứa role đã xóa
      await Promise.all([
        fetchRoles({ page: newPage }),
        fetchDropdownRoles(),
      ])
      return res.data
    } catch (e) {
      error.value = e.response?.data?.message || e.message
      throw e
    } finally {
      loading.value = false
    }
  }

  async function syncRolePermissions(id, permissionIds) {
    loading.value = true
    error.value = null
    try {
      const res = await roleService.syncPermissions(id, permissionIds)
      await fetchRoles({ page: meta.value.current_page })
      return res.data
    } catch (e) {
      error.value = e.response?.data?.message || e.message
      throw e
    } finally {
      loading.value = false
    }
  }

  return {
    roleList,
    dropdownRoles,
    meta,
    loading,
    error,
    fetchRoles,
    fetchDropdownRoles,
    createRole,
    updateRole,
    deleteRole,
    syncRolePermissions,
  }
})
