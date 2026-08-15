<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\Interfaces\StatisticServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function __construct(
        private readonly StatisticServiceInterface $statisticService
    ) {}

    /**
     * GET /api/admin/statistics/dashboard
     * Trả về toàn bộ dữ liệu cần thiết cho trang thống kê:
     * KPI overview, revenue chart, category chart, order status, recent orders.
     *
     * @queryParam start_date  string  Ngày bắt đầu (Y-m-d). Mặc định 30 ngày trước.
     * @queryParam end_date    string  Ngày kết thúc (Y-m-d). Mặc định hôm nay.
     * @queryParam group_by    string  Nhóm theo: day | week | month. Mặc định day.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $endDate = $request->query('end_date', now()->format('Y-m-d'));
        $startDate = $request->query('start_date', now()->subDays(29)->format('Y-m-d'));
        $groupBy = $request->query('group_by', 'day');

        // Validate groupBy
        if (! in_array($groupBy, ['day', 'week', 'month'])) {
            $groupBy = 'day';
        }

        try {
            $data = $this->statisticService->getDashboardData($startDate, $endDate, $groupBy);

            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'group_by' => $groupBy,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu thống kê: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/admin/statistics/top-products
     * Top sản phẩm bán chạy trong khoảng thời gian.
     *
     * @queryParam start_date  string  Y-m-d. Mặc định 30 ngày trước.
     * @queryParam end_date    string  Y-m-d. Mặc định hôm nay.
     * @queryParam limit       int     Số sản phẩm trả về. Mặc định 10.
     */
    public function topProducts(Request $request): JsonResponse
    {
        $endDate = $request->query('end_date', now()->format('Y-m-d'));
        $startDate = $request->query('start_date', now()->subDays(29)->format('Y-m-d'));
        $limit = (int) $request->query('limit', 10);

        try {
            $products = $this->statisticService->getTopProducts($startDate, $endDate, $limit);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy top sản phẩm: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/admin/statistics/low-stock
     * Danh sách biến thể sản phẩm sắp hết hàng.
     *
     * @queryParam threshold  int  Ngưỡng tồn kho cảnh báo. Mặc định 10.
     * @queryParam limit      int  Số dòng trả về. Mặc định 15.
     */
    public function lowStock(Request $request): JsonResponse
    {
        $threshold = (int) $request->query('threshold', 10);
        $limit = (int) $request->query('limit', 15);

        try {
            $products = $this->statisticService->getLowStockAlerts($threshold, $limit);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu tồn kho: '.$e->getMessage(),
            ], 500);
        }
    }
}
