import { defineStore } from 'pinia'
import { ref } from 'vue'
import { orderService } from '@/services/admin/orderService'

export const useOrderStore = defineStore('order', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const orders = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
  })
  const stats = ref({
    total_orders: 0,
    pending: 0,
    confirmed: 0,
    shipping: 0,
    completed: 0,
    cancelled: 0,
    total_revenue: 0.0
  })
  const currentOrder = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // ─── Actions ─────────────────────────────────────────────────────────────

  /**
   * Tải danh sách đơn hàng từ API
   * @param {Object} params - { search?, status?, payment_status?, page?, per_page? }
   */
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
      if (res.data.stats) {
        stats.value = res.data.stats
      }
    } catch (e) {
      error.value = e.message || 'Lỗi khi tải danh sách đơn hàng'
    } finally {
      loading.value = false
    }
  }

  /**
   * Tải chi tiết một đơn hàng
   * @param {number} id
   */
  async function fetchOrderById(id) {
    loading.value = true
    error.value = null
    currentOrder.value = null
    try {
      const res = await orderService.getById(id)
      currentOrder.value = res.data.data
      return res.data.data
    } catch (e) {
      error.value = e.message || 'Lỗi khi tải chi tiết đơn hàng'
      throw e
    } finally {
      loading.value = false
    }
  }

  /**
   * Cập nhật thông tin/trạng thái đơn hàng
   * @param {number} id
   * @param {Object} data
   */
  async function updateOrder(id, data) {
    loading.value = true
    error.value = null
    try {
      const res = await orderService.update(id, data)
      // Refresh details if updating the currently viewed order
      if (currentOrder.value && currentOrder.value.id === id) {
        currentOrder.value = { ...currentOrder.value, ...res.data.data }
      }
      // Refresh list
      await fetchOrders({ page: meta.value.current_page })
      return res.data
    } catch (e) {
      error.value = e.message || 'Lỗi khi cập nhật đơn hàng'
      throw e
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    orders,
    meta,
    stats,
    currentOrder,
    loading,
    error,
    // Actions
    fetchOrders,
    fetchOrderById,
    updateOrder,
  }
})
