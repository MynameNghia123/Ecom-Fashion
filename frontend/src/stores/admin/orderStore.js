import { defineStore } from 'pinia'
import { ref } from 'vue'
import { orderService } from '@/services/admin/orderService'

export const useOrderStore = defineStore('orders', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const orders = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 4,
    total: 0,
    last_page: 1,
  })
  const loading = ref(false)
  const error = ref(null)

  // ─── Actions ─────────────────────────────────────────────────────────────

  async function fetchOrders(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await orderService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      orders.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  async function initialFetch(params = {}) {
    if (orders.value.length > 0) return
    return fetchOrders(params)
  }

  async function getOrderById(id) {
    const res = await orderService.getById(id)
    return res.data
  }

  async function createOrder(data) {
    const res = await orderService.create(data)
    await fetchOrders({ page: meta.value.current_page })
    return res.data 
  }

  async function updateOrder(id, data) {
    const res = await orderService.update(id, data)
    await fetchOrders({ page: meta.value.current_page })
    return res.data 
  }

  async function deleteOrder(id) {
    const res = await orderService.delete(id)
    const newPage = orders.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchOrders({ page: newPage })
    return res.data 
  }

  return {
    // State
    orders,
    meta,
    loading,
    error,
    
    // Actions
    fetchOrders,
    initialFetch,
    getOrderById,
    createOrder,
    updateOrder,
    deleteOrder,
  }
})
