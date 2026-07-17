<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Permission\PermissionResource;
use App\Services\Admin\Interfaces\PermissionServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Permissions',
    description: 'Quyền hạn — chỉ đọc, dùng để render form phân quyền cho Role'
)]
class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionServiceInterface $permissionService,
    ) {}

    #[OA\Get(
        path: '/api/admin/permissions',
        summary: 'Lấy toàn bộ quyền hạn, nhóm theo module',
        description: 'Trả về danh sách permissions nhóm theo module để FE render bảng phân quyền khi tạo/sửa Role.',
        tags: ['Permissions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            description: 'Object key = module, value = mảng các permission của module đó',
                            example: [
                                'products' => [
                                    ['id' => 1, 'module' => 'products', 'action' => 'read'],
                                    ['id' => 2, 'module' => 'products', 'action' => 'create'],
                                ],
                                'orders' => [
                                    ['id' => 5, 'module' => 'orders', 'action' => 'read'],
                                ],
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $grouped = $this->permissionService->getAllGroupedByModule();

        return response()->json([
            'success' => true,
            'data'    => $grouped,
        ]);
    }
}
