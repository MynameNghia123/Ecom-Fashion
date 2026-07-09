import { defineStore } from 'pinia'
import { ref } from 'vue'
import { blogService } from '@/services/admin/blogService'

export const useBlogStore = defineStore('blog', () => {
  // ─── State ───────────────────────────────────────────────────────────────
  const blogs = ref([])
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
   * Tải danh sách bài viết từ API
   * @param {Object} params - { search?, status?, page?, per_page? }
   */
  async function fetchBlogs(params = {}) {
    loading.value = true
    error.value = null
    try {
      const res = await blogService.getAll({
        per_page: meta.value.per_page,
        ...params,
      })
      blogs.value = res.data.data
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
    if (blogs.value.length > 0) return
    return fetchBlogs(params)
  }

  /**
   * Tạo bài viết mới
   */
  async function createBlog(data) {
    const res = await blogService.create(data)
    await fetchBlogs({ page: 1 })
    return res.data
  }

  /**
   * Cập nhật bài viết
   */
  async function updateBlog(id, data) {
    const res = await blogService.update(id, data)
    await fetchBlogs({ page: meta.value.current_page })
    return res.data
  }

  /**
   * Xóa bài viết
   */
  async function deleteBlog(id) {
    const res = await blogService.delete(id)
    const newPage = blogs.value.length === 1 && meta.value.current_page > 1
      ? meta.value.current_page - 1
      : meta.value.current_page
    await fetchBlogs({ page: newPage })
    return res.data
  }

  return {
    blogs,
    meta,
    loading,
    error,
    fetchBlogs,
    initialFetch,
    createBlog,
    updateBlog,
    deleteBlog,
  }
})
