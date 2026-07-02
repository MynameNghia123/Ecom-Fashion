import { defineStore } from 'pinia'
import { ref } from 'vue'
import { staffService } from '@/services/admin/staffService'

export const useStaffStore = defineStore('staff', () => {
  const staffList = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
  })
  const loading = ref(false)
  const error = ref(null)

  async function fetchStaffs(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await staffService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      staffList.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      error.value = e.response?.data?.message || e.message
    } finally {
      loading.value = false
    }
  }

  async function createStaff(data) {
    const res = await staffService.create(data)
    await fetchStaffs({ page: meta.value.current_page })
    return res.data
  }

  async function updateStaff(id, data) {
    const res = await staffService.update(id, data)
    await fetchStaffs({ page: meta.value.current_page })
    return res.data
  }

  async function deleteStaff(id) {
    const res = await staffService.delete(id)
    const newPage = staffList.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchStaffs({ page: newPage })
    return res.data
  }

  return {
    staffList,
    meta,
    loading,
    error,
    fetchStaffs,
    createStaff,
    updateStaff,
    deleteStaff,
  }
})
