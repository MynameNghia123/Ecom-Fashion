<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\Services\Admin\Interfaces\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Categories',
    description: 'Quản lý danh mục sản phẩm'
)]
class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService
    ) {}

    #[OA\Get(
        path: '/api/admin/categories',
        summary: 'Lấy danh sách danh mục (có phân trang & tìm kiếm)',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tên danh mục', required: false, schema: new OA\Schema(type: 'string')),
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
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->categoryService->getList([
            'search' => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
            'stats' => $this->categoryService->getStats(),
        ]);
    }

    #[OA\Get(
        path: '/api/admin/categories/parents',
        summary: 'Lấy tất cả danh mục cha (không phân trang, dùng cho dropdown)',
        tags: ['Categories'],
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
    public function parents(): JsonResponse
    {
        $parents = $this->categoryService->getAll();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($parents),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/categories',
        summary: 'Tạo danh mục mới',
        tags: ['Categories'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Áo khoác', description: 'Tên danh mục, unique'),
                    new OA\Property(property: 'slug', type: 'string', example: 'ao-khoac', description: 'Slug URL, unique'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Các loại áo khoác'),
                    new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: 1, description: 'ID danh mục cha'),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Danh mục đã được thêm thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
            'message' => 'Danh mục đã được thêm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/categories/{category}',
        summary: 'Xem chi tiết một danh mục',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', description: 'ID của danh mục', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy danh mục'),
        ]
    )]
    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/categories/{category}',
        summary: 'Cập nhật danh mục',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', description: 'ID của danh mục cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Áo khoác mùa đông'),
                    new OA\Property(property: 'slug', type: 'string', example: 'ao-khoac-mua-dong'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Cập nhật mô tả'),
                    new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: 1),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Danh mục đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy danh mục'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(CategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->categoryService->update($category, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($updatedCategory),
            'message' => 'Danh mục đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/categories/{category}',
        summary: 'Xóa danh mục',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', description: 'ID của danh mục cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Danh mục đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy danh mục'),
        ]
    )]
    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được xóa thành công.',
        ]);
    }
}
