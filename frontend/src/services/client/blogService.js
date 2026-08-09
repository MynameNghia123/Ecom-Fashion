import api from '@/plugins/axios'

export const blogService = {
  /**
   * Lấy danh sách bài viết blog đang hoạt động (phía client).
   * @param {Object} params - { page?, per_page? }
   */
  getBlogs(params = {}) {
    return api.get('/client/blogs', { params })
  },

  /**
   * Lấy chi tiết bài viết theo slug.
   * @param {string} slug
   */
  getBlogDetail(slug) {
    return api.get(`/client/blogs/${slug}`)
  },
}
