<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Lấy cây danh mục (Category Tree) cho bộ lọc sidebar và mega menu.
     * Sử dụng cache 1 giờ để tối ưu performance.
     */
    public function getTree(): JsonResponse
    {
        $roots = Cache::remember('categories_tree', 3600, function () {
            return Category::whereNull('parent_id')
                ->with(['children.children'])
                ->orderBy('name')
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => $roots
        ]);
    }
}
