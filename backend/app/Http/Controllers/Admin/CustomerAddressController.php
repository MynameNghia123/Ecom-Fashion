<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerAddress\StoreCustomerAddressRequest;
use App\Http\Requests\Admin\CustomerAddress\UpdateCustomerAddressRequest;
use App\Http\Resources\Admin\CustomerAddress\CustomerAddressResource;
use App\Models\CustomerAddress;
use App\Services\Admin\Interfaces\CustomerAddressServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'CustomerAddresses',
    description: 'Quản lý địa chỉ khách hàng'
)]
class CustomerAddressController extends Controller
{
    public function __construct(
        private readonly CustomerAddressServiceInterface $customerAddressService
    ){}

    #[OA\Get(
        path: '/api/admin/customer-addresses',
        summary: 'Lấy danh sách địa chỉ khách hàng',
        tags: ['CustomerAddresses'],
        parameters: [
            new OA\Parameter(name: 'customer_id', in: 'query', description: 'Lọc theo ID khách hàng', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'search', in: 'query', description: 'Tìm kiếm theo tên người nhận hoặc số điện thoại', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số bản ghi mỗi trang', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lấy danh sách thành công'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->customerAddressService->getList([
            'customer_id' => $request->query('customer_id'),
            'search'      => $request->query('search'),
            'per_page'    => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => CustomerAddressResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/customer-addresses',
        summary: 'Thêm địa chỉ mới',
        tags: ['CustomerAddresses'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['customer_id', 'receiver_name', 'receiver_phone', 'detail_address'],
                properties: [
                    new OA\Property(property: 'customer_id', type: 'integer'),
                    new OA\Property(property: 'receiver_name', type: 'string'),
                    new OA\Property(property: 'receiver_phone', type: 'string'),
                    new OA\Property(property: 'province', type: 'string', nullable: true),
                    new OA\Property(property: 'district', type: 'string', nullable: true),
                    new OA\Property(property: 'ward', type: 'string', nullable: true),
                    new OA\Property(property: 'detail_address', type: 'string'),
                    new OA\Property(property: 'is_default', type: 'boolean', default: false),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Tạo thành công'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreCustomerAddressRequest $request): JsonResponse
    {
        $address = $this->customerAddressService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new CustomerAddressResource($address),
            'message' => 'Địa chỉ đã được thêm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/customer-addresses/{customer_address}',
        summary: 'Xem chi tiết địa chỉ',
        tags: ['CustomerAddresses'],
        parameters: [
            new OA\Parameter(name: 'customer_address', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Thành công'),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
        ]
    )]
    public function show(CustomerAddress $customerAddress): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new CustomerAddressResource($customerAddress),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/customer-addresses/{customer_address}',
        summary: 'Cập nhật địa chỉ',
        tags: ['CustomerAddresses'],
        parameters: [
            new OA\Parameter(name: 'customer_address', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['customer_id', 'receiver_name', 'receiver_phone', 'detail_address'],
                properties: [
                    new OA\Property(property: 'customer_id', type: 'integer'),
                    new OA\Property(property: 'receiver_name', type: 'string'),
                    new OA\Property(property: 'receiver_phone', type: 'string'),
                    new OA\Property(property: 'province', type: 'string', nullable: true),
                    new OA\Property(property: 'district', type: 'string', nullable: true),
                    new OA\Property(property: 'ward', type: 'string', nullable: true),
                    new OA\Property(property: 'detail_address', type: 'string'),
                    new OA\Property(property: 'is_default', type: 'boolean'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Cập nhật thành công'),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
            new OA\Response(response: 422, description: 'Lỗi validate'),
        ]
    )]
    public function update(UpdateCustomerAddressRequest $request, CustomerAddress $customerAddress): JsonResponse
    {
        $updatedAddress = $this->customerAddressService->update($customerAddress, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new CustomerAddressResource($updatedAddress),
            'message' => 'Địa chỉ đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/customer-addresses/{customer_address}',
        summary: 'Xóa địa chỉ',
        tags: ['CustomerAddresses'],
        parameters: [
            new OA\Parameter(name: 'customer_address', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Xóa thành công'),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
        ]
    )]
    public function destroy(CustomerAddress $customerAddress): JsonResponse
    {
        $this->customerAddressService->delete($customerAddress);

        return response()->json([
            'success' => true,
            'message' => 'Địa chỉ đã được xóa thành công.',
        ]);
    }
}
