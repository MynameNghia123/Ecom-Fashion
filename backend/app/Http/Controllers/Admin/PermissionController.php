<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Staff\PermissionRequest;
use App\Http\Resources\Admin\Staff\PermissionResource;
use App\Services\Admin\Interfaces\PermissionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionServiceInterface $permissionService
    ){}

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
   public function getAll()
   {
        $permissions = $this->permissionService->getAll();
        return response()->json([
            'success' => true,
            'data'    => PermissionResource::collection($permissions),
        ]);
   }
}
