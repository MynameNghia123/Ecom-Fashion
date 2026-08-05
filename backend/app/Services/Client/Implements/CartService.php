<?php
namespace App\Services\Client\Implements;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Repositories\Client\Interfaces\CartRepositoryInterface;
use App\Services\Client\Interfaces\CartServiceInterface;

class CartService implements CartServiceInterface
{
    public function __construct(private readonly CartRepositoryInterface $repo) {}

    public function getCart(int $customerId): array
    {
        $cart = $this->repo->getActiveCartByCustomerId($customerId);
        $cart = $this->repo->loadCartRelations($cart);
        
        return $this->formatCart($cart);
    }

    public function addItem(int $customerId, int $variantId, int $quantity): array
    {
        $variant = ProductVariant::find($variantId);
        
        if (!$variant) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại.'];
        }

        if ($variant->stock_quantity < $quantity) {
            return ['success' => false, 'message' => 'Số lượng tồn kho không đủ.'];
        }

        $cart = $this->repo->getActiveCartByCustomerId($customerId);
        $item = $this->repo->findCartItemByVariant($cart->id, $variantId);

        if ($item) {
            $newQty = $item->quantity + $quantity;
            if ($newQty > $variant->stock_quantity) {
                return ['success' => false, 'message' => 'Vượt quá số lượng tồn kho.'];
            }
            $this->repo->updateCartItemQuantity($item, $newQty);
        } else {
            $this->repo->createCartItem($cart->id, $variantId, $quantity);
        }

        $cart = $this->repo->loadCartRelations($cart);

        return [
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng.',
            'data'    => $this->formatCart($cart),
        ];
    }

    public function updateItem(int $customerId, int $itemId, int $quantity): array
    {
        $cart = $this->repo->getActiveCartByCustomerId($customerId);
        $item = $this->repo->findCartItem($cart->id, $itemId);

        if (!$item) {
            return ['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.'];
        }

        $variant = $item->productVariant;
        if ($quantity > $variant->stock_quantity) {
            return ['success' => false, 'message' => 'Vượt quá số lượng tồn kho.'];
        }

        $this->repo->updateCartItemQuantity($item, $quantity);

        $cart = $this->repo->loadCartRelations($cart);

        return [
            'success' => true,
            'message' => 'Đã cập nhật số lượng.',
            'data'    => $this->formatCart($cart),
        ];
    }

    public function removeItem(int $customerId, int $itemId): array
    {
        $cart = $this->repo->getActiveCartByCustomerId($customerId);
        $item = $this->repo->findCartItem($cart->id, $itemId);

        if (!$item) {
            return ['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.'];
        }

        $this->repo->deleteCartItem($item);

        $cart = $this->repo->loadCartRelations($cart);

        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
            'data'    => $this->formatCart($cart),
        ];
    }

    private function formatCart(Cart $cart): array
    {
        $items = $cart->items->map(function (CartItem $item) {
            $variant = $item->productVariant;
            $product = $variant->product;

            $attributes = $variant->attributeValues->map(fn($av) => [
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
