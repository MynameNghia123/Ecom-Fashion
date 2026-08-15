<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerRequest;
use App\Http\Resources\Admin\Customer\CustomerResource;
use App\Models\Customer;
use App\Services\Admin\Interfaces\CustomerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Customers',
    description: 'Quản lý khách hàng'
)]
class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    #[OA\Get(
        path: '/api/admin/customers',
        summary: 'Lấy danh sách khách hàng (có phân trang & lọc)',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm (tên, email, số điện thoại)', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', description: 'Lọc theo trạng thái (1=active, 0=inactive)', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1])),
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
                        new OA\Property(property: 'total', type: 'integer', example: 100),
                        new OA\Property(property: 'last_page', type: 'integer', example: 10),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->customerService->getList([
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        $stats = $this->customerService->getStats();

        return response()->json([
            'success' => true,
            'data' => CustomerResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'total_active' => $stats['total_active'],
                'total_banned' => $stats['total_banned'],
                'new_today' => $stats['new_today'],
            ],
        ]);
    }

    #[OA\Get(
        path: '/api/admin/customers/all',
        summary: 'Lấy tất cả khách hàng (không phân trang)',
        tags: ['Customers'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                ])
            ),
        ]
    )]
    public function parents(): JsonResponse
    {
        $parents = $this->customerService->getAll();

        return response()->json([
            'success' => true,
            'data' => CustomerResource::collection($parents),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/customers',
        summary: 'Tạo khách hàng mới',
        tags: ['Customers'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'email', 'phone_number', 'password', 'status'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Văn A', description: 'Tên khách hàng'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Nguyễn', description: 'Họ khách hàng'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'customer@example.com', description: 'Email, unique'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '0901234567'),
                    new OA\Property(property: 'password', type: 'string', example: 'secret123', description: 'Bắt buộc khi tạo mới'),
                    new OA\Property(property: 'status', type: 'boolean', example: true, description: 'Trạng thái tài khoản'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Khách hàng đã được thêm thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(CustomerRequest $request)
    {
        $customer = $this->customerService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer),
            'message' => 'Khách hàng đã được thêm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/customers/{customer}',
        summary: 'Xem chi tiết khách hàng',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', description: 'ID của khách hàng', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy khách hàng'),
        ]
    )]
    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/customers/{customer}',
        summary: 'Cập nhật thông tin khách hàng',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', description: 'ID của khách hàng cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'email', 'phone_number', 'status'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Văn B'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Trần'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'new@example.com', description: 'Unique, bỏ qua customer hiện tại'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '0987654321'),
                    new OA\Property(property: 'password', type: 'string', nullable: true, example: null, description: 'Nullable khi update'),
                    new OA\Property(property: 'status', type: 'boolean', example: true),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Khách hàng đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy khách hàng'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(CustomerRequest $request, Customer $customer)
    {
        $updatedCustomer = $this->customerService->update($customer, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($updatedCustomer),
            'message' => 'Khách hàng đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/customers/{customer}',
        summary: 'Xóa khách hàng',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', description: 'ID của khách hàng cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Khách hàng đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy khách hàng'),
        ]
    )]
    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer);

        return response()->json([
            'success' => true,
            'message' => 'Khách hàng đã được xóa thành công.',
        ]);
    }
}
