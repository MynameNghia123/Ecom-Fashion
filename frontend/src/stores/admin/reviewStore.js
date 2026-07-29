import { defineStore } from 'pinia'
import { ref } from 'vue'
import { reviewService } from '@/services/admin/reviewService'

export const useReviewStore = defineStore('adminReview', () => {
  // State
  const reviews = ref([])
  const meta = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
    average: 0,
    star_stats: {
      5: { count: 0, percentage: '0%' },
      4: { count: 0, percentage: '0%' },
      3: { count: 0, percentage: '0%' },
      2: { count: 0, percentage: '0%' },
      1: { count: 0, percentage: '0%' }
    }
  })
  const loading = ref(false)
  const error = ref(null)

  // Actions
  async function fetchReviews(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await reviewService.getAll({
        per_page: meta.value.per_page,
        ...params
      })
      if (res.data && res.data.success) {
        reviews.value = res.data.data
        meta.value = res.data.meta
      }
    } catch (e) {
      error.value = e.message || 'Lỗi khi tải danh sách nhận xét.'
    } finally {
      loading.value = false
    }
  }

  async function deleteReview(id) {
    loading.value = true
    error.value = null
    try {
      const res = await reviewService.delete(id)
      
      // Tính toán chuyển trang nếu xóa item cuối cùng ở trang hiện tại
      const newPage = reviews.value.length === 1 && meta.value.current_page > 1
        ? meta.value.current_page - 1
        : meta.value.current_page

      await fetchReviews({ page: newPage })
      return res.data
    } catch (e) {
      error.value = e.message || 'Lỗi khi xóa nhận xét.'
      throw e
    } finally {
      loading.value = false
    }
  }

  return {
    reviews,
    meta,
    loading,
    error,
    fetchReviews,
    deleteReview
  }
})
