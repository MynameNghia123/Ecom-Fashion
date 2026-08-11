import { defineStore } from 'pinia'
import { ref } from 'vue'
import { returnService } from '@/services/admin/returnService'

export const useReturnStore = defineStore('returnStore', () => {
  const requests = ref([])
  const stats = ref({
    total: 0,
    pending: 0,
    approved: 0,
    received: 0,
    refunded: 0,
    rejected: 0
  })
  
  const loading = ref(false)
  const error = ref(null)

  const paginationMeta = ref({
    current_page: 1,
    per_page: 15,
    total: 0,
    last_page: 1,
  })

  async function fetchReturnRequests(params = {}) {
    loading.value = true
    error.value = null
    try {
      const query = {
        page: paginationMeta.value.current_page,
        per_page: paginationMeta.value.per_page,
        ...params
      }
      const response = await returnService.getReturnRequests(query)
      requests.value = response.data.data
      
      if (response.data.meta) {
        paginationMeta.value = response.data.meta
      }
      
      if (response.data.stats) {
        stats.value = { ...stats.value, ...response.data.stats }
      }
    } catch (err) {
      error.value = err.response?.data?.message || err.message || 'Lỗi khi lấy danh sách yêu cầu đổi trả'
    } finally {
      loading.value = false
    }
  }

  async function updateStatus(id, newStatus, adminNote = '') {
    loading.value = true
    error.value = null
    try {
      const response = await returnService.updateStatus(id, { status: newStatus, admin_note: adminNote })
      // Cập nhật local
      const index = requests.value.findIndex(r => r.id === id)
      if (index !== -1 && response.data.data) {
        requests.value[index] = response.data.data
      }
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message || 'Lỗi khi cập nhật trạng thái'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    requests,
    stats,
    loading,
    error,
    paginationMeta,
    fetchReturnRequests,
    updateStatus
  }
})
