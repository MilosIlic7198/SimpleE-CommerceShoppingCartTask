<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getUserCartItems()
    {
        return CartItem::with('product')->where('user_id', Auth::id())->get();
    }

    public function getUserCartItemsIds()
    {
        return CartItem::where('user_id', Auth::id())->pluck('product_id');
    }

    public function clearUserCart()
    {
        CartItem::where('user_id', Auth::id())->delete();
    }

    public function addProduct(Product $product)
    {
        $exists = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return;
        }

        CartItem::create([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);
    }

    public function updateQuantity(CartItem $cartItem, int $quantity)
    {
        if ($cartItem->product->stock_quantity < $quantity) {
            throw new \Exception('Not enough stock available.');
        }

        $cartItem->update([
            'quantity' => $quantity,
        ]);
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
    }
}

