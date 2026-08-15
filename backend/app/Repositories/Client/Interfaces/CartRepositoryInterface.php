<?php

namespace App\Repositories\Client\Interfaces;

use App\Models\Cart;
use App\Models\CartItem;

interface CartRepositoryInterface
{
    public function getActiveCartByCustomerId(int $customerId): Cart;

    public function findCartItem(int $cartId, int $itemId): ?CartItem;

    public function findCartItemByVariant(int $cartId, int $variantId): ?CartItem;

    public function createCartItem(int $cartId, int $variantId, int $quantity): CartItem;

    public function updateCartItemQuantity(CartItem $item, int $quantity): bool;

    public function deleteCartItem(CartItem $item): bool;

    public function loadCartRelations(Cart $cart): Cart;
}
