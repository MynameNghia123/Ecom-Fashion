<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Banner\BannerResource;
use App\Services\Client\Interfaces\BannerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(private readonly BannerServiceInterface $bannerService) {}

    /**
     * Lấy danh sách Banner đang active, trong khoảng thời gian hiệu lực.
     * Hỗ trợ lọc theo vị trí (position).
     */
    public function index(Request $request): JsonResponse
    {
        $banners = $this->bannerService->getActiveBanners($request->query('position'));

        return response()->json([
            'success' => true,
            'data'    => BannerResource::collection($banners),
        ]);
    }
}
