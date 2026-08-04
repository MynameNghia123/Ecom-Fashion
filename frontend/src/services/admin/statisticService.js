import http from '@/services/shared/http'

const BASE = '/admin/statistics'

export const statisticService = {
  /**
   * Lấy toàn bộ dữ liệu Dashboard Thống kê.
   * @param {Object} params - { start_date?, end_date?, group_by? }
   *   group_by: 'day' | 'week' | 'month'
   */
  getDashboard(params = {}) {
    return http.get(`${BASE}/dashboard`, { params })
  },

  /**
   * Lấy top sản phẩm bán chạy.
   * @param {Object} params - { start_date?, end_date?, limit? }
   */
  getTopProducts(params = {}) {
    return http.get(`${BASE}/top-products`, { params })
  },

  /**
   * Lấy danh sách sản phẩm sắp hết hàng.
   * @param {Object} params - { threshold?, limit? }
   */
  getLowStock(params = {}) {
    return http.get(`${BASE}/low-stock`, { params })
  },
}
