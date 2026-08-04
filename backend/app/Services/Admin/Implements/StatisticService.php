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
