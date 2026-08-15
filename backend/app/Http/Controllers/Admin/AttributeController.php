<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;
use App\Http\Resources\Admin\AttributeResource;
use App\Models\Attribute;
use App\Services\Admin\Interfaces\AttributeServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Attributes',
    description: 'Quản lý các thuộc tính sản phẩm (VD: Màu sắc, Kích thước...)'
)]
class AttributeController extends Controller
{
    public function __construct(
        private readonly AttributeServiceInterface $attributeService
    ) {}

    #[OA\Get(
        path: '/api/admin/attributes',
        summary: 'Lấy danh sách thuộc tính (có phân trang & tìm kiếm)',
        tags: ['Attributes'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm (theo tên thuộc tính)', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số lượng bản ghi trên mỗi trang (Mặc định: 10)', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
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
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->attributeService->getList([
            'search' => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data' => AttributeResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/attributes',
        summary: 'Thêm mới một thuộc tính',
        tags: ['Attributes'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Màu sắc', description: 'Tên thuộc tính'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Thêm thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Thuộc tính đã được thêm thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(AttributeRequest $request): JsonResponse
    {
        $attribute = $this->attributeService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new AttributeResource($attribute),
            'message' => 'Thuộc tính đã được thêm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/attributes/{attribute}',
        summary: 'Xem chi tiết một thuộc tính',
        tags: ['Attributes'],
        parameters: [
            new OA\Parameter(name: 'attribute', in: 'path', description: 'ID của thuộc tính', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy thuộc tính'),
        ]
    )]
    public function show(Attribute $attribute): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AttributeResource($attribute),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/attributes/{attribute}',
        summary: 'Cập nhật một thuộc tính',
        tags: ['Attributes'],
        parameters: [
            new OA\Parameter(name: 'attribute', in: 'path', description: 'ID của thuộc tính cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Kích thước', description: 'Tên thuộc tính mới'),
            ])
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cập nhật thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Thuộc tính đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(AttributeRequest $request, Attribute $attribute): JsonResponse
    {
        $updated = $this->attributeService->update($attribute, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new AttributeResource($updated),
            'message' => 'Thuộc tính đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/attributes/{attribute}',
        summary: 'Xóa một thuộc tính',
        tags: ['Attributes'],
        parameters: [
            new OA\Parameter(name: 'attribute', in: 'path', description: 'ID của thuộc tính cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Thuộc tính đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
        ]
    )]
    public function destroy(Attribute $attribute): JsonResponse
    {
        $this->attributeService->delete($attribute);

        return response()->json([
            'success' => true,
            'message' => 'Thuộc tính đã được xóa thành công.',
        ]);
    }
}
