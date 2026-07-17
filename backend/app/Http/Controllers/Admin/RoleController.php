<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Models\Role;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Roles',
    description: 'Quản lý vai trò trong hệ thống'
)]
class RoleController extends Controller
{
    public function __construct(
        private readonly RoleServiceInterface $roleService,
    ) {}

    #[OA\Get(
        path: '/api/admin/roles',
        summary: 'Lấy danh sách vai trò (có phân trang & lọc)',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Tìm kiếm theo tên vai trò', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số bản ghi mỗi trang (mặc định: 15)', required: false, schema: new OA\Schema(type: 'integer', default: 15)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lấy danh sách thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(
                        property: 'data', 
                        type: 'array', 
                        items: new OA\Items(
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Admin'),
                                new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản trị viên'),
                                new OA\Property(
                                    property: 'permissions',
                                    type: 'array',
                                    nullable: true,
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'module', type: 'string', example: 'products'),
                                            new OA\Property(property: 'action', type: 'string', example: 'read'),
                                        ]
                                    )
                                ),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ]
                        )
                    ),
                    new OA\Property(property: 'meta', type: 'object', properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(property: 'per_page', type: 'integer', example: 15),
                        new OA\Property(property: 'total', type: 'integer', example: 10),
                        new OA\Property(property: 'last_page', type: 'integer', example: 1),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->roleService->getList([
            'search'   => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 15),
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

    #[OA\Get(
        path: '/api/admin/roles/all',
        summary: 'Lấy tất cả danh sách vai trò (không phân trang)',
        tags: ['Roles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
            )
        ]
    )]
    public function getAll(): JsonResponse
    {
        $roles = $this->roleService->getAll();
        return response()->json([
            'success' => true,
            'data'    => $roles,
        ]);
    }

    #[OA\Post(
        path: '/api/admin/roles',
        summary: 'Tạo vai trò mới',
        tags: ['Roles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Manager', description: 'Tên vai trò, duy nhất'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản lý cấp trung'),
                    new OA\Property(property: 'permission_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 2, 5]),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(
                        property: 'data', 
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 2),
                            new OA\Property(property: 'name', type: 'string', example: 'Manager'),
                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản lý cấp trung'),
                            new OA\Property(
                                property: 'permissions',
                                type: 'array',
                                nullable: true,
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'module', type: 'string', example: 'products'),
                                        new OA\Property(property: 'action', type: 'string', example: 'read'),
                                    ]
                                )
                            ),
                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                        ]
                    ),
                    new OA\Property(property: 'message', type: 'string', example: 'Thêm vai trò thành công'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
            'message' => 'Thêm vai trò thành công',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/roles/{role}',
        summary: 'Xem chi tiết vai trò (kèm danh sách permissions)',
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
                    new OA\Property(
                        property: 'data', 
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 2),
                            new OA\Property(property: 'name', type: 'string', example: 'Manager'),
                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản lý cấp trung'),
                            new OA\Property(
                                property: 'permissions',
                                type: 'array',
                                nullable: true,
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'module', type: 'string', example: 'products'),
                                        new OA\Property(property: 'action', type: 'string', example: 'read'),
                                    ]
                                )
                            ),
                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                        ]
                    ),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy vai trò'),
        ]
    )]
    public function show(Role $role): JsonResponse
    {
        $role->load('rolePermissions.permission');

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
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Manager'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản lý cấp trung'),
                    new OA\Property(property: 'permission_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 3, 7]),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cập nhật thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(
                        property: 'data', 
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 2),
                            new OA\Property(property: 'name', type: 'string', example: 'Manager'),
                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Quản lý cấp trung'),
                            new OA\Property(
                                property: 'permissions',
                                type: 'array',
                                nullable: true,
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'module', type: 'string', example: 'products'),
                                        new OA\Property(property: 'action', type: 'string', example: 'read'),
                                    ]
                                )
                            ),
                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                        ]
                    ),
                    new OA\Property(property: 'message', type: 'string', example: 'Cập nhật vai trò thành công'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy vai trò'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role = $this->roleService->update($role, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
            'message' => 'Cập nhật vai trò thành công',
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
                    new OA\Property(property: 'message', type: 'string', example: 'Xóa vai trò thành công'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy vai trò'),
        ]
    )]
    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->delete($role);

        return response()->json([
            'success' => true,
            'message' => 'Xóa vai trò thành công',
        ]);
    }
}
