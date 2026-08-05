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
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Products',
    description: 'Quản lý sản phẩm, bao gồm ảnh và biến thể'
)]
class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService,

    ){}

    #[OA\Get(
        path: '/api/admin/products',
        summary: 'Lấy danh sách sản phẩm (có phân trang & tìm kiếm)',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tên sản phẩm', required: false, schema: new OA\Schema(type: 'string')),
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
                        new OA\Property(property: 'total', type: 'integer', example: 200),
                        new OA\Property(property: 'last_page', type: 'integer', example: 20),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->productService->getList([
            'search'      => $request->query('search'),
            'category_id' => $request->query('category_id'),
            'is_active'   => $request->query('is_active'),
            'per_page'    => (int) $request->query('per_page', 10),
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
            'stats'   => $this->productService->getStats(),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/products',
        summary: 'Tạo sản phẩm mới (kèm ảnh và biến thể)',
        tags: ['Products'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['category_id', 'name', 'slug', 'variants'],
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 2, description: 'ID danh mục (phải tồn tại)'),
                    new OA\Property(property: 'name', type: 'string', example: 'Áo Thun Basic'),
                    new OA\Property(property: 'slug', type: 'string', example: 'ao-thun-basic', description: 'Unique'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Mô tả sản phẩm'),
                    new OA\Property(property: 'brand', type: 'string', nullable: true, example: 'Local Brand'),
                    new OA\Property(property: 'thumbnail', type: 'string', format: 'uri', nullable: true, example: 'http://localhost/storage/images/products/uuid.jpg', description: 'URL ảnh đại diện'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(
                        property: 'images',
                        type: 'array',
                        nullable: true,
                        description: 'Danh sách ảnh sản phẩm (URL đã upload trước)',
                        items: new OA\Items(properties: [
                            new OA\Property(property: 'image_url', type: 'string', format: 'uri', nullable: true, example: 'http://localhost/storage/images/products/uuid.jpg'),
                            new OA\Property(property: 'alt_text', type: 'string', nullable: true, example: 'Ảnh sản phẩm'),
                            new OA\Property(property: 'display_order', type: 'integer', nullable: true, example: 1),
                            new OA\Property(property: 'is_thumbnail', type: 'boolean', nullable: true, example: true),
                        ])
                    ),
                    new OA\Property(
                        property: 'variants',
                        type: 'array',
                        minItems: 1,
                        description: 'Danh sách biến thể (bắt buộc ít nhất 1)',
                        items: new OA\Items(
                            required: ['sku', 'price', 'stock_quantity'],
                            properties: [
                                new OA\Property(property: 'sku', type: 'string', example: 'SKU-AO-TRANG-L', description: 'Unique trong hệ thống'),
                                new OA\Property(property: 'price', type: 'number', example: 250000),
                                new OA\Property(property: 'sale_price', type: 'number', nullable: true, example: 199000, description: 'Phải ≤ price'),
                                new OA\Property(property: 'cost_price', type: 'number', nullable: true, example: 150000),
                                new OA\Property(property: 'stock_quantity', type: 'integer', example: 50),
                                new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                new OA\Property(property: 'thumbnail', type: 'string', format: 'uri', nullable: true, example: 'http://localhost/storage/images/variants/uuid.jpg'),
                                new OA\Property(
                                    property: 'attribute_values',
                                    type: 'array',
                                    nullable: true,
                                    items: new OA\Items(
                                        required: ['attribute_id', 'value'],
                                        properties: [
                                            new OA\Property(property: 'attribute_id', type: 'integer', example: 1),
                                            new OA\Property(property: 'value', type: 'string', example: 'Trắng'),
                                        ]
                                    )
                                ),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo sản phẩm thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Thêm sản phẩm thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new ProductResource($product),
            'message' => 'Thêm sản phẩm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/products/{product}',
        summary: 'Xem chi tiết sản phẩm (kèm ảnh và biến thể)',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', description: 'ID của sản phẩm', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy sản phẩm'),
        ]
    )]
    public function show(Product $product){
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ]);
    }

    #[OA\Put(
        path: '/api/admin/products/{product}',
        summary: 'Cập nhật sản phẩm (sync ảnh và biến thể)',
        description: 'Gửi toàn bộ danh sách images và variants. Server sẽ tự động thêm mới, cập nhật, và xóa những item không có trong danh sách. Gửi `id` để update item cũ, bỏ `id` để tạo mới.',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', description: 'ID của sản phẩm cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['category_id', 'name', 'slug', 'variants'],
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 2),
                    new OA\Property(property: 'name', type: 'string', example: 'Áo Thun Basic V2'),
                    new OA\Property(property: 'slug', type: 'string', example: 'ao-thun-basic-v2', description: 'Unique, bỏ qua sản phẩm hiện tại'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'brand', type: 'string', nullable: true, example: 'Local Brand'),
                    new OA\Property(property: 'thumbnail', type: 'string', format: 'uri', nullable: true),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(
                        property: 'images',
                        type: 'array',
                        nullable: true,
                        items: new OA\Items(properties: [
                            new OA\Property(property: 'id', type: 'integer', nullable: true, example: 10, description: 'ID ảnh cũ để update. Null = tạo mới.'),
                            new OA\Property(property: 'image_url', type: 'string', format: 'uri', nullable: true),
                            new OA\Property(property: 'alt_text', type: 'string', nullable: true),
                            new OA\Property(property: 'display_order', type: 'integer', nullable: true),
                            new OA\Property(property: 'is_thumbnail', type: 'boolean', nullable: true),
                        ])
                    ),
                    new OA\Property(
                        property: 'variants',
                        type: 'array',
                        minItems: 1,
                        items: new OA\Items(
                            required: ['sku', 'price', 'stock_quantity'],
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', nullable: true, example: 5, description: 'ID biến thể cũ để update. Null = tạo mới.'),
                                new OA\Property(property: 'sku', type: 'string', example: 'SKU-AO-TRANG-L'),
                                new OA\Property(property: 'price', type: 'number', example: 260000),
                                new OA\Property(property: 'sale_price', type: 'number', nullable: true, example: 209000),
                                new OA\Property(property: 'cost_price', type: 'number', nullable: true),
                                new OA\Property(property: 'stock_quantity', type: 'integer', example: 60),
                                new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                new OA\Property(property: 'thumbnail', type: 'string', format: 'uri', nullable: true),
                                new OA\Property(
                                    property: 'attribute_values',
                                    type: 'array',
                                    nullable: true,
                                    items: new OA\Items(properties: [
                                        new OA\Property(property: 'id', type: 'integer', nullable: true, example: 3, description: 'ID attribute_value cũ. Null = tạo mới.'),
                                        new OA\Property(property: 'attribute_id', type: 'integer', example: 1),
                                        new OA\Property(property: 'value', type: 'string', example: 'Đen'),
                                    ])
                                ),
                            ]
                        )
                    ),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Sản phẩm đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy sản phẩm'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updatedProduct = $this->productService->update($product, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new ProductResource($updatedProduct),
            'message' => 'Sản phẩm đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/products/{product}',
        summary: 'Xóa sản phẩm',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', description: 'ID của sản phẩm cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Sản phẩm đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy sản phẩm'),
        ]
    )]
    public function destroy(Product $product){

        $this->productService->delete($product);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa thành công.',
        ], 200); 
    }

}
