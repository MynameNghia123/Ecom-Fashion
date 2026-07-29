<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lấy danh sách sản phẩm đang active (hỗ trợ lọc nâng cao)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'productImages', 'productVariants.attributeValues.attribute']);

        // Lọc theo category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo category_slug
        if ($request->filled('category_slug')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category_slug);
            });
        }

        // Tìm kiếm thông minh theo Tên, Thương hiệu, Danh mục hoặc SKU
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%")
                  ->orWhereHas('category', function ($cq) use ($s) {
                      $cq->where('name', 'like', "%{$s}%");
                  })
                  ->orWhereHas('productVariants', function ($vq) use ($s) {
                      $vq->where('sku', 'like', "%{$s}%");
                  });
            });
        }

        // Lọc theo brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Lọc theo khoảng giá (dựa vào sale_price hoặc price của variants)
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->filled('min_price') ? (float) $request->min_price : 0;
            $maxPrice = $request->filled('max_price') ? (float) $request->max_price : PHP_INT_MAX;

            $query->whereHas('productVariants', function ($q) use ($minPrice, $maxPrice) {
                $q->whereRaw('COALESCE(sale_price, price) >= ?', [$minPrice])
                  ->whereRaw('COALESCE(sale_price, price) <= ?', [$maxPrice]);
            });
        }

        // Sắp xếp
        $sort = $request->query('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                // Sắp xếp theo giá nhỏ nhất của variants tăng dần
                $query->orderByRaw('(
                    SELECT MIN(COALESCE(sale_price, price))
                    FROM product_variants
                    WHERE product_variants.product_id = products.id
                ) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('(
                    SELECT MIN(COALESCE(sale_price, price))
                    FROM product_variants
                    WHERE product_variants.product_id = products.id
                ) DESC');
                break;
            default: // 'latest'
                $query->latest();
                break;
        }

        $perPage = (int) $request->query('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $products->items(),
            'meta'    => [
                'current_page' => $products->currentPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
                'last_page'    => $products->lastPage(),
            ]
        ]);
    }

    /**
     * Lấy danh sách brands (unique) từ sản phẩm đang active
     */
    public function brands(): JsonResponse
    {
        $brands = Product::where('is_active', true)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'data'    => $brands
        ]);
    }

    /**
     * Lấy chi tiết sản phẩm
     */
    public function show($idOrSlug): JsonResponse
    {
        $product = Product::where('is_active', true)
            ->with(['category', 'productImages', 'productVariants.attributeValues.attribute'])
            ->where(function ($query) use ($idOrSlug) {
                $query->where('id', $idOrSlug)->orWhere('slug', $idOrSlug);
            })
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $product
        ]);
    }
}
