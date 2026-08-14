import api from '@/plugins/axios'

export const productService = {
  /**
   * Lấy danh sách sản phẩm với bộ lọc (category_id, min_price, max_price, brand, sort, search, page, per_page)
   * @param {Object} params 
   */
  getProducts(params = {}) {
    return api.get('/client/products', { params })
  },

  /**
   * Lấy danh sách danh mục sản phẩm phục vụ filter
   */
  getCategories() {
    return api.get('/client/categories', { params: { per_page: 100 } })
  },

  /**
   * Lấy cây danh mục phân cấp (parent + children) phục vụ Mega Dropdown Menu
   */
  getCategoryTree() {
    return api.get('/client/categories/tree')
  },

  /**
   * Lấy danh sách Thương hiệu (Brands)
   */
  getBrands() {
    return api.get('/client/products/brands')
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
  },

  /**
   * Lấy sản phẩm nổi bật (được đánh giá cao nhất)
   * @param {Number} limit - Số lượng sản phẩm tối đa
   */
  getTopRatedProducts(limit = 8) {
    return api.get('/client/products/top-rated', { params: { per_page: limit } })
  }
}
