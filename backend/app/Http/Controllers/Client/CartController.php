<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Cart\AddItemRequest;
use App\Http\Requests\Client\Cart\UpdateItemRequest;
use App\Http\Requests\Client\Cart\SyncCartRequest;
use App\Services\Client\Interfaces\CartServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(private readonly CartServiceInterface $cartService) {}

    /**
     * GET /client/cart — Lấy giỏ hàng của customer hiện tại.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();
        
        return response()->json([
            'success' => true,
            'data'    => $this->cartService->getCart($customer->id),
        ]);
    }

    /**
     * POST /client/cart/sync — Đồng bộ giỏ hàng từ LocalStorage.
     */
    public function syncCart(SyncCartRequest $request): JsonResponse
    {
        $customer = Auth::user();
        $validated = $request->validated();
        
        // Items may be empty if LocalStorage was empty
        $items = $validated['items'] ?? [];

        $result = $this->cartService->syncCart($customer->id, $items);

        return response()->json($result);
    }

    /**
     * POST /client/cart/items — Thêm sản phẩm vào giỏ hàng.
     */
    public function addItem(AddItemRequest $request): JsonResponse
    {
        $customer = Auth::user();
        $validated = $request->validated();

        $result = $this->cartService->addItem($customer->id, $validated['product_variant_id'], $validated['quantity']);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json($result);
    }

    /**
     * PUT /client/cart/items/{id} — Cập nhật số lượng sản phẩm.
     */
    public function updateItem(UpdateItemRequest $request, int $id): JsonResponse
    {
        $customer = Auth::user();
        $validated = $request->validated();

        $result = $this->cartService->updateItem($customer->id, $id, $validated['quantity']);

        if (!$result['success']) {
            $status = str_contains($result['message'], 'Không tìm thấy') ? 404 : 422;
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $status);
        }

        return response()->json($result);
    }

    /**
     * DELETE /client/cart/items/{id} — Xóa sản phẩm khỏi giỏ hàng.
     */
    public function removeItem(int $id): JsonResponse
    {
        $customer = Auth::user();

        $result = $this->cartService->removeItem($customer->id, $id);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 404);
        }

        return response()->json($result);
    }
}
