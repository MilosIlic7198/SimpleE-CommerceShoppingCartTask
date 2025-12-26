<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        // Ensure that the user hasn't already added the product to the cart
        $existingCartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            // If the product already exists in the cart, simply return a response or message
            return back()->with('info', 'Product is already in your cart');
        }

        // If not in cart, create a new cart item
        $cartItem = CartItem::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => 1,  // Add 1 quantity by default
        ]);

        return back()->with('success', 'Product was added in your cart');
    }


    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($cartItem->product->stock_quantity < $request->quantity) {
            return back()->withErrors(['stock' => 'Not enough stock']);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back();
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();

        return back();
    }

}

