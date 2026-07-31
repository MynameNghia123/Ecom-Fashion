import { defineStore } from 'pinia'
import { ref } from 'vue'
import { couponService } from '@/services/admin/couponService'

export const useCouponStore = defineStore('coupon', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const coupons = ref([])
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
   * Tải danh sách mã giảm giá từ API
   * @param {Object} params - { search?, page?, per_page?, type?, is_active? }
   */
  async function fetchCoupons(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await couponService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      coupons.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch lần đầu — chỉ gọi API nếu chưa có data.
   */
  async function initialFetch(params = {}) {
    if (coupons.value.length > 0) return
    return fetchCoupons(params)
  }

  /**
   * Tạo mã giảm giá mới
   * @param {Object} data
   * @returns {Promise<any>}
   */
  async function createCoupon(data) {
    const res = await couponService.create(data)
    await fetchCoupons({ page: meta.value.current_page })
    return res.data
  }

  /**
   * Cập nhật mã giảm giá
   * @param {number} id
   * @param {Object} data
   * @returns {Promise<any>}
   */
  async function updateCoupon(id, data) {
    const res = await couponService.update(id, data)
    await fetchCoupons({ page: meta.value.current_page })
    return res.data
  }

  /**
   * Xóa mã giảm giá
   * @param {number} id
   * @returns {Promise<any>}
   */
  async function deleteCoupon(id) {
    const res = await couponService.delete(id)
    const newPage = coupons.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchCoupons({ page: newPage })
    return res.data
  }

  /**
   * Kiểm tra mã giảm giá
   * @param {Object} data - { code, order_total }
   * @returns {Promise<any>}
   */
  async function checkCoupon(data) {
    const res = await couponService.check(data)
    return res.data
  }

  return {
    // State
    coupons,
    meta,
    loading,
    error,
    // Actions
    fetchCoupons,
    initialFetch,
    createCoupon,
    updateCoupon,
    deleteCoupon,
    checkCoupon,
  }
})
