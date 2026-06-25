<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Role;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Roles',
    description: 'Quản lý vai trò trong hệ thống RBAC'
)]
class RoleController extends Controller
{
    public function __construct(
        private readonly RoleServiceInterface $roleService
    ){}

    #[OA\Get(
        path: '/api/admin/roles',
        summary: 'Lấy danh sách vai trò (có phân trang & lọc)',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tên vai trò', required: false, schema: new OA\Schema(type: 'string')),
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
                        new OA\Property(property: 'total', type: 'integer', example: 20),
                        new OA\Property(property: 'last_page', type: 'integer', example: 2),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->roleService->getList([
            'search'   => $request->query('search'),
            'status'   => $request->query('status'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => RoleResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/roles',
        summary: 'Tạo vai trò mới',
        tags: ['Roles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'permissions', 'status'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Manager', description: 'Tên vai trò'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản lý cửa hàng'),
                    new OA\Property(property: 'permissions', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3], description: 'Danh sách ID quyền hạn'),
                    new OA\Property(property: 'status', type: 'boolean', example: true),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Vai trò đã được thêm thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(RoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
            'message' => 'Vai trò đã được thêm thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/roles/{role}',
        summary: 'Xem chi tiết vai trò (kèm danh sách quyền)',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', description: 'ID của vai trò', required: true, schema: new OA\Schema(type: 'integer')),
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
            new OA\Response(response: 404, description: 'Không tìm thấy vai trò'),
        ]
    )]
    public function show(Role $role)
    {
        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/roles/{role}',
        summary: 'Cập nhật vai trò',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', description: 'ID của vai trò cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'permissions', 'status'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Senior Manager'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản lý cấp cao'),
                    new OA\Property(property: 'permissions', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3, 4]),
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
                    new OA\Property(property: 'message', type: 'string', example: 'Vai trò đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy vai trò'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(RoleRequest $request, Role $role)
    {
        $updatedRole = $this->roleService->update($role, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new RoleResource($updatedRole),
            'message' => 'Vai trò đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/roles/{role}',
        summary: 'Xóa vai trò',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', description: 'ID của vai trò cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Vai trò đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy vai trò'),
        ]
    )]
    public function destroy(Role $role)
    {
        $this->roleService->delete($role);

        return response()->json([
            'success' => true,
            'message' => 'Vai trò đã được xóa thành công.',
        ]);
    }

    #[OA\Post(
        path: '/api/admin/roles/{role}/sync-permissions',
        summary: 'Đồng bộ danh sách quyền cho vai trò',
        description: 'Gán toàn bộ quyền mới vào vai trò, xóa các quyền cũ không có trong danh sách.',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', description: 'ID của vai trò', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['permission_ids'],
                properties: [
                    new OA\Property(property: 'permission_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 5], description: 'Danh sách ID quyền muốn gán'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Đồng bộ quyền thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Vai trò đã được cập nhật quyền thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy vai trò'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function syncPermissions(Role $role, SyncPermissionsRequest $request)
    {
        $updatedRole = $this->roleService->syncPermissions($role, $request->validated());
        return response()->json([
            'success' => true,
            'data'    => new RoleResource($updatedRole),
            'message' => 'Vai trò đã được cập nhật quyền thành công.',
        ]);
    }

}
