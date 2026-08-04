import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { statisticService } from '@/services/admin/statisticService'

export const useStatisticStore = defineStore('statistic', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const overview = ref({
    total_revenue: 0,
    revenue_change_percent: 0,
    total_orders: 0,
    orders_change_percent: 0,
    new_customers: 0,
    customers_change_percent: 0,
    average_order_value: 0,
    aov_change_percent: 0,
  })

  const revenueChart = ref({ labels: [], revenue: [], profit: [] })
  const categoryChart = ref({ labels: [], data: [], percentages: [] })
  const orderStatus = ref({
    pending: 0, confirmed: 0, shipping: 0, completed: 0, cancelled: 0,
  })
  const recentOrders = ref([])
  const topProducts = ref([])
  const lowStockProducts = ref([])

  // Bộ lọc thời gian hiện tại
  const dateRange = ref({
    start_date: formatDate(new Date(Date.now() - 29 * 86400000)),
    end_date:   formatDate(new Date()),
    group_by:   'day',
  })

  const loadingDashboard   = ref(false)
  const loadingTopProducts = ref(false)
  const loadingLowStock    = ref(false)
  const error              = ref(null)

  // ─── Computed ─────────────────────────────────────────────────────────────
  const isLoading = computed(() =>
    loadingDashboard.value || loadingTopProducts.value || loadingLowStock.value
  )

  // ─── Actions ─────────────────────────────────────────────────────────────

  /**
   * Lấy toàn bộ dữ liệu dashboard (KPI, biểu đồ, đơn gần đây).
   * @param {Object} params - overrides dateRange
   */
  async function fetchDashboard(params = {}) {
    loadingDashboard.value = true
    error.value = null
    try {
      const mergedParams = { ...dateRange.value, ...params }
      const res = await statisticService.getDashboard(mergedParams)
      const data = res.data.data

      overview.value       = data.overview      ?? overview.value
      revenueChart.value   = data.revenue_chart  ?? revenueChart.value
      categoryChart.value  = data.category_chart ?? categoryChart.value
      orderStatus.value    = data.order_status   ?? orderStatus.value
      recentOrders.value   = data.recent_orders  ?? []

      // Cập nhật lại dateRange từ meta nếu server trả về
      if (res.data.meta) {
        dateRange.value = {
          start_date: res.data.meta.start_date ?? dateRange.value.start_date,
          end_date:   res.data.meta.end_date   ?? dateRange.value.end_date,
          group_by:   res.data.meta.group_by   ?? dateRange.value.group_by,
        }
      }
    } catch (e) {
      error.value = e.response?.data?.message || e.message || 'Lỗi khi tải dữ liệu thống kê'
    } finally {
      loadingDashboard.value = false
    }
  }

  /**
   * Lấy top sản phẩm bán chạy.
   */
  async function fetchTopProducts(params = {}) {
    loadingTopProducts.value = true
    try {
      const mergedParams = {
        start_date: dateRange.value.start_date,
        end_date:   dateRange.value.end_date,
        limit: 10,
        ...params,
      }
      const res = await statisticService.getTopProducts(mergedParams)
      topProducts.value = res.data.data ?? []
    } catch (e) {
      error.value = e.response?.data?.message || e.message || 'Lỗi khi tải top sản phẩm'
    } finally {
      loadingTopProducts.value = false
    }
  }

  /**
   * Lấy sản phẩm sắp hết hàng.
   */
  async function fetchLowStock(params = {}) {
    loadingLowStock.value = true
    try {
      const res = await statisticService.getLowStock({ threshold: 10, limit: 15, ...params })
      lowStockProducts.value = res.data.data ?? []
    } catch (e) {
      error.value = e.response?.data?.message || e.message || 'Lỗi khi tải tồn kho'
    } finally {
      loadingLowStock.value = false
    }
  }

  /**
   * Thay đổi bộ lọc thời gian và refetch tất cả dữ liệu.
   */
  async function setDateRange(newRange) {
    dateRange.value = { ...dateRange.value, ...newRange }
    await Promise.all([
      fetchDashboard(),
      fetchTopProducts(),
    ])
  }

  return {
    // State
    overview,
    revenueChart,
    categoryChart,
    orderStatus,
    recentOrders,
    topProducts,
    lowStockProducts,
    dateRange,
    loadingDashboard,
    loadingTopProducts,
    loadingLowStock,
    isLoading,
    error,
    // Actions
    fetchDashboard,
    fetchTopProducts,
    fetchLowStock,
    setDateRange,
  }
})

// ─── Utility ──────────────────────────────────────────────────────────────────
function formatDate(date) {
  const y  = date.getFullYear()
  const m  = String(date.getMonth() + 1).padStart(2, '0')
  const d  = String(date.getDate()).padStart(2, '0')
  return `${y}-${m}-${d}`
}
