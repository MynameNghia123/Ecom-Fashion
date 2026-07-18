<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\StoreBlogRequest;
use App\Http\Requests\Admin\Blog\UpdateBlogRequest;
use App\Http\Resources\Admin\Blog\BlogResource;
use App\Models\Blog;
use App\Services\Admin\Interfaces\BlogServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Blogs',
    description: 'Quản lý bài viết blog'
)]
class BlogController extends Controller
{
    public function __construct(
        private readonly BlogServiceInterface $blogService
    ) {}

    #[OA\Get(
        path: '/api/admin/blogs',
        summary: 'Lấy danh sách bài viết (có phân trang & lọc)',
        tags: ['Blogs'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tên bài viết', required: false, schema: new OA\Schema(type: 'string')),
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
                        new OA\Property(property: 'total', type: 'integer', example: 20),
                        new OA\Property(property: 'last_page', type: 'integer', example: 5),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->blogService->getList([
            'search'   => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 4),
        ]);

        return response()->json([
            'success' => true,
            'data'    => BlogResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/blogs',
        summary: 'Tạo bài viết mới',
        tags: ['Blogs'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Xu hướng thời trang Thu Đông 2026', description: 'Tên bài viết'),
                    new OA\Property(property: 'slug', type: 'string', example: 'xu-huong-thoi-trang-thu-dong-2026', description: 'Slug bài viết (duy nhất)'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Nội dung chi tiết bài viết...'),
                    new OA\Property(property: 'image', type: 'string', nullable: true, example: 'https://example.com/image.jpg'),
                    new OA\Property(property: 'status', type: 'boolean', example: true, description: 'Trạng thái bài viết (1=active, 0=draft)'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo bài viết thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Bài viết đã được tạo thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $blog = $this->blogService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new BlogResource($blog),
            'message' => 'Bài viết đã được tạo thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/blogs/{blog}',
        summary: 'Xem chi tiết một bài viết',
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
            'data'    => new BlogResource($blog),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/blogs/{blog}',
        summary: 'Cập nhật bài viết',
        tags: ['Blogs'],
        parameters: [
            new OA\Parameter(name: 'blog', in: 'path', description: 'ID của bài viết cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Xu hướng thời trang Thu Đông 2026 (Cập nhật)'),
                    new OA\Property(property: 'slug', type: 'string', example: 'xu-huong-thoi-trang-thu-dong-2026'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Nội dung cập nhật...'),
                    new OA\Property(property: 'image', type: 'string', nullable: true, example: 'https://example.com/image.jpg'),
                    new OA\Property(property: 'status', type: 'boolean', example: true),
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
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        $updatedBlog = $this->blogService->update($blog, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new BlogResource($updatedBlog),
            'message' => 'Bài viết đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/blogs/{blog}',
        summary: 'Xóa bài viết',
        tags: ['Blogs'],
        parameters: [
            new OA\Parameter(name: 'blog', in: 'path', description: 'ID của bài viết cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
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