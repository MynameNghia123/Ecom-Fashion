<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Wishlist\ToggleWishlistRequest;
use App\Services\Client\Interfaces\WishlistServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct(
        private readonly WishlistServiceInterface $wishlistService
    ) {}

    /**
     * GET /client/wishlist — Lấy danh sách yêu thích của khách hàng.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();

        $wishlists = $this->wishlistService->getList($customer->id);

        return response()->json([
            'success' => true,
            'data' => $wishlists,
        ]);
    }

    /**
     * POST /client/wishlist/toggle — Thêm hoặc xóa sản phẩm khỏi yêu thích.
     */
    public function toggle(ToggleWishlistRequest $request): JsonResponse
    {
        $customer = Auth::user();
        $productId = $request->validated()['product_id'];

        $result = $this->wishlistService->toggle($customer->id, $productId);

        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['action'] === 'added' ? 201 : 200);
    }

    /**
     * DELETE /client/wishlist/{productId} — Xóa sản phẩm khỏi yêu thích.
     */
    public function destroy($productId): JsonResponse
    {
        $customer = Auth::user();

        $this->wishlistService->remove($customer->id, $productId);

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích',
        ]);
    }
}
