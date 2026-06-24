<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Staff\StaffRequest;
use App\Http\Resources\Admin\Staff\StaffResource;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Staff;

class StaffController extends Controller
{
    public function __construct(
        private readonly StaffServiceInterface $staffService
    ){}

    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->staffService->getList([
            'search'   => $request->query('search'),
            'status'   => $request->query('status'),
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

    public function store(StaffRequest $request)
    {
        $staff = $this->staffService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new StaffResource($staff),
            'message' => 'Nhân viên đã được thêm thành công.',
        ], 201);
    }

    public function show(Staff $staff)
    {
        return response()->json([
            'success' => true,
            'data'    => new StaffResource($staff),
        ]);
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $updatedStaff = $this->staffService->update($staff, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new StaffResource($updatedStaff),
            'message' => 'Nhân viên đã được cập nhật thành công.',
        ]);
    }

    public function destroy(Staff $staff)
    {
        $this->staffService->delete($staff);

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được xóa thành công.',
        ]);
    }
}
