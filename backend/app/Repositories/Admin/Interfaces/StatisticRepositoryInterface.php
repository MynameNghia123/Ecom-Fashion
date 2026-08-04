<?php

namespace App\Repositories\Admin\Interfaces;

interface StatisticRepositoryInterface
{
    /**
     * Lấy các chỉ số KPI tổng quan (doanh thu, đơn hàng, khách hàng, AOV)
     * kèm % thay đổi so với kỳ trước tương ứng.
     */
    public function getOverviewStats(string $startDate, string $endDate): array;

    /**
     * Lấy dữ liệu doanh thu & lợi nhuận gộp theo từng mốc thời gian
     * cho biểu đồ đường/cột.
     * $groupBy: 'day' | 'week' | 'month'
     */
    public function getRevenueByPeriod(string $startDate, string $endDate, string $groupBy = 'day'): array;

    /**
     * Lấy doanh thu phân theo danh mục sản phẩm cho biểu đồ Doughnut.
     */
    public function getRevenueByCategory(string $startDate, string $endDate): array;

    /**
     * Phân phối trạng thái đơn hàng trong khoảng thời gian.
     */
    public function getOrderStatusDistribution(string $startDate, string $endDate): array;

    /**
     * Lấy N đơn hàng gần nhất.
     */
    public function getRecentOrders(int $limit = 5): array;

    /**
     * Top sản phẩm bán chạy theo doanh số (số lượng và doanh thu).
     */
    public function getTopSellingProducts(string $startDate, string $endDate, int $limit = 10): array;

    /**
     * Danh sách biến thể sản phẩm sắp hết hàng (tồn kho <= threshold).
     */
    public function getLowStockProducts(int $threshold = 10, int $limit = 10): array;
}
