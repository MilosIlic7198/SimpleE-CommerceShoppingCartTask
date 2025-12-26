<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCartItemRequest;
use Inertia\Inertia;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return Inertia::render('cart/Index', [
            'cartItems' => $this->cartService->getUserCartItems(),
        ]);
    }

    public function store(Product $product)
    {
        $this->cartService->addProduct($product);

        return redirect()
            ->route('productsIndex')
            ->with('success', 'All good!');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        try {
            $this->cartService->updateQuantity(
                $cartItem,
                $request->validated()['quantity']
            );
        } catch (\Exception $e) {
        return redirect()
            ->route('cartIndex')
            ->with('error', $e->getMessage());
        }
    }

    public function destroy(CartItem $cartItem)
    {
        $this->cartService->remove($cartItem);
    }
}

