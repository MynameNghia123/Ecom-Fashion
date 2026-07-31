import { defineStore } from 'pinia'
import { ref } from 'vue'
import { customerService } from '@/services/admin/customerService'

export const useCustomerStore = defineStore('customer', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const customers = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
  })
  const loading = ref(false)
  const error = ref(null)

  // ─── Actions ─────────────────────────────────────────────────────────────

  /**
   * Tải danh sách khách hàng từ API
   * @param {Object} params - { search?, page?, per_page? }
   */
  async function fetchCustomers(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await customerService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      customers.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  /**
   * Tìm kiếm khách hàng (chủ yếu dùng cho dropdown/gợi ý)
   * Không lưu vào state `customers` để không ảnh hưởng phân trang hiện tại.
   * @param {string} keyword
   */
  async function searchCustomers(keyword) {
    try {
      const res = await customerService.searchByString(keyword)
      return res.data.data
    } catch (e) {
      console.error('Lỗi khi tìm kiếm khách hàng:', e)
      return []
    }
  }

  /**
   * Fetch lần đầu — chỉ gọi API nếu chưa có data.
   */
  async function initialFetch(params = {}) {
    if (customers.value.length > 0) return
    return fetchCustomers(params)
  }

  /**
   * Tạo khách hàng mới
   * @param {Object} data
   * @returns {Promise<any>}
   */
  async function createCustomer(data) {
    const res = await customerService.create(data)
    await fetchCustomers({ page: meta.value.current_page })
    return res.data
  }

  /**
   * Cập nhật thông tin khách hàng
   * @param {number} id
   * @param {Object} data
   * @returns {Promise<any>}
   */
  async function updateCustomer(id, data) {
    const res = await customerService.update(id, data)
    await fetchCustomers({ page: meta.value.current_page })
    return res.data
  }

  /**
   * Xóa khách hàng
   * @param {number} id
   * @returns {Promise<any>}
   */
  async function deleteCustomer(id) {
    const res = await customerService.delete(id)
    const newPage = customers.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchCustomers({ page: newPage })
    return res.data
  }

  return {
    // State
    customers,
    meta,
    loading,
    error,
    // Actions
    fetchCustomers,
    initialFetch,
    createCustomer,
    updateCustomer,
    deleteCustomer,
    searchCustomers,
  }
})
