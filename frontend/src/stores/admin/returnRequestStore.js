import { defineStore } from 'pinia'
import { ref } from 'vue'
import { returnRequestService } from '@/services/admin/returnRequestService'

export const useReturnRequestStore = defineStore('return-requests', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const returnRequests = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 4,
    total: 0,
    last_page: 1,
  })
  const loading = ref(false)
  const error = ref(null)

  // ─── Actions ─────────────────────────────────────────────────────────────

  async function fetchReturnRequests(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await returnRequestService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      // res.data.success === true
      returnRequests.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      // e.message đã được chuẩn hóa bởi http interceptor
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  async function initialFetch(params = {}) {
    if (returnRequests.value.length > 0) return
    return fetchReturnRequests(params)
  }

  async function createReturnRequest(data) {
    const res = await returnRequestService.create(data)
    await fetchReturnRequests({ page: meta.value.current_page })
    return res.data 
  }

  async function updateReturnRequest(id, data) {
    const res = await returnRequestService.update(id, data)
    await fetchReturnRequests({ page: meta.value.current_page })
    return res.data 
  }

  async function deleteReturnRequest(id) {
    const res = await returnRequestService.delete(id)
    const newPage = returnRequests.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchReturnRequests({ page: newPage })
    return res.data 
  }

  return {
    // State
    returnRequests,
    meta,
    loading,
    error,
    
    // Actions
    fetchReturnRequests,
    initialFetch,
    createReturnRequest,
    updateReturnRequest,
    deleteReturnRequest,
  }
})

