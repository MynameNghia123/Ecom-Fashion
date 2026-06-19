<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Services\Admin\Interfaces\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService,

    ){}

    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->productService->getList([
            'search' => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);
        return response()->json([
            'success' => true,
            'data'    => ProductResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
    
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new ProductResource($product),
            'message' => 'Thêm sản phẩm thành công.',
        ], 201);
    }

    public function show(Product $product){
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updatedProduct = $this->productService->update($product, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new ProductResource($updatedProduct),
            'message' => 'Sản phẩm đã được cập nhật thành công.',
        ]);
    }

    public function destroy(Product $product){

        $this->productService->delete($product);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa thành công.',
        ], 200); 
    }

}

