import api from '@/plugins/axios'

export const productService = {
  /**
   * Lấy danh sách sản phẩm
   * @param {Object} params - { category_id, category_slug, search, page, per_page }
   */
  getProducts(params = {}) {
    return api.get('/client/products', { params })
  },

  /**
   * Lấy chi tiết sản phẩm theo ID hoặc Slug
   * @param {String|Number} idOrSlug 
   */
  getProductDetail(idOrSlug) {
    return api.get(`/client/products/${idOrSlug}`)
  },

  /**
   * Lấy danh sách đánh giá công khai của sản phẩm theo ID
   * @param {String|Number} id 
   */
  getProductReviews(id) {
    return api.get(`/client/products/${id}/reviews`)
  }
}
