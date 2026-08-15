<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerRequest;
use App\Http\Resources\Admin\Banner\BannerResource;
use App\Models\Banner;
use App\Services\Admin\Interfaces\BannerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Banners',
    description: 'Quản lý Banner quảng cáo'
)]
class BannerController extends Controller
{
    public function __construct(
        private readonly BannerServiceInterface $bannerService
    ) {}

    #[OA\Get(
        path: '/api/admin/banners',
        summary: 'Lấy danh sách banner (có phân trang & lọc)',
        tags: ['Banners'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm theo tiêu đề', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'position', in: 'query', description: 'Lọc theo vị trí (home_hero, home_middle,...)', required: false, schema: new OA\Schema(type: 'string')),
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
                        new OA\Property(property: 'total', type: 'integer', example: 20),
                        new OA\Property(property: 'last_page', type: 'integer', example: 2),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->bannerService->getList([
            'search' => $request->query('search'),
            'position' => $request->query('position'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data' => BannerResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/banners',
        summary: 'Tạo Banner mới',
        tags: ['Banners'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'image_url', 'position', 'is_active'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Sale mùa hè 2025'),
                    new OA\Property(property: 'image_url', type: 'string', example: 'http://localhost/storage/images/banners/summer.jpg'),
                    new OA\Property(property: 'target_url', type: 'string', nullable: true, example: '/products?sale=1'),
                    new OA\Property(property: 'position', type: 'string', example: 'home_hero'),
                    new OA\Property(property: 'display_order', type: 'integer', example: 1),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'start_date', type: 'string', format: 'date', nullable: true, example: '2025-06-01'),
                    new OA\Property(property: 'end_date', type: 'string', format: 'date', nullable: true, example: '2025-08-31'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Banner đã được tạo thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(BannerRequest $request): JsonResponse
    {
        $banner = $this->bannerService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new BannerResource($banner),
            'message' => 'Banner đã được tạo thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/banners/{banner}',
        summary: 'Xem chi tiết Banner',
        tags: ['Banners'],
        parameters: [
            new OA\Parameter(name: 'banner', in: 'path', description: 'ID của banner', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy banner'),
        ]
    )]
    public function show(Banner $banner): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new BannerResource($banner),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/banners/{banner}',
        summary: 'Cập nhật Banner',
        tags: ['Banners'],
        parameters: [
            new OA\Parameter(name: 'banner', in: 'path', description: 'ID của banner', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'image_url', 'position', 'is_active'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Sale mùa hè 2025 (cập nhật)'),
                    new OA\Property(property: 'image_url', type: 'string', example: 'http://localhost/storage/images/banners/summer_v2.jpg'),
                    new OA\Property(property: 'target_url', type: 'string', nullable: true, example: '/products?sale=1'),
                    new OA\Property(property: 'position', type: 'string', example: 'home_hero'),
                    new OA\Property(property: 'display_order', type: 'integer', example: 1),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'start_date', type: 'string', format: 'date', nullable: true, example: '2025-06-01'),
                    new OA\Property(property: 'end_date', type: 'string', format: 'date', nullable: true, example: '2025-08-31'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cập nhật thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Banner đã được cập nhật thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy banner'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(BannerRequest $request, Banner $banner): JsonResponse
    {
        $updated = $this->bannerService->update($banner, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new BannerResource($updated),
            'message' => 'Banner đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/banners/{banner}',
        summary: 'Xóa Banner',
        tags: ['Banners'],
        parameters: [
            new OA\Parameter(name: 'banner', in: 'path', description: 'ID của banner', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Banner đã được xóa thành công.'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy banner'),
        ]
    )]
    public function destroy(Banner $banner): JsonResponse
    {
        $this->bannerService->delete($banner);

        return response()->json([
            'success' => true,
            'message' => 'Banner đã được xóa thành công.',
        ]);
    }
}
