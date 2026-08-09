<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Http\Requests\Admin\Order\StoreOrderRequest;
use App\Services\Admin\Interfaces\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Orders',
    description: 'Quản lý đơn hàng (Admin)'
)]
class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {}
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
        $paginator = $this->orderService->getList([
            'search'         => $request->query('search'),
            'status'         => $request->query('status'),
            'payment_status' => $request->query('payment_status'),
            'payment_method' => $request->query('payment_method'),
            'per_page'       => (int) $request->query('per_page', 10),
        ]);

        $stats = $this->orderService->getStats();

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
        $order = $this->orderService->getDetail($id);

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
    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = $this->orderService->getDetail($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.'
            ], 404);
        }

        try {
            $updatedOrder = $this->orderService->update($order, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật đơn hàng thành công.',
                'data'    => $updatedOrder
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage()
            ], 422);
        }
    }

    #[OA\Post(
        path: '/api/admin/orders',
        summary: 'Tạo đơn hàng mới (POS)',
        tags: ['Admin - Orders'],
        responses: [
            new OA\Response(response: 201, description: 'Tạo đơn hàng thành công')
        ]
    )]
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tạo đơn hàng thành công.',
                'data'    => $order
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage()
            ], 422);
        }
    }
}
