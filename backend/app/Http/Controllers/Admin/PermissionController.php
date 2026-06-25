<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Staff\PermissionRequest;
use App\Http\Resources\Admin\Staff\PermissionResource;
use App\Services\Admin\Interfaces\PermissionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Resources\Admin\Staff\StaffResource;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Permissions',
    description: 'Quản lý quyền hạn trong hệ thống RBAC'
)]
class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionServiceInterface $permissionService
    ){}

    #[OA\Get(
        path: '/api/admin/permissions',
        summary: 'Lấy danh sách quyền hạn (có phân trang & tìm kiếm)',
        tags: ['Permissions'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tên quyền', required: false, schema: new OA\Schema(type: 'string')),
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
                        new OA\Property(property: 'total', type: 'integer', example: 30),
                        new OA\Property(property: 'last_page', type: 'integer', example: 3),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->permissionService->getList([
            'search'   => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => StaffResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

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
                            new OA\Property(property: 'name', type: 'string', example: 'products.view'),
                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Xem danh sách sản phẩm'),
                        ]
                    )),
                ])
            ),
        ]
    )]
   public function getAll()
   {
        $permissions = $this->permissionService->getAll();
        return response()->json([
            'success' => true,
            'data'    => PermissionResource::collection($permissions),
        ]);
   }
}
