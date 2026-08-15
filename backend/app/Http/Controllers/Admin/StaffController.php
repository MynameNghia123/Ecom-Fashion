<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Staff\StaffRequest;
use App\Http\Resources\Admin\Staff\StaffResource;
use App\Models\Staff;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Staff',
    description: 'Quản lý nhân viên quản trị hệ thống'
)]
class StaffController extends Controller
{
    public function __construct(
        private readonly StaffServiceInterface $staffService
    ) {}

    #[OA\Get(
        path: '/api/admin/staffs',
        summary: 'Lấy danh sách nhân viên (có phân trang & lọc)',
        tags: ['Staff'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tên hoặc email nhân viên', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', description: 'Lọc theo trạng thái hoạt động (1=active, 0=inactive)', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1])),
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
                        new OA\Property(property: 'total', type: 'integer', example: 20),
                        new OA\Property(property: 'last_page', type: 'integer', example: 2),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->staffService->getList([
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data' => StaffResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/staffs',
        summary: 'Tạo nhân viên mới',
        tags: ['Staff'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['full_name', 'email', 'password', 'is_active'],
                properties: [
                    new OA\Property(property: 'full_name', type: 'string', example: 'Nguyễn Văn Admin', description: 'Họ và tên nhân viên'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@ecomfashion.vn', description: 'Email, unique'),
                    new OA\Property(property: 'phone_number', type: 'string', nullable: true, example: '0909123456', description: 'Tối đa 20 ký tự'),
                    new OA\Property(property: 'password', type: 'string', example: 'secret123', description: 'Bắt buộc khi tạo mới. Tối thiểu 6 ký tự.'),
                    new OA\Property(property: 'avatar', type: 'string', nullable: true, example: 'http://localhost/storage/images/avatars/uuid.jpg', description: 'URL ảnh đại diện nhân viên'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'role_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2], description: 'Danh sách ID vai trò'),
                    new OA\Property(property: 'permission_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 3], description: 'Danh sách ID quyền đặc cách'),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Nhân viên đã được thêm thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StaffRequest $request)
    {
        $staff = $this->staffService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new StaffResource($staff),
            'message' => 'Nhân viên đã được thêm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/staffs/{staff}',
        summary: 'Xem chi tiết nhân viên',
        tags: ['Staff'],
        parameters: [
            new OA\Parameter(name: 'staff', in: 'path', description: 'ID của nhân viên', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy nhân viên'),
        ]
    )]
    public function show(Staff $staff)
    {
        return response()->json([
            'success' => true,
            'data' => new StaffResource($staff),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/staffs/{staff}',
        summary: 'Cập nhật thông tin nhân viên',
        tags: ['Staff'],
        parameters: [
            new OA\Parameter(name: 'staff', in: 'path', description: 'ID của nhân viên cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['full_name', 'email', 'is_active'],
                properties: [
                    new OA\Property(property: 'full_name', type: 'string', example: 'Nguyễn Văn B'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'newmail@ecomfashion.vn', description: 'Unique, bỏ qua nhân viên hiện tại'),
                    new OA\Property(property: 'phone_number', type: 'string', nullable: true, example: '0912345678'),
                    new OA\Property(property: 'password', type: 'string', nullable: true, example: null, description: 'Nullable khi update'),
                    new OA\Property(property: 'avatar', type: 'string', nullable: true, example: 'http://localhost/storage/images/avatars/new-uuid.jpg'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'role_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2], description: 'Danh sách ID vai trò'),
                    new OA\Property(property: 'permission_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 3], description: 'Danh sách ID quyền đặc cách'),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Nhân viên đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy nhân viên'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(StaffRequest $request, Staff $staff)
    {
        $updatedStaff = $this->staffService->update($staff, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new StaffResource($updatedStaff),
            'message' => 'Nhân viên đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/staffs/{staff}',
        summary: 'Xóa nhân viên',
        tags: ['Staff'],
        parameters: [
            new OA\Parameter(name: 'staff', in: 'path', description: 'ID của nhân viên cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Nhân viên đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy nhân viên'),
        ]
    )]
    public function destroy(Staff $staff)
    {
        $this->staffService->delete($staff);

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được xóa thành công.',
        ]);
    }
}
