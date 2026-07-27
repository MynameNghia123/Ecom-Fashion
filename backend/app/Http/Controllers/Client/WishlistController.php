<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * GET /client/wishlist — Lấy danh sách yêu thích của khách hàng.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();

        $wishlists = Wishlist::with([
            'product.category',
            'product.productVariants.attributeValues.attribute',
        ])
        ->where('customer_id', $customer->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data'    => $wishlists,
        ]);
    }

    /**
     * POST /client/wishlist/toggle — Thêm hoặc xóa sản phẩm khỏi yêu thích.
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $customer = Auth::user();
        $productId = $request->product_id;

        $existing = Wishlist::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'action'  => 'removed',
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích',
            ]);
        }

        $wishlist = Wishlist::create([
            'customer_id' => $customer->id,
            'product_id'  => $productId,
            'created_at'  => now(),
        ]);

        return response()->json([
            'success' => true,
            'action'  => 'added',
            'message' => 'Đã thêm sản phẩm vào danh sách yêu thích',
            'data'    => $wishlist,
        ], 201);
    }

    /**
     * DELETE /client/wishlist/{productId} — Xóa sản phẩm khỏi yêu thích.
     */
    public function destroy($productId): JsonResponse
    {
        $customer = Auth::user();

        Wishlist::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích',
        ]);
    }
}
