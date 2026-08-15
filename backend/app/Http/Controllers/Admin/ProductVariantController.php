<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\Interfaces\ProductVariantServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductVariantController extends Controller
{
    public function __construct(
        private readonly ProductVariantServiceInterface $productVariantService
    ) {}

    #[OA\Get(
        path: '/api/admin/product-variants/search',
        summary: 'Tìm kiếm biến thể sản phẩm theo SKU, tên sản phẩm hoặc ID',
        tags: ['Admin - Product Variant'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: true, description: 'Từ khóa tìm kiếm (SKU, tên sản phẩm hoặc ID)', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Tìm kiếm thành công'),
            new OA\Response(response: 400, description: 'Lỗi khi thiếu tham số tìm kiếm'),
        ]
    )]
    public function search(Request $request)
    {
        $query = $request->input('search');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp từ khóa tìm kiếm.',
                'data' => [],
            ], 400);
        }

        $variants = $this->productVariantService->searchBySkuOrId($query);

        return response()->json([
            'success' => true,
            'data' => $variants,
            'message' => 'Tìm kiếm thành công.',
        ]);
    }
}
