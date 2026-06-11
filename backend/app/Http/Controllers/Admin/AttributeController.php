<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;
use App\Http\Resources\Admin\AttributeResource;
use App\Models\Attribute;
use App\Services\Admin\Interfaces\AttributeServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{

    public function __construct(
        private readonly AttributeServiceInterface $attributeService
    ) {}

    // ─────────────────────────────────────────────────────────────────────────
    // GET /api/admin/attributes?search=&page=&per_page=
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->attributeService->getList([
            'search'   => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => AttributeResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /api/admin/attributes
    // ─────────────────────────────────────────────────────────────────────────
    public function store(AttributeRequest $request): JsonResponse
    {
        $attribute = $this->attributeService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new AttributeResource($attribute),
            'message' => 'Thuộc tính đã được thêm thành công.',
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /api/admin/attributes/{attribute}
    // ─────────────────────────────────────────────────────────────────────────
    public function show(Attribute $attribute): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new AttributeResource($attribute),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PUT /api/admin/attributes/{attribute}
    // ─────────────────────────────────────────────────────────────────────────
    public function update(AttributeRequest $request, Attribute $attribute): JsonResponse
    {
        $updated = $this->attributeService->update($attribute, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new AttributeResource($updated),
            'message' => 'Thuộc tính đã được cập nhật thành công.',
        ]);
    }
    // ─────────────────────────────────────────────────────────────────────────
    // DELETE /api/admin/attributes/{attribute}
    // ─────────────────────────────────────────────────────────────────────────
    public function destroy(Attribute $attribute): JsonResponse
    {
        $this->attributeService->delete($attribute);

        return response()->json([
            'success' => true,
            'message' => 'Thuộc tính đã được xóa thành công.',
        ]);
    }
}
