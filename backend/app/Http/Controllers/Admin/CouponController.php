<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Http\Resources\Admin\Coupon\CouponResource;
use App\Services\Admin\Interfaces\CouponServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Coupon;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Coupons',
    description: 'Quản lý mã giảm giá'
)]
class CouponController extends Controller
{
    public function __construct(
        private readonly CouponServiceInterface $couponService
    ){}

    #[OA\Get(
        path: '/api/admin/coupons',
        summary: 'Lấy danh sách mã giảm giá (có phân trang & lọc)',
        tags: ['Coupons'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo mã code', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'type', in: 'query', description: 'Lọc theo loại giảm giá', required: false, schema: new OA\Schema(type: 'string', enum: ['percent', 'fixed'])),
            new OA\Parameter(name: 'is_active', in: 'query', description: 'Lọc theo trạng thái (1=active, 0=inactive)', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1])),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số bản ghi mỗi trang (mặc định: 10)', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lấy danh sách thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
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
    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->couponService->getList([
            'search'    => $request->query('search'),
            'type'      => $request->query('type'),
            'is_active' => $request->query('is_active'),
            'per_page'  => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => CouponResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Get(
        path: '/api/admin/coupons/all',
        summary: 'Lấy tất cả mã giảm giá (không phân trang)',
        tags: ['Coupons'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                ])
            ),
        ]
    )]
    public function parents() : JsonResponse
    {
        $parents = $this->couponService->getAll();

        return response()->json([
            'success' => true,
            'data'    => CouponResource::collection($parents),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/coupons',
        summary: 'Tạo mã giảm giá mới',
        tags: ['Coupons'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'type', 'discount_value', 'is_active'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', example: 'SUMMER20', description: 'Mã coupon, unique'),
                    new OA\Property(property: 'type', type: 'string', enum: ['percent', 'fixed'], example: 'percent', description: 'Loại giảm giá'),
                    new OA\Property(property: 'discount_value', type: 'number', example: 20, description: 'Giá trị giảm (% hoặc VNĐ)'),
                    new OA\Property(property: 'price_min_order_value', type: 'number', nullable: true, example: 500000, description: 'Đơn hàng tối thiểu để áp dụng'),
                    new OA\Property(property: 'max_usage', type: 'integer', nullable: true, example: 100, description: 'Số lần sử dụng tối đa'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'expiry_date', type: 'string', format: 'date', nullable: true, example: '2026-12-31', description: 'Ngày hết hạn (phải sau hôm nay)'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Mã giảm giá đã được thêm thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreCouponRequest $request)
    {
        $data = $request->validated();
        $data['created_by_staff_id'] = $request->user()->id;



        $coupon = $this->couponService->create($data);
        return response()->json([
            'success' => true,
            'data'    => new CouponResource($coupon),
            'message' => 'Mã giảm giá đã được thêm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/coupons/{coupon}',
        summary: 'Xem chi tiết một mã giảm giá',
        tags: ['Coupons'],
        parameters: [
            new OA\Parameter(name: 'coupon', in: 'path', description: 'ID của mã giảm giá', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy mã giảm giá'),
        ]
    )]
    public function show(Coupon $coupon)
    {
        return response()->json([
            'success' => true,
            'data'    => new CouponResource($coupon),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/coupons/{coupon}',
        summary: 'Cập nhật mã giảm giá',
        tags: ['Coupons'],
        parameters: [
            new OA\Parameter(name: 'coupon', in: 'path', description: 'ID của mã giảm giá cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'type', 'discount_value', 'is_active'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', example: 'SUMMER20', description: 'Mã coupon (unique, bỏ qua coupon hiện tại)'),
                    new OA\Property(property: 'type', type: 'string', enum: ['percent', 'fixed'], example: 'fixed'),
                    new OA\Property(property: 'discount_value', type: 'number', example: 50000),
                    new OA\Property(property: 'price_min_order_value', type: 'number', nullable: true, example: 300000),
                    new OA\Property(property: 'max_usage', type: 'integer', nullable: true, example: 50),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'expiry_date', type: 'string', format: 'date', nullable: true, example: '2026-12-31'),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Mã giảm giá đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();



        $updatedCoupon = $this->couponService->update($coupon, $data);

        return response()->json([
            'success' => true,
            'data'    => new CouponResource($updatedCoupon),
            'message' => 'Mã giảm giá đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/coupons/{coupon}',
        summary: 'Xóa mã giảm giá',
        tags: ['Coupons'],
        parameters: [
            new OA\Parameter(name: 'coupon', in: 'path', description: 'ID của mã giảm giá cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Mã giảm giá đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
        ]
    )]
    public function destroy(Coupon $coupon)
    {
        $this->couponService->delete($coupon);

        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá đã được xóa thành công.',
        ]);
    }
}