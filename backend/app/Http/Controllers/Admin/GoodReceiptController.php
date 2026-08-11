<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GoodReceipt\StoreGoodReceiptRequest;
use App\Http\Requests\Admin\GoodReceipt\UpdateGoodReceiptRequest;
use App\Http\Requests\Admin\GoodReceipt\DeleteGoodReceiptRequest;
use App\Http\Resources\Admin\GoodReceipt\GoodReceiptResource;
use App\Models\GoodReceipt;
use App\Services\Admin\Interfaces\GoodReceiptServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class GoodReceiptController extends Controller
{
    public function __construct(
        private readonly GoodReceiptServiceInterface $service
    ) {}

    #[OA\Get(
        path: '/api/admin/good-receipts',
        summary: 'Lấy danh sách phiếu nhập hàng',
        tags: ['Admin - Good Receipt'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, description: 'Từ khóa tìm kiếm', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, description: 'Số lượng trên 1 trang', schema: new OA\Schema(type: 'integer', default: 4)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Danh sách phiếu nhập')
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->getList([
            'search'    => $request->query('search'),
            'per_page'  => $request->query('per_page', 4) 
        ]);

        return response()->json([
            'success' => true,
            'data'    => GoodReceiptResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
            'stats'   => $this->service->getStats(),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/good-receipts',
        summary: 'Tạo mới phiếu nhập hàng',
        tags: ['Admin - Good Receipt'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['receipt_code', 'supplier_id', 'total_amount_price', 'status'],
                properties: [
                    new OA\Property(property: 'receipt_code', type: 'string', example: 'GR20231001'),
                    new OA\Property(property: 'supplier_id', type: 'integer', example: 1),
                    new OA\Property(property: 'staff_id', type: 'integer', example: 1),
                    new OA\Property(property: 'total_amount_price', type: 'number', example: 100000),
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'approved', 'cancel', 'completed'], example: 'pending'),
                    new OA\Property(
                        property: 'good_receipt_details', 
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'product_variant_id', type: 'integer', example: 1),
                                new OA\Property(property: 'quantity', type: 'integer', example: 10),
                                new OA\Property(property: 'import_price', type: 'number', example: 50000),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Tạo mới thành công')
        ]
    )]
    public function store(StoreGoodReceiptRequest $request): JsonResponse
    {
       $data = $request->validated();
       if (!isset($data['staff_id'])) {
           $data['staff_id'] = $request->user()->id;
       }
       $created = $this->service->create($data);

       return response()->json([
            'success'   => true,
            'data'      => new GoodReceiptResource($created),
            'message'   => 'Tạo mới thành công phiếu nhập hàng'
       ], 201);
    }

    #[OA\Get(
        path: '/api/admin/good-receipts/{id}',
        summary: 'Lấy chi tiết phiếu nhập hàng',
        tags: ['Admin - Good Receipt'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID phiếu nhập', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Chi tiết phiếu nhập')
        ]
    )]
    public function show(GoodReceipt $goods_receipt): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'data'      => new GoodReceiptResource($goods_receipt),
        ], 200);
    }

    #[OA\Put(
        path: '/api/admin/good-receipts/{id}',
        summary: 'Cập nhật phiếu nhập hàng',
        tags: ['Admin - Good Receipt'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID phiếu nhập', schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'receipt_code', type: 'string', example: 'GR20231001'),
                    new OA\Property(property: 'supplier_id', type: 'integer', example: 1),
                    new OA\Property(property: 'staff_id', type: 'integer', example: 1),
                    new OA\Property(property: 'total_amount_price', type: 'number', example: 100000),
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'approved', 'cancel', 'completed'], example: 'pending'),
                    new OA\Property(
                        property: 'good_receipt_details', 
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'product_variant_id', type: 'integer', example: 1),
                                new OA\Property(property: 'quantity', type: 'integer', example: 10),
                                new OA\Property(property: 'import_price', type: 'number', example: 50000),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Cập nhật thành công')
        ]
    )]
    public function update(UpdateGoodReceiptRequest $request, GoodReceipt $goods_receipt): JsonResponse
    {
        $data = $request->validated();
        if (!isset($data['staff_id'])) {
            $data['staff_id'] = $request->user()->id;
        }
        $updated = $this->service->update($goods_receipt, $data);

        return response()->json([
            'success'   => true,
            'data'      => new GoodReceiptResource($updated),
            'message'   => 'Cập nhập thành công phiếu nhập hàng',
        ], 200);
    }

    #[OA\Delete(
        path: '/api/admin/good-receipts/{id}',
        summary: 'Xóa phiếu nhập hàng',
        tags: ['Admin - Good Receipt'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID phiếu nhập', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Xóa thành công')
        ]
    )]
    public function destroy(DeleteGoodReceiptRequest $request, GoodReceipt $goods_receipt): JsonResponse
    {
       $this->service->delete($goods_receipt);

       return response()->json([
            'success' => true,
            'message' => 'Xóa đơn nhập hàng thành công'
       ], 204);
    }
}