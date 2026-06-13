<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Services\Admin\Interfaces\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService
    ){}

    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->categoryService->getList([
            'search'   => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => CategoryResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new CategoryResource($category),
            'message' => 'Danh mục đã được thêm thành công.',
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'data'    => new CategoryResource($category),
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->categoryService->update($category, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new CategoryResource($updatedCategory),
            'message' => 'Danh mục đã được cập nhật thành công.',
        ]);
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được xóa thành công.',
        ]);
    }
}