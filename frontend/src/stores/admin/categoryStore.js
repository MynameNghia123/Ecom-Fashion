import { defineStore } from 'pinia'
import { ref } from 'vue'
import { categoryService } from '@/services/admin/categoryService'

export const useCategoryStore = defineStore('category', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const categories = ref([])
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
   * Tải danh sách danh mục từ API
   * @param {Object} params - { search?, page?, per_page? }
   */
  async function fetchCategories(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await categoryService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      // res.data.success === true
      categories.value = res.data.data
      meta.value = res.data.meta
    } catch (e) {
      // e.message đã được chuẩn hóa bởi http interceptor
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  /**
   * Thêm danh mục mới
   * @param {{ name: string, slug?: string, description?: string, parent_id?: number|null }} data
   * @returns {Promise<{ success: boolean, data: object, message: string }>}
   */
  async function createCategory(data) {
    const res = await categoryService.create(data)
    await fetchCategories({ page: meta.value.current_page })
    return res.data // { success: true, data: {...}, message: '...' }
  }

  /**
   * Cập nhật danh mục
   * @param {number} id
   * @param {{ name?: string, slug?: string, description?: string, parent_id?: number|null }} data
   * @returns {Promise<{ success: boolean, data: object, message: string }>}
   */
  async function updateCategory(id, data) {
    const res = await categoryService.update(id, data)
    await fetchCategories({ page: meta.value.current_page })
    return res.data // { success: true, data: {...}, message: '...' }
  }

  /**
   * Xóa danh mục
   * @param {number} id
   * @returns {Promise<{ success: boolean, message: string }>}
   */
  async function deleteCategory(id) {
    const res = await categoryService.delete(id)
    const newPage = categories.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchCategories({ page: newPage })
    return res.data // { success: true, message: '...' }
  }

  return {
    // State
    categories,
    meta,
    loading,
    error,
    // Actions
    fetchCategories,
    createCategory,
    updateCategory,
    deleteCategory,
  }
})

