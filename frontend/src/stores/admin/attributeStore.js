import { defineStore } from 'pinia'
import { ref } from 'vue'
import { attributeService } from '@/services/admin/attributeService'

export const useAttributeStore = defineStore('attribute', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const attributes = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 4,
    total: 0,
    last_page: 1,
  })
  const loading = ref(false)
  const error = ref(null)

  // ─── Actions ─────────────────────────────────────────────────────────────

  /**
   * Tải danh sách thuộc tính từ API
   * @param {Object} params - { search?, page?, per_page? }
   */
  async function fetchAttributes(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await attributeService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      // res.data.success === true
      attributes.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      // e.message đã được chuẩn hóa bởi http interceptor
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch lần đầu — chỉ gọi API nếu chưa có data.
   * Dùng trong onMounted để tránh re-fetch khi tab qua lại.
   */
  async function initialFetch(params = {}) {
    if (attributes.value.length > 0) return
    return fetchAttributes(params)
  }

  /**
   * @param {string} name
   * @returns {Promise<{ success: boolean, data: object, message: string }>}
   */
  async function createAttribute(name) {
    const res = await attributeService.create({ name })
    // Reload trang hiện tại để cập nhật danh sách
    await fetchAttributes({ page: meta.value.current_page })
    return res.data // { success: true, data: {...}, message: '...' }
  }

  /**
   * Cập nhật tên thuộc tính
   * @param {number} id
   * @param {string} name
   * @returns {Promise<{ success: boolean, data: object, message: string }>}
   */
  async function updateAttribute(id, name) {
    const res = await attributeService.update(id, { name })
    await fetchAttributes({ page: meta.value.current_page })
    return res.data // { success: true, data: {...}, message: '...' }
  }

  /**
   * Xóa thuộc tính
   * @param {number} id
   * @returns {Promise<{ success: boolean, message: string }>}
   */
  async function deleteAttribute(id) {
    const res = await attributeService.delete(id)
    // Nếu xóa hết trang cuối thì về trang trước
    const newPage = attributes.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchAttributes({ page: newPage })
    return res.data // { success: true, message: '...' }
  }

  return {
    // State
    attributes,
    meta,
    loading,
    error,
    // Actions
    fetchAttributes,
    initialFetch,
    createAttribute,
    updateAttribute,
    deleteAttribute,
  }
})

