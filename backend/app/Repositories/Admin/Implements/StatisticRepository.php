<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Repositories\Admin\Interfaces\StatisticRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StatisticRepository implements StatisticRepositoryInterface
{
    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Tính % thay đổi từ giá trị cũ sang mới.
     */
    private function calcChangePercent(float $previous, float $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Lấy khoảng thời gian tương ứng trước đó (cùng độ dài).
     */
    private function getPreviousPeriod(string $startDate, string $endDate): array
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $diff = $start->diff($end)->days + 1;

        $prevEnd = (clone $start)->modify('-1 day');
        $prevStart = (clone $prevEnd)->modify("-{$diff} days")->modify('+1 day');

        return [
            $prevStart->format('Y-m-d 00:00:00'),
            $prevEnd->format('Y-m-d 23:59:59'),
        ];
    }

    // ── Implementation ───────────────────────────────────────────────────────

    public function getOverviewStats(string $startDate, string $endDate): array
    {
        $start = $startDate.' 00:00:00';
        $end = $endDate.' 23:59:59';
        [$prevStart, $prevEnd] = $this->getPreviousPeriod($startDate, $endDate);

        // Doanh thu (loại trừ đơn đã hủy)
        $revenue = (float) Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('final_amount');
        $prevRevenue = (float) Order::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('final_amount');

        // Tổng đơn hàng
        $orders = Order::whereBetween('created_at', [$start, $end])->count();
        $prevOrders = Order::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        // Khách hàng mới
        $newCustomers = Customer::whereBetween('created_at', [$start, $end])->count();
        $prevNewCustomers = Customer::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        // Giá trị đơn trung bình (AOV)
        $aov = $orders > 0 ? $revenue / $orders : 0;
        $prevAov = $prevOrders > 0 ? $prevRevenue / $prevOrders : 0;

        return [
            'total_revenue' => $revenue,
            'revenue_change_percent' => $this->calcChangePercent($prevRevenue, $revenue),
            'total_orders' => $orders,
            'orders_change_percent' => $this->calcChangePercent($prevOrders, $orders),
            'new_customers' => $newCustomers,
            'customers_change_percent' => $this->calcChangePercent($prevNewCustomers, $newCustomers),
            'average_order_value' => round($aov, 2),
            'aov_change_percent' => $this->calcChangePercent($prevAov, $aov),
        ];
    }

    public function getRevenueByPeriod(string $startDate, string $endDate, string $groupBy = 'day'): array
    {
        $start = $startDate.' 00:00:00';
        $end = $endDate.' 23:59:59';

        // Chọn format nhóm theo yêu cầu
        $dateFormat = match ($groupBy) {
            'month' => '%Y-%m',
            'week' => '%Y-W%u',
            default => '%Y-%m-%d',
        };

        $rows = DB::table('orders')
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period")
            ->selectRaw('SUM(CASE WHEN status != \'cancelled\' THEN final_amount ELSE 0 END) as revenue')
            ->selectRaw('SUM(CASE WHEN status != \'cancelled\' THEN final_amount ELSE 0 END) - SUM(CASE WHEN status != \'cancelled\' THEN (SELECT IFNULL(SUM(od.cost_price * od.quantity), 0) FROM order_details od WHERE od.order_id = orders.id) ELSE 0 END) as gross_profit')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Tính gross_profit đơn giản: revenue - cost
        $rows2 = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw("DATE_FORMAT(orders.created_at, '{$dateFormat}') as period")
            ->selectRaw('SUM(CASE WHEN orders.status != \'cancelled\' THEN orders.final_amount ELSE 0 END) as revenue')
            ->selectRaw('SUM(CASE WHEN orders.status != \'cancelled\' THEN (order_details.unit_price - order_details.cost_price) * order_details.quantity ELSE 0 END) as gross_profit')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupByRaw("DATE_FORMAT(orders.created_at, '{$dateFormat}')")
            ->orderByRaw("DATE_FORMAT(orders.created_at, '{$dateFormat}')")
            ->get();

        $labels = [];
        $revenueData = [];
        $profitData = [];

        foreach ($rows2 as $row) {
            $labels[] = $row->period;
            $revenueData[] = round((float) $row->revenue, 2);
            $profitData[] = round((float) $row->gross_profit, 2);
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'profit' => $profitData,
        ];
    }

    public function getRevenueByCategory(string $startDate, string $endDate): array
    {
        $start = $startDate.' 00:00:00';
        $end = $endDate.' 23:59:59';

        $rows = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category')
            ->selectRaw('SUM(order_details.unit_price * order_details.quantity) as revenue')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('categories.id', 'categories.name')
            ->orderByRaw('revenue DESC')
            ->limit(8)
            ->get();

        $total = $rows->sum('revenue');

        $labels = [];
        $data = [];
        $percentages = [];

        foreach ($rows as $row) {
            $labels[] = $row->category;
            $data[] = round((float) $row->revenue, 2);
            $percentages[] = $total > 0 ? round(($row->revenue / $total) * 100, 1) : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'percentages' => $percentages,
        ];
    }

    public function getOrderStatusDistribution(string $startDate, string $endDate): array
    {
        $start = $startDate.' 00:00:00';
        $end = $endDate.' 23:59:59';

        $rows = DB::table('orders')
            ->selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return [
            'pending' => (int) ($rows['pending']->count ?? 0),
            'confirmed' => (int) ($rows['confirmed']->count ?? 0),
            'shipping' => (int) ($rows['shipping']->count ?? 0),
            'completed' => (int) ($rows['completed']->count ?? 0),
            'cancelled' => (int) ($rows['cancelled']->count ?? 0),
        ];
    }

    public function getRecentOrders(int $limit = 5): array
    {
        return Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'order_code' => $o->order_code,
                'customer_name' => $o->customer
                    ? trim(($o->customer->first_name ?? '').' '.($o->customer->last_name ?? ''))
                    : $o->shipping_name,
                'final_amount' => (float) $o->final_amount,
                'status' => $o->status,
                'payment_method' => $o->payment_method,
                'created_at' => $o->created_at,
            ])
            ->toArray();
    }

    public function getTopSellingProducts(string $startDate, string $endDate, int $limit = 10): array
    {
        $start = $startDate.' 00:00:00';
        $end = $endDate.' 23:59:59';

        return DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->selectRaw('products.id, products.name, products.thumbnail')
            ->selectRaw('SUM(order_details.quantity) as total_sold')
            ->selectRaw('SUM(order_details.unit_price * order_details.quantity) as total_revenue')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByRaw('total_sold DESC')
            ->limit($limit)
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'thumbnail' => $r->thumbnail,
                'total_sold' => (int) $r->total_sold,
                'total_revenue' => round((float) $r->total_revenue, 2),
            ])
            ->toArray();
    }

    public function getLowStockProducts(int $threshold = 10, int $limit = 10): array
    {
        return ProductVariant::with('product')
            ->where('stock_quantity', '<=', $threshold)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->limit($limit)
            ->get()
            ->map(fn ($v) => [
                'variant_id' => $v->id,
                'sku' => $v->sku,
                'stock_quantity' => $v->stock_quantity,
                'product_id' => $v->product?->id,
                'product_name' => $v->product?->name,
                'thumbnail' => $v->product?->thumbnail,
                'price' => (float) $v->price,
            ])
            ->toArray();
    }
}
