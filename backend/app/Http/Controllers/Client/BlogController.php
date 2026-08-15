<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Blog\BlogResource;
use App\Services\Client\Interfaces\BlogServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(private readonly BlogServiceInterface $blogService) {}

    /**
     * Lấy danh sách bài viết Blog đang active (status = true).
     * Hỗ trợ tìm kiếm và phân trang.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search']);
        $perPage = (int) $request->query('per_page', 12);

        $paginator = $this->blogService->getActiveBlogs($filters, $perPage);

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

    /**
     * Lấy chi tiết một bài viết theo slug.
     */
    public function show(string $slug): JsonResponse
    {
        $blog = $this->blogService->findActiveBySlug($slug);

        if (! $blog) {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết không tồn tại hoặc đã bị ẩn.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new BlogResource($blog),
        ]);
    }
}
