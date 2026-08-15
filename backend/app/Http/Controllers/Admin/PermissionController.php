<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Permission\PermissionResource;
use App\Services\Admin\Interfaces\PermissionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Permissions',
    description: 'Quản lý quyền hạn trong hệ thống RBAC'
)]
class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionServiceInterface $permissionService
    ) {}

    // ── GET /api/admin/permissions ────────────────────────────────────────────
    #[OA\Get(
        path: '/api/admin/permissions',
        summary: 'Lấy danh sách quyền hạn có phân trang',
        tags: ['Permissions'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Tìm theo module hoặc action'),
            new OA\Parameter(name: 'module', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Lọc theo module cụ thể'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 20)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Thành công'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->permissionService->getList([
            'search' => $request->query('search'),
            'module' => $request->query('module'),
            'per_page' => (int) $request->query('per_page', 20),
        ]);

        return response()->json([
            'success' => true,
            'data' => PermissionResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    // ── GET /api/admin/permissions/all ────────────────────────────────────────
    #[OA\Get(
        path: '/api/admin/permissions/all',
        summary: 'Lấy tất cả quyền hạn (không phân trang, dùng cho form gán quyền cho vai trò)',
        tags: ['Permissions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'module', type: 'string', example: 'products'),
                            new OA\Property(property: 'action', type: 'string', example: 'view'),
                            new OA\Property(property: 'label', type: 'string', example: 'products.view'),
                        ]
                    )),
                ])
            ),
        ]
    )]
    public function getAll(): JsonResponse
    {
        $permissions = $this->permissionService->getAll();

        return response()->json([
            'success' => true,
            'data' => PermissionResource::collection($permissions),
        ]);
    }
}
