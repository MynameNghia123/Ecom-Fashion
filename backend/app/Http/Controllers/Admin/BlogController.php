<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\BlogRequest;
use App\Http\Resources\Admin\Blog\BlogResource;
use App\Models\Blog;
use App\Services\Admin\Interfaces\BlogServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Blogs',
    description: 'Quản lý bài viết Blog'
)]
class BlogController extends Controller
{
    public function __construct(
        private readonly BlogServiceInterface $blogService
    ) {}

    #[OA\Get(
        path: '/api/admin/blogs',
        summary: 'Lấy danh sách blog (có phân trang & lọc)',
        tags: ['Blogs'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tên', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', description: 'Lọc theo trạng thái (active|inactive)', required: false, schema: new OA\Schema(type: 'string', enum: ['active', 'inactive'])),
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
                        new OA\Property(property: 'total', type: 'integer', example: 100),
                        new OA\Property(property: 'last_page', type: 'integer', example: 10),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->blogService->getList([
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data' => BlogResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/blogs',
        summary: 'Tạo bài viết Blog mới',
        tags: ['Blogs'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug', 'description', 'image', 'status'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Xu hướng thời trang 2025'),
                    new OA\Property(property: 'slug', type: 'string', example: 'xu-huong-thoi-trang-2025'),
                    new OA\Property(property: 'description', type: 'string', example: 'Nội dung bài viết...'),
                    new OA\Property(property: 'image', type: 'string', example: 'http://localhost/storage/images/blogs/abc.jpg'),
                    new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Bài viết đã được tạo thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(BlogRequest $request): JsonResponse
    {
        $data = $request->validated();

        $blog = $this->blogService->create($data);

        return response()->json([
            'success' => true,
            'data' => new BlogResource($blog),
            'message' => 'Bài viết đã được tạo thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/blogs/{blog}',
        summary: 'Xem chi tiết bài viết Blog',
        tags: ['Blogs'],
        parameters: [
            new OA\Parameter(name: 'blog', in: 'path', description: 'ID của bài viết', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy bài viết'),
        ]
    )]
    public function show(Blog $blog): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new BlogResource($blog),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/blogs/{blog}',
        summary: 'Cập nhật bài viết Blog',
        tags: ['Blogs'],
        parameters: [
            new OA\Parameter(name: 'blog', in: 'path', description: 'ID của bài viết', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug', 'description', 'image', 'status'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Xu hướng thời trang 2025 (cập nhật)'),
                    new OA\Property(property: 'slug', type: 'string', example: 'xu-huong-thoi-trang-2025-v2'),
                    new OA\Property(property: 'description', type: 'string', example: 'Nội dung bài viết cập nhật...'),
                    new OA\Property(property: 'image', type: 'string', example: 'http://localhost/storage/images/blogs/abc.jpg'),
                    new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Bài viết đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy bài viết'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(BlogRequest $request, Blog $blog): JsonResponse
    {
        $data = $request->validated();

        $updated = $this->blogService->update($blog, $data);

        return response()->json([
            'success' => true,
            'data' => new BlogResource($updated),
            'message' => 'Bài viết đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/blogs/{blog}',
        summary: 'Xóa bài viết Blog',
        tags: ['Blogs'],
        parameters: [
            new OA\Parameter(name: 'blog', in: 'path', description: 'ID của bài viết', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Bài viết đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy bài viết'),
        ]
    )]
    public function destroy(Blog $blog): JsonResponse
    {
        $this->blogService->delete($blog);

        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được xóa thành công.',
        ]);
    }
}
