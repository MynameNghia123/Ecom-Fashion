<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Banner\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BannerController extends Controller
{
    /**
     * Lấy danh sách Banner đang active, trong khoảng thời gian hiệu lực.
     * Hỗ trợ lọc theo vị trí (position).
     */
    public function index(Request $request): JsonResponse
    {
        $now = Carbon::now();

        $query = Banner::where('is_active', true)
            ->where(function ($q) use ($now) {
                // start_date null hoặc đã qua
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                // end_date null hoặc chưa hết
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });

        if (!empty($request->query('position'))) {
            $query->where('position', $request->query('position'));
        }

        $banners = $query->orderBy('display_order', 'asc')->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'data'    => BannerResource::collection($banners),
        ]);
    }
}
