<?php

namespace App\Services\Admin\Implements;

use App\Repositories\Admin\Interfaces\StatisticRepositoryInterface;
use App\Services\Admin\Interfaces\StatisticServiceInterface;

class StatisticService implements StatisticServiceInterface
{
    public function __construct(
        private readonly StatisticRepositoryInterface $statisticRepository
    ) {}

    public function getDashboardData(string $startDate, string $endDate, string $groupBy): array
    {
        return [
            'overview'         => $this->statisticRepository->getOverviewStats($startDate, $endDate),
            'revenue_chart'    => $this->statisticRepository->getRevenueByPeriod($startDate, $endDate, $groupBy),
            'category_chart'   => $this->statisticRepository->getRevenueByCategory($startDate, $endDate),
            'order_status'     => $this->statisticRepository->getOrderStatusDistribution($startDate, $endDate),
            'recent_orders'    => $this->statisticRepository->getRecentOrders(5),
            'alerts'           => [
                'pending_orders'  => \App\Models\Order::where('status', 'pending')->count(),
                'return_requests' => \App\Models\ReturnRequest::where('status', 'pending')->count(),
                'low_stock'       => \App\Models\ProductVariant::where('stock_quantity', '<=', 10)->count(),
            ]
        ];
    }

    public function getTopProducts(string $startDate, string $endDate, int $limit): array
    {
        return $this->statisticRepository->getTopSellingProducts($startDate, $endDate, $limit);
    }

    public function getLowStockAlerts(int $threshold, int $limit): array
    {
        return $this->statisticRepository->getLowStockProducts($threshold, $limit);
    }
}
