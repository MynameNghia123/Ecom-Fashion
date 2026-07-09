import { defineStore } from 'pinia'
import { ref } from 'vue'
import { bannerService } from '@/services/admin/bannerService'

export const useBannerStore = defineStore('banner', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const banners = ref([])
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
   * Tải danh sách banner từ API
   * @param {Object} params - { search?, position?, page?, per_page? }
   */
  async function fetchBanners(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await bannerService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      banners.value = res.data.data
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
    if (banners.value.length > 0) return
    return fetchBanners(params)
  }

  /**
   * Tạo banner mới
   */
  async function createBanner(data) {
    const res = await bannerService.create(data)
    await fetchBanners({ page: 1 })
    return res.data
  }

  /**
   * Cập nhật banner
   */
  async function updateBanner(id, data) {
    const res = await bannerService.update(id, data)
    await fetchBanners({ page: meta.value.current_page })
    return res.data
  }

  /**
   * Xóa banner
   */
  async function deleteBanner(id) {
    const res = await bannerService.delete(id)
    const newPage = banners.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchBanners({ page: newPage })
    return res.data
  }

  return {
    banners,
    meta,
    loading,
    error,
    fetchBanners,
    initialFetch,
    createBanner,
    updateBanner,
    deleteBanner,
  }
})
