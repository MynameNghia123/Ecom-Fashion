<?php

namespace App\Services\Admin\Interfaces;

interface StatisticServiceInterface
{
    /**
     * Tổng hợp toàn bộ dữ liệu cần thiết cho trang Dashboard Thống kê.
     * Bao gồm: KPI overview, revenue chart, category chart,
     * order status distribution, recent orders.
     */
    public function getDashboardData(string $startDate, string $endDate, string $groupBy): array;

    /**
     * Lấy top sản phẩm bán chạy.
     */
    public function getTopProducts(string $startDate, string $endDate, int $limit): array;

    /**
     * Lấy danh sách sản phẩm sắp hết hàng.
     */
    public function getLowStockAlerts(int $threshold, int $limit): array;
}
