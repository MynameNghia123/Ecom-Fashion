<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleServiceInterface $roleService
    ){}

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

    public function store(RoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
            'message' => 'Vai trò đã được thêm thành công.',
        ], 201);
    }

    public function show(Role $role)
    {
        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $updatedRole = $this->roleService->update($role, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new RoleResource($updatedRole),
            'message' => 'Vai trò đã được cập nhật thành công.',
        ]);
    }

    public function destroy(Role $role)
    {
        $this->roleService->delete($role);

        return response()->json([
            'success' => true,
            'message' => 'Vai trò đã được xóa thành công.',
        ]);
    }
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
