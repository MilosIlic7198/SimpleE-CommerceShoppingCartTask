<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutProductsRequest;
use App\Jobs\SendLowStockJob;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of products and user's cart items.
     */
    public function index()
    {
        $products = Product::all();
        $cartProductIds = $this->cartService->getUserCartItemsIds();

        return Inertia::render('products/Index', [
            'products' => $products,
            'cartProducts' => $cartProductIds,
        ]);
    }

    /**
     * Checkout the user's cart items.
     */
    public function checkout(CheckoutProductsRequest $request)
    {
        $cartItems = $this->cartService->getUserCartItems();
        if($cartItems->isEmpty()) {
            return redirect()->route('cartIndex')
                             ->with('error', 'Not good! Cart is empty!');
        }

        try {
            $lowStockProducts = $this->orderService->checkout($cartItems);
            $this->cartService->clearUserCart();
        } catch (\Exception $e) {
            return redirect()->route('cartIndex')
                             ->with('error', $e->getMessage());
        }

        // Dispatch low stock notifications after transaction
        if (!empty($lowStockProducts)) {
            SendLowStockJob::dispatch(collect($lowStockProducts));
        }

        return redirect()->route('cartIndex')
                         ->with('success', 'All good!');
    }
}

