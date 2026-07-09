<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Blog\BlogResource;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Lấy danh sách bài viết Blog đang active (status = true).
     * Hỗ trợ tìm kiếm và phân trang.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::where('status', true);

        if (!empty($request->query('search'))) {
            $query->where('name', 'like', '%' . $request->query('search') . '%');
        }

        $perPage = (int) $request->query('per_page', 12);
        $paginator = $query->orderBy('id', 'desc')->paginate($perPage);

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

    /**
     * Lấy chi tiết một bài viết theo slug.
     */
    public function show(string $slug): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->where('status', true)->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết không tồn tại hoặc đã bị ẩn.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => new BlogResource($blog),
        ]);
    }
}
