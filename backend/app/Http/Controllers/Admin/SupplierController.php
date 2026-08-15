<?php

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Supplier\StoreSupplierRequest;
use App\Http\Requests\Admin\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Admin\Supplier\SupplierResource;
use App\Models\Supplier;
use App\Services\Admin\Interfaces\SupplierServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Suppliers', description: 'API Quản lý nhà cung cấp')]
class SupplierController extends Controller
{
    public function __construct(
        private readonly SupplierServiceInterface $supplierService,
    ) {}

    #[OA\Get(
        path: '/api/admin/suppliers',
        operationId: 'getSuppliersList',
        summary: 'Lấy danh sách nhà cung cấp (có phân trang)',
        tags: ['Admin - Suppliers'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, description: 'Từ khóa tìm kiếm (tên, sđt, email)', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, description: 'Trạng thái (ví dụ: active, paused)', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, description: 'Số lượng item trên 1 trang (Mặc định: 4)', schema: new OA\Schema(type: 'integer', default: 4)),
            new OA\Parameter(name: 'page', in: 'query', required: false, description: 'Trang hiện tại', schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lấy danh sách thành công',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Công ty TNHH ABC'),
                                new OA\Property(property: 'phone', type: 'string', example: '0123456789'),
                                new OA\Property(property: 'email', type: 'string', example: 'contact@abc.com'),
                                new OA\Property(property: 'address', type: 'string', example: '123 Đường XYZ, TP HCM'),
                                new OA\Property(property: 'is_active', type: 'boolean', example: true),
                            ]
                        )),
                        new OA\Property(property: 'meta', type: 'object', properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'per_page', type: 'integer', example: 4),
                            new OA\Property(property: 'total', type: 'integer', example: 100),
                            new OA\Property(property: 'last_page', type: 'integer', example: 25),
                        ]),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->supplierService->getList([
            'search' => $request->query('search'),
            'is_active' => $request->query('is_active'),
            'per_page' => (int) $request->query('per_page', 4),
        ]);

        return response()->json([
            'success' => true,
            'data' => SupplierResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
            'stats' => $this->supplierService->getStats(),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/suppliers',
        operationId: 'storeSupplier',
        summary: 'Thêm mới nhà cung cấp',
        tags: ['Admin - Suppliers'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'phone', 'email', 'address', 'is_active'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Công ty TNHH ABC'),
                    new OA\Property(property: 'phone', type: 'string', example: '0123456789'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'contact@abc.com'),
                    new OA\Property(property: 'address', type: 'string', example: '123 Đường XYZ, TP HCM'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Thêm mới thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object', example: ['id' => 1, 'name' => 'Công ty TNHH ABC', 'email' => 'contact@abc.com']),
                    new OA\Property(property: 'message', type: 'string', example: 'Thêm nhà cung cấp mới thành công'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreSupplierRequest $request)
    {
        $supplier = $this->supplierService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new SupplierResource($supplier),
            'message' => 'Thêm nhà cung cấp mới thành công',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/suppliers/{supplier}',
        operationId: 'showSupplier',
        summary: 'Lấy thông tin chi tiết một nhà cung cấp',
        tags: ['Admin - Suppliers'],
        parameters: [
            new OA\Parameter(name: 'supplier', in: 'path', required: true, description: 'ID của nhà cung cấp', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object', example: ['id' => 1, 'name' => 'Công ty TNHH ABC', 'email' => 'contact@abc.com']),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy nhà cung cấp'),
        ]
    )]
    public function show(Supplier $supplier)
    {
        return response()->json([
            'success' => true,
            'data' => new SupplierResource($supplier),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/suppliers/{supplier}',
        operationId: 'updateSupplier',
        summary: 'Cập nhật nhà cung cấp',
        tags: ['Admin - Suppliers'],
        parameters: [
            new OA\Parameter(name: 'supplier', in: 'path', required: true, description: 'ID của nhà cung cấp', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'phone', 'email', 'address', 'is_active'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Công ty TNHH Cập Nhật'),
                    new OA\Property(property: 'phone', type: 'string', example: '0987654321'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'updated@abc.com'),
                    new OA\Property(property: 'address', type: 'string', example: '456 Đường Mới, TP HCM'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: false),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cập nhật thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object', example: ['id' => 1, 'name' => 'Công ty TNHH Cập Nhật', 'email' => 'updated@abc.com']),
                    new OA\Property(property: 'message', type: 'string', example: 'Cập nhập dữ liệu mới cho nhà cung cấp thành công'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
            new OA\Response(response: 404, description: 'Không tìm thấy nhà cung cấp'),
        ]
    )]
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $newSupplier = $this->supplierService->update($supplier, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new SupplierResource($newSupplier),
            'message' => 'Cập nhập dữ liệu mới cho nhà cung cấp thành công',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/suppliers/{supplier}',
        operationId: 'destroySupplier',
        summary: 'Xóa nhà cung cấp',
        tags: ['Admin - Suppliers'],
        parameters: [
            new OA\Parameter(name: 'supplier', in: 'path', required: true, description: 'ID của nhà cung cấp', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Xóa nhà cung cấp mới thành công'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy nhà cung cấp'),
        ]
    )]
    public function destroy(Supplier $supplier)
    {
        $this->supplierService->delete($supplier);

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhà cung cấp mưới thành công', // Giữ nguyên theo text của bạn
        ]);
    }

    #[OA\Get(
        path: '/api/admin/supplier/dropdown',
        operationId: 'getSupplierForDropDown',
        summary: 'Lấy danh sách nhà cung cấp cho dropdown',
        tags: ['Admin - Suppliers'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lấy danh sách nhà cung cấp cho dropdown thành công',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Công ty TNHH ABC'),
                                ]
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function getSupplierForDropDown()
    {
        $supplier = Supplier::select('id', 'name')->get();

        return response()->json([
            'success' => true,
            'data' => $supplier,
        ]);
    }
}
