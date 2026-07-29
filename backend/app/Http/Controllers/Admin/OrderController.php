<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\DB;

#[OA\Tag(
    name: 'Orders',
    description: 'Quản lý đơn hàng (Admin)'
)]
class OrderController extends Controller
{
    #[OA\Get(
        path: '/api/admin/orders',
        summary: 'Lấy danh sách đơn hàng (có phân trang & lọc)',
        tags: ['Admin - Orders'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm (mã đơn hàng, tên khách hàng, SĐT)', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', description: 'Lọc theo trạng thái đơn hàng', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'payment_status', in: 'query', description: 'Lọc theo trạng thái thanh toán', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số lượng bản ghi trên một trang', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lấy danh sách thành công')
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['customer']);

        // Filter by Search (order_code, shipping_name, shipping_phone)
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        // Filter by Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->query('payment_status'));
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->query('payment_method'));
        }

        // Sort by created_at DESC to show newest orders first
        $query->orderBy('created_at', 'desc');

        $perPage = (int) $request->query('per_page', 10);
        $paginator = $query->paginate($perPage);

        // Fetch overall stats for quick dashboards/counters on the admin frontend
        $stats = [
            'total_orders' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => (double) Order::where('status', '!=', 'cancelled')->sum('final_amount')
        ];

        return response()->json([
            'success' => true,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
            'stats'   => $stats
        ]);
    }

    #[OA\Get(
        path: '/api/admin/orders/{id}',
        summary: 'Lấy thông tin chi tiết một đơn hàng',
        tags: ['Admin - Orders'],
        responses: [
            new OA\Response(response: 200, description: 'Lấy chi tiết thành công')
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $order = Order::with([
            'customer',
            'coupon',
            'details.productVariant.product',
            'details.productVariant.attributeValues.attribute'
        ])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $order
        ]);
    }

    #[OA\Put(
        path: '/api/admin/orders/{id}',
        summary: 'Cập nhật trạng thái đơn hàng và thông tin vận chuyển',
        tags: ['Admin - Orders'],
        responses: [
            new OA\Response(response: 200, description: 'Cập nhật đơn hàng thành công')
        ]
    )]
    public function update(Request $request, int $id): JsonResponse
    {
        $order = Order::with('details.productVariant')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.'
            ], 404);
        }

        $request->validate([
            'status' => 'nullable|string|in:pending,confirmed,shipping,completed,cancelled',
            'payment_status' => 'nullable|string|in:unpaid,paid,refunded',
            'shipping_name' => 'nullable|string|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $newStatus = $request->input('status', $oldStatus);

            // Handle stock allocation / restoration on Cancellation status change
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                // Restore stock for all products in this order
                foreach ($order->details as $detail) {
                    if ($detail->productVariant) {
                        $detail->productVariant->increment('stock_quantity', $detail->quantity);
                    }
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // Changing back from cancelled to active -> Rededuct stock
                foreach ($order->details as $detail) {
                    if ($detail->productVariant) {
                        if ($detail->productVariant->stock_quantity < $detail->quantity) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'message' => "Sản phẩm '{$detail->productVariant->sku}' không đủ tồn kho để khôi phục đơn hàng."
                            ], 422);
                        }
                        $detail->productVariant->decrement('stock_quantity', $detail->quantity);
                    }
                }
            }

            // Update order values
            $order->fill($request->only([
                'status',
                'payment_status',
                'shipping_name',
                'shipping_phone',
                'shipping_address'
            ]));
            
            $order->save();

            $order->load([
                'customer',
                'coupon',
                'details.productVariant.product',
                'details.productVariant.attributeValues.attribute'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật đơn hàng thành công.',
                'data'    => $order
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}
