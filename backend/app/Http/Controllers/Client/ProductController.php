<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\Interfaces\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductServiceInterface $productService) {}

    /**
     * Lấy danh sách sản phẩm đang active (hỗ trợ lọc nâng cao)
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'category_id', 'category_slug', 'search', 'brand', 'min_price', 'max_price',
        ]);
        $sort = $request->query('sort', 'latest');
        $perPage = (int) $request->query('per_page', 12);

        $products = $this->productService->getActiveProducts($filters, $sort, $perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }

    /**
     * Lấy danh sách brands (unique) từ sản phẩm đang active
     */
    public function brands(): JsonResponse
    {
        $brands = $this->productService->getActiveBrands();

        return response()->json([
            'success' => true,
            'data' => $brands,
        ]);
    }

    /**
     * Lấy chi tiết sản phẩm
     */
    public function show($idOrSlug): JsonResponse
    {
        $product = $this->productService->getFormattedProductDetail($idOrSlug);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Lấy sản phẩm nổi bật (được đánh giá cao nhất)
     */
    public function topRated(Request $request): JsonResponse
    {
        $limit = (int) $request->query('per_page', 8);
        $limit = min($limit, 20); // tối đa 20 sản phẩm

        $products = $this->productService->getTopRated($limit);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * GET /client/products/{idOrSlug}/related — Lấy sản phẩm liên quan cùng category, loại trừ sản phẩm hiện tại.
     */
    public function related($idOrSlug, Request $request): JsonResponse
    {
        $product = $this->productService->findActiveByIdOrSlug($idOrSlug);

        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại.'], 404);
        }

        $limit = (int) $request->query('per_page', 4);
        $limit = min($limit, 12);

        $related = $this->productService->getRelatedProducts($product->id, $product->category_id, $limit);

        return response()->json([
            'success' => true,
            'data' => $related,
        ]);
    }
}
