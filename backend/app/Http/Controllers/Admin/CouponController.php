<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Http\Resources\Admin\Coupon\CouponResource;
use App\Services\Admin\Interfaces\CouponServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function __construct(
        private readonly CouponServiceInterface $couponService
    ){}

    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->couponService->getList([
            'search'    => $request->query('search'),
            'type'      => $request->query('type'),
            'is_active' => $request->query('is_active'),
            'per_page'  => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => CouponResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    public function parents() : JsonResponse
    {
        $parents = $this->couponService->getAll();

        return response()->json([
            'success' => true,
            'data'    => CouponResource::collection($parents),
        ]);
    }

    public function store(StoreCouponRequest $request)
    {
        $coupon = $this->couponService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new CouponResource($coupon),
            'message' => 'Mã giảm giá đã được thêm thành công.',
        ], 201);
    }

    public function show(Coupon $coupon)
    {
        return response()->json([
            'success' => true,
            'data'    => new CouponResource($coupon),
        ]);
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $updatedCoupon = $this->couponService->update($coupon, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new CouponResource($updatedCoupon),
            'message' => 'Mã giảm giá đã được cập nhật thành công.',
        ]);
    }

    public function destroy(Coupon $coupon)
    {
        $this->couponService->delete($coupon);

        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá đã được xóa thành công.',
        ]);
    }
}