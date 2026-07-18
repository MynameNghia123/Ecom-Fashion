<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Http\Resources\Admin\Banner\BannerResource;
use App\Models\Banner;
use App\Services\Admin\Interfaces\BannerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Banners',
    description: 'Quản lý banner quảng cáo'
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
            new OA\Parameter(name: 'search', in: 'query', description: 'Tìm kiếm theo tiêu đề banner', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'position', in: 'query', description: 'Lọc theo vị trí hiển thị', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'is_active', in: 'query', description: 'Lọc theo trạng thái (1=active, 0=inactive)', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1])),
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
                        new OA\Property(property: 'total', type: 'integer', example: 25),
                        new OA\Property(property: 'last_page', type: 'integer', example: 3),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->bannerService->getList([
            'search'    => $request->query('search'),
            'position'  => $request->query('position'),
            'is_active' => $request->query('is_active'),
            'per_page'  => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => BannerResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/banners',
        summary: 'Tạo banner mới',
        tags: ['Banners'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'image_url', 'position'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Banner Khuyến Mãi Hè 2026'),
                    new OA\Property(property: 'image_url', type: 'string', example: 'https://example.com/banner.jpg'),
                    new OA\Property(property: 'target_url', type: 'string', nullable: true, example: 'https://example.com/khuyen-mai'),
                    new OA\Property(property: 'position', type: 'string', example: 'home_slider'),
                    new OA\Property(property: 'display_order', type: 'integer', example: 1),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'start_date', type: 'string', format: 'date-time', nullable: true, example: '2026-06-01 00:00:00'),
                    new OA\Property(property: 'end_date', type: 'string', format: 'date-time', nullable: true, example: '2026-08-31 23:59:59'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo banner thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Banner đã được tạo thành công.'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreBannerRequest $request): JsonResponse
    {
        $banner = $this->bannerService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new BannerResource($banner),
            'message' => 'Banner đã được tạo thành công.',
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/banners/{banner}',
        summary: 'Xem chi tiết một banner',
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
            'data'    => new BannerResource($banner),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/banners/{banner}',
        summary: 'Cập nhật banner',
        tags: ['Banners'],
        parameters: [
            new OA\Parameter(name: 'banner', in: 'path', description: 'ID của banner cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'image_url', 'position'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Banner Khuyến Mãi Hè 2026 (Cập nhật)'),
                    new OA\Property(property: 'image_url', type: 'string', example: 'https://example.com/banner-updated.jpg'),
                    new OA\Property(property: 'target_url', type: 'string', nullable: true, example: 'https://example.com/khuyen-mai-moi'),
                    new OA\Property(property: 'position', type: 'string', example: 'home_slider'),
                    new OA\Property(property: 'display_order', type: 'integer', example: 2),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'start_date', type: 'string', format: 'date-time', nullable: true, example: '2026-06-01 00:00:00'),
                    new OA\Property(property: 'end_date', type: 'string', format: 'date-time', nullable: true, example: '2026-08-31 23:59:59'),
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
    public function update(UpdateBannerRequest $request, Banner $banner): JsonResponse
    {
        $updatedBanner = $this->bannerService->update($banner, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new BannerResource($updatedBanner),
            'message' => 'Banner đã được cập nhật thành công.',
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/banners/{banner}',
        summary: 'Xóa banner',
        tags: ['Banners'],
        parameters: [
            new OA\Parameter(name: 'banner', in: 'path', description: 'ID của banner cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
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
