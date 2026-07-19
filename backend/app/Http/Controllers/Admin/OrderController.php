<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreOrderRequest;
use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Http\Resources\Admin\Order\OrderResource;
use App\Models\Order;
use App\Services\Admin\Interfaces\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Orders',
    description: 'Quản lý đơn hàng bán'
)]
class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {}

    #[OA\Get(
        path: '/api/admin/orders',
        summary: 'Lấy danh sách đơn hàng (có phân trang & lọc)',
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Tìm kiếm theo mã đơn hàng, tên người nhận hoặc SĐT', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', description: 'Lọc theo trạng thái đơn hàng (pending, processing, shipping, completed, cancelled)', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'payment_status', in: 'query', description: 'Lọc theo trạng thái thanh toán (unpaid, paid, refunded)', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'customer_id', in: 'query', description: 'Lọc theo ID khách hàng', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số bản ghi mỗi trang (mặc định: 10)', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lấy danh sách thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'order_code', type: 'string', example: 'ORD-20260719-001'),
                            new OA\Property(property: 'customer_id', type: 'integer', example: 1),
                            new OA\Property(property: 'customer_name', type: 'string', example: 'Nguyễn Văn A'),
                            new OA\Property(property: 'shipping_name', type: 'string', example: 'Nguyễn Văn A'),
                            new OA\Property(property: 'shipping_phone', type: 'string', example: '0987654321'),
                            new OA\Property(property: 'shipping_address', type: 'string', example: '123 Đường ABC, Q1, TP.HCM'),
                            new OA\Property(property: 'sub_total_amount', type: 'number', example: 500000),
                            new OA\Property(property: 'coupon_discount_amount', type: 'number', example: 50000),
                            new OA\Property(property: 'shipping_fee', type: 'number', example: 30000),
                            new OA\Property(property: 'final_amount', type: 'number', example: 480000),
                            new OA\Property(property: 'status', type: 'string', example: 'pending'),
                            new OA\Property(property: 'payment_method', type: 'string', example: 'cod'),
                            new OA\Property(property: 'payment_status', type: 'string', example: 'unpaid'),
                            new OA\Property(property: 'created_at', type: 'string', example: '19/07/2026 15:30'),
                        ]
                    )),
                    new OA\Property(property: 'meta', type: 'object', properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(property: 'per_page', type: 'integer', example: 10),
                        new OA\Property(property: 'total', type: 'integer', example: 50),
                        new OA\Property(property: 'last_page', type: 'integer', example: 5),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->orderService->getList([
            'search'         => $request->query('search'),
            'status'         => $request->query('status'),
            'payment_status' => $request->query('payment_status'),
            'customer_id'    => $request->query('customer_id'),
            'per_page'       => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => OrderResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/orders',
        summary: 'Tạo đơn hàng mới (kèm mảng sản phẩm order_details)',
        tags: ['Orders'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['order_code', 'customer_id', 'shipping_name', 'shipping_phone', 'shipping_address', 'payment_method'],
                properties: [
                    new OA\Property(property: 'order_code', type: 'string', example: 'ORD-20260719-001', description: 'Mã đơn hàng (duy nhất)'),
                    new OA\Property(property: 'customer_id', type: 'integer', example: 1, description: 'ID khách hàng mua hàng'),
                    new OA\Property(property: 'coupon_id', type: 'integer', nullable: true, example: 2, description: 'ID mã giảm giá (tự động tính discount trên Server)'),
                    new OA\Property(property: 'shipping_name', type: 'string', example: 'Nguyễn Văn A', description: 'Tên người nhận hàng'),
                    new OA\Property(property: 'shipping_phone', type: 'string', example: '0987654321', description: 'Số điện thoại nhận hàng'),
                    new OA\Property(property: 'shipping_address', type: 'string', example: '123 Đường ABC, Quận 1, TP.HCM', description: 'Địa chỉ nhận hàng'),
                    new OA\Property(property: 'shipping_fee', type: 'number', example: 30000, description: 'Phí vận chuyển'),
                    new OA\Property(property: 'payment_method', type: 'string', example: 'cod', description: 'Phương thức thanh toán (cod, vnpay, momo...)'),
                    new OA\Property(property: 'status', type: 'string', example: 'pending', description: 'Trạng thái đơn hàng'),
                    new OA\Property(property: 'payment_status', type: 'string', example: 'unpaid', description: 'Trạng thái thanh toán'),
                    new OA\Property(
                        property: 'order_details',
                        type: 'array',
                        description: 'Danh sách sản phẩm thuộc đơn hàng',
                        items: new OA\Items(
                            required: ['product_variant_id', 'quantity', 'unit_price'],
                            properties: [
                                new OA\Property(property: 'product_variant_id', type: 'integer', example: 5, description: 'ID biến thể sản phẩm'),
                                new OA\Property(property: 'quantity', type: 'integer', example: 2, description: 'Số lượng mua'),
                                new OA\Property(property: 'unit_price', type: 'number', example: 250000, description: 'Đơn giá bán'),
                                new OA\Property(property: 'cost_price', type: 'number', example: 180000, description: 'Giá vốn sản phẩm'),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo đơn hàng thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object', description: 'Chi tiết đơn hàng vừa tạo kèm mảng order_details'),
                    new OA\Property(property: 'message', type: 'string', example: 'Đơn hàng đã được tạo thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu đầu vào'),
        ]
    )]
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new OrderResource($order),
            'message' => 'Đơn hàng đã được tạo thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/orders/{order}',
        summary: 'Xem chi tiết một đơn hàng',
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(name: 'order', in: 'path', description: 'ID của đơn hàng', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lấy thông tin thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object', description: 'Thông tin chi tiết đơn hàng kèm danh sách sản phẩm order_details'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy đơn hàng'),
        ]
    )]
    public function show(Order $order): JsonResponse
    {
        $order->load(['customer', 'coupon', 'orderDetails.productVariant.product']);

        return response()->json([
            'success' => true,
            'data'    => new OrderResource($order),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/orders/{order}',
        summary: 'Cập nhật đơn hàng (đồng bộ mảng sản phẩm order_details)',
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(name: 'order', in: 'path', description: 'ID của đơn hàng cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['order_code', 'customer_id', 'shipping_name', 'shipping_phone', 'shipping_address', 'payment_method'],
                properties: [
                    new OA\Property(property: 'order_code', type: 'string', example: 'ORD-20260719-001'),
                    new OA\Property(property: 'customer_id', type: 'integer', example: 1),
                    new OA\Property(property: 'coupon_id', type: 'integer', nullable: true, example: 2),
                    new OA\Property(property: 'shipping_name', type: 'string', example: 'Nguyễn Văn A'),
                    new OA\Property(property: 'shipping_phone', type: 'string', example: '0987654321'),
                    new OA\Property(property: 'shipping_address', type: 'string', example: '123 Đường ABC, Quận 1, TP.HCM'),
                    new OA\Property(property: 'shipping_fee', type: 'number', example: 30000),
                    new OA\Property(property: 'status', type: 'string', example: 'processing', description: 'Trạng thái đơn hàng (pending, processing, shipping, completed, cancelled)'),
                    new OA\Property(property: 'payment_method', type: 'string', example: 'cod'),
                    new OA\Property(property: 'payment_status', type: 'string', example: 'paid'),
                    new OA\Property(
                        property: 'order_details',
                        type: 'array',
                        description: 'Danh sách sản phẩm chi tiết cập nhật',
                        items: new OA\Items(
                            required: ['product_variant_id', 'quantity', 'unit_price'],
                            properties: [
                                new OA\Property(property: 'product_variant_id', type: 'integer', example: 5),
                                new OA\Property(property: 'quantity', type: 'integer', example: 2),
                                new OA\Property(property: 'unit_price', type: 'number', example: 250000),
                                new OA\Property(property: 'cost_price', type: 'number', example: 180000),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cập nhật thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Đơn hàng đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy đơn hàng'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu đầu vào'),
        ]
    )]
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $updatedOrder = $this->orderService->update($order, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new OrderResource($updatedOrder),
            'message' => 'Đơn hàng đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/orders/{order}',
        summary: 'Xóa đơn hàng',
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(name: 'order', in: 'path', description: 'ID của đơn hàng cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Đơn hàng đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy đơn hàng'),
        ]
    )]
    public function destroy(Order $order): JsonResponse
    {
        $this->orderService->delete($order);

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được xóa thành công.',
        ]);
    }
}
