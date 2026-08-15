<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleRequest;
use App\Http\Requests\Admin\Role\SyncPermissionsRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Models\Role;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Roles',
    description: 'Quản lý vai trò trong hệ thống RBAC'
)]
class RoleController extends Controller
{
    public function __construct(
        private readonly RoleServiceInterface $roleService
    ) {}

    // ── GET /api/admin/roles ──────────────────────────────────────────────────
    #[OA\Get(
        path: '/api/admin/roles',
        summary: 'Lấy danh sách vai trò (có phân trang & lọc)',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Thành công'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->roleService->getList([
            'search' => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data' => RoleResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    // ── GET /api/admin/roles/all ──────────────────────────────────────────────
    #[OA\Get(
        path: '/api/admin/roles/all',
        summary: 'Lấy toàn bộ vai trò (không phân trang, dùng cho dropdown)',
        tags: ['Roles'],
        responses: [new OA\Response(response: 200, description: 'Thành công')]
    )]
    public function all(): JsonResponse
    {
        $roles = $this->roleService->getAll();

        return response()->json([
            'success' => true,
            'data' => RoleResource::collection($roles),
        ]);
    }

    // ── POST /api/admin/roles ─────────────────────────────────────────────────
    #[OA\Post(
        path: '/api/admin/roles',
        summary: 'Tạo vai trò mới',
        tags: ['Roles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Manager'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'permission_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Tạo thành công'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(RoleRequest $request): JsonResponse
    {
        $role = $this->roleService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new RoleResource($role),
            'message' => 'Vai trò đã được tạo thành công.',
        ], 201);
    }

    // ── GET /api/admin/roles/{role} ───────────────────────────────────────────
    #[OA\Get(
        path: '/api/admin/roles/{role}',
        summary: 'Xem chi tiết vai trò (kèm danh sách quyền)',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Thành công'),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
        ]
    )]
    public function show(Role $role): JsonResponse
    {
        $role->load('permissions');

        return response()->json([
            'success' => true,
            'data' => new RoleResource($role),
        ]);
    }

    // ── PUT /api/admin/roles/{role} ───────────────────────────────────────────
    #[OA\Put(
        path: '/api/admin/roles/{role}',
        summary: 'Cập nhật vai trò',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'permission_ids', type: 'array', items: new OA\Items(type: 'integer')),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Cập nhật thành công'),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        $updatedRole = $this->roleService->update($role, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new RoleResource($updatedRole),
            'message' => 'Vai trò đã được cập nhật thành công.',
        ]);
    }

    // ── DELETE /api/admin/roles/{role} ────────────────────────────────────────
    #[OA\Delete(
        path: '/api/admin/roles/{role}',
        summary: 'Xóa vai trò',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Xóa thành công'),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
        ]
    )]
    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->delete($role);

        return response()->json([
            'success' => true,
            'message' => 'Vai trò đã được xóa thành công.',
        ]);
    }

    // ── POST /api/admin/roles/{role}/sync-permissions ─────────────────────────
    #[OA\Post(
        path: '/api/admin/roles/{role}/sync-permissions',
        summary: 'Đồng bộ danh sách quyền cho vai trò',
        description: 'Gán toàn bộ quyền mới vào vai trò, xóa các quyền cũ không có trong danh sách.',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['permission_ids'],
                properties: [
                    new OA\Property(property: 'permission_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 5]),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Đồng bộ thành công'),
            new OA\Response(response: 404, description: 'Không tìm thấy'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function syncPermissions(Role $role, SyncPermissionsRequest $request): JsonResponse
    {
        $updatedRole = $this->roleService->syncPermissions($role, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new RoleResource($updatedRole),
            'message' => 'Quyền hạn của vai trò đã được cập nhật thành công.',
        ]);
    }
}
