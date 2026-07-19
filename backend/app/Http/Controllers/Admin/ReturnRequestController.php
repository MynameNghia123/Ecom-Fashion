<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReturenRequest\StoreReturnRequest;
use App\Http\Requests\Admin\ReturenRequest\UpdateReturnRequest;
use App\Http\Resources\Admin\ReturnRequest\ReturnRequestResource;
use App\Models\ReturnRequest;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'ReturnRequests',
    description: 'Quản lý yêu cầu trả hàng & hoàn tiền'
)]
class ReturnRequestController extends Controller
{
    public function __construct(
        private readonly ReturnRequestServiceInterface $returnRequestService
    ) {}

    #[OA\Get(
        path: '/api/admin/return-requests',
        summary: 'Lấy danh sách yêu cầu trả hàng (có phân trang & lọc)',
        tags: ['ReturnRequests'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Tìm kiếm theo mã đơn hàng', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', description: 'Lọc theo trạng thái (pending, approved, rejected, completed)', required: false, schema: new OA\Schema(type: 'string', enum: ['pending', 'approved', 'rejected', 'completed'])),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số bản ghi mỗi trang (mặc định: 4)', required: false, schema: new OA\Schema(type: 'integer', default: 4)),
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
                        new OA\Property(property: 'per_page', type: 'integer', example: 4),
                        new OA\Property(property: 'total', type: 'integer', example: 12),
                        new OA\Property(property: 'last_page', type: 'integer', example: 3),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->returnRequestService->getList([
            'search'   => $request->query('search'),
            'status'   => $request->query('status'),
            'per_page' => (int) $request->query('per_page', 4),
        ]);

        return response()->json([
            'success' => true,
            'data'    => ReturnRequestResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/return-requests',
        summary: 'Tạo yêu cầu trả hàng mới',
        tags: ['ReturnRequests'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['order_id', 'reason'],
                properties: [
                    new OA\Property(property: 'order_id', type: 'integer', example: 1, description: 'ID đơn hàng'),
                    new OA\Property(property: 'reason', type: 'string', example: 'Sản phẩm không vừa size, bị lỗi chỉ may', description: 'Lý do trả hàng'),
                    new OA\Property(property: 'evidence_images', type: 'array', items: new OA\Items(type: 'string'), example: ['https://example.com/evidence1.jpg'], description: 'Ảnh minh chứng'),
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'approved', 'rejected', 'completed'], example: 'pending'),
                    new OA\Property(property: 'refund_amount', type: 'number', example: 350000, description: 'Số tiền đề xuất hoàn trả'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo yêu cầu trả hàng thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Yêu cầu trả hàng đã được gửi thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreReturnRequest $request): JsonResponse
    {
        $returnRequest = $this->returnRequestService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new ReturnRequestResource($returnRequest),
            'message' => 'Yêu cầu trả hàng đã được gửi thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/return-requests/{returnRequest}',
        summary: 'Xem chi tiết một yêu cầu trả hàng',
        tags: ['ReturnRequests'],
        parameters: [
            new OA\Parameter(name: 'returnRequest', in: 'path', description: 'ID của yêu cầu trả hàng', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy yêu cầu trả hàng'),
        ]
    )]
    public function show(ReturnRequest $returnRequest): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new ReturnRequestResource($returnRequest),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/return-requests/{returnRequest}',
        summary: 'Cập nhật/Duyệt yêu cầu trả hàng',
        tags: ['ReturnRequests'],
        parameters: [
            new OA\Parameter(name: 'returnRequest', in: 'path', description: 'ID yêu cầu trả hàng cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['status'],
                properties: [
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'approved', 'rejected', 'completed'], example: 'approved', description: 'Trạng thái mới'),
                    new OA\Property(property: 'refund_amount', type: 'number', example: 350000, description: 'Số tiền hoàn trả chính thức'),
                    new OA\Property(property: 'processed_by_staff_id', type: 'integer', example: 2, description: 'ID nhân viên xử lý'),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Yêu cầu trả hàng đã được cập nhật.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(UpdateReturnRequest $request, ReturnRequest $returnRequest): JsonResponse
    {
        $updated = $this->returnRequestService->update($returnRequest, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new ReturnRequestResource($updated),
            'message' => 'Yêu cầu trả hàng đã được cập nhật.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/return-requests/{returnRequest}',
        summary: 'Xóa yêu cầu trả hàng',
        tags: ['ReturnRequests'],
        parameters: [
            new OA\Parameter(name: 'returnRequest', in: 'path', description: 'ID của yêu cầu trả hàng cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Yêu cầu trả hàng đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
        ]
    )]
    public function destroy(ReturnRequest $returnRequest): JsonResponse
    {
        $this->returnRequestService->delete($returnRequest);

        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu trả hàng đã được xóa thành công.',
        ]);
    }
}
