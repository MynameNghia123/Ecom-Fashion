<?php
namespace App\Repositories\Client\Implements;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\Client\Interfaces\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(private readonly Cart $model, private readonly CartItem $itemModel) {}

    public function getActiveCartByCustomerId(int $customerId): Cart
    {
        return $this->model->firstOrCreate(
            ['customer_id' => $customerId],
            ['status' => 'active']
        );
    }

    public function findCartItem(int $cartId, int $itemId): ?CartItem
    {
        return $this->itemModel->where('id', $itemId)->where('cart_id', $cartId)->first();
    }

    public function findCartItemByVariant(int $cartId, int $variantId): ?CartItem
    {
        return $this->itemModel->where('cart_id', $cartId)->where('product_variant_id', $variantId)->first();
    }

    public function createCartItem(int $cartId, int $variantId, int $quantity): CartItem
    {
        return $this->itemModel->create([
            'cart_id'            => $cartId,
            'product_variant_id' => $variantId,
            'quantity'           => $quantity,
        ]);
    }

    public function updateCartItemQuantity(CartItem $item, int $quantity): bool
    {
        return $item->update(['quantity' => $quantity]);
    }

    public function deleteCartItem(CartItem $item): bool
    {
        return $item->delete();
    }

    public function loadCartRelations(Cart $cart): Cart
    {
        return $cart->load([
            'items.productVariant.product',
            'items.productVariant.attributeValues.attribute',
        ]);
    }
}
