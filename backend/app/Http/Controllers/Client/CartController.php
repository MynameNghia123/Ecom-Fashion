<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * GET /client/cart — Lấy giỏ hàng của customer hiện tại.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();
        $cart = Cart::with([
            'items.productVariant.product',
            'items.productVariant.attributeValues.attribute',
        ])->firstOrCreate(
            ['customer_id' => $customer->id],
            ['status' => 'active']
        );

        return response()->json([
            'success' => true,
            'data'    => $this->formatCart($cart),
        ]);
    }

    /**
     * POST /client/cart/items — Thêm sản phẩm vào giỏ hàng.
     */
    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'quantity'           => 'required|integer|min:1',
        ]);

        $customer = Auth::user();
        $variant  = ProductVariant::findOrFail($request->product_variant_id);

        if ($variant->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng tồn kho không đủ.',
            ], 422);
        }

        $cart = Cart::firstOrCreate(
            ['customer_id' => $customer->id],
            ['status' => 'active']
        );

        $item = $cart->items()->where('product_variant_id', $variant->id)->first();

        if ($item) {
            $newQty = $item->quantity + $request->quantity;
            if ($newQty > $variant->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vượt quá số lượng tồn kho.',
                ], 422);
            }
            $item->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity'           => $request->quantity,
            ]);
        }

        $cart->load([
            'items.productVariant.product',
            'items.productVariant.attributeValues.attribute',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng.',
            'data'    => $this->formatCart($cart),
        ]);
    }

    /**
     * PUT /client/cart/items/{id} — Cập nhật số lượng sản phẩm.
     */
    public function updateItem(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = Auth::user();
        $cart     = Cart::where('customer_id', $customer->id)->firstOrFail();
        $item     = CartItem::where('id', $id)->where('cart_id', $cart->id)->firstOrFail();

        $variant = $item->productVariant;
        if ($request->quantity > $variant->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Vượt quá số lượng tồn kho.',
            ], 422);
        }

        $item->update(['quantity' => $request->quantity]);

        $cart->load([
            'items.productVariant.product',
            'items.productVariant.attributeValues.attribute',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng.',
            'data'    => $this->formatCart($cart),
        ]);
    }

    /**
     * DELETE /client/cart/items/{id} — Xóa sản phẩm khỏi giỏ hàng.
     */
    public function removeItem(int $id): JsonResponse
    {
        $customer = Auth::user();
        $cart     = Cart::where('customer_id', $customer->id)->firstOrFail();
        $item     = CartItem::where('id', $id)->where('cart_id', $cart->id)->firstOrFail();
        $item->delete();

        $cart->load([
            'items.productVariant.product',
            'items.productVariant.attributeValues.attribute',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
            'data'    => $this->formatCart($cart),
        ]);
    }

    // ── Private helper ────────────────────────────────────────────────

    private function formatCart(Cart $cart): array
    {
        $items = $cart->items->map(function (CartItem $item) {
            $variant = $item->productVariant;
            $product = $variant->product;

            $attributes = $item->productVariant->attributeValues->map(fn($av) => [
                'attribute' => $av->attribute?->name,
                'value'     => $av->value,
            ]);

            $price = $variant->sale_price ?? $variant->price;

            return [
                'id'                 => $item->id,
                'product_variant_id' => $variant->id,
                'quantity'           => $item->quantity,
                'price'              => $price,
                'subtotal'           => $price * $item->quantity,
                'product_name'       => $product?->name,
                'product_thumbnail'  => $product?->thumbnail,
                'sku'                => $variant->sku,
                'stock_quantity'     => $variant->stock_quantity,
                'attributes'         => $attributes,
            ];
        });

        $totalPrice = $items->sum('subtotal');

        return [
            'id'          => $cart->id,
            'items'       => $items,
            'total_items' => $items->count(),
            'total_price' => $totalPrice,
        ];
    }
}
