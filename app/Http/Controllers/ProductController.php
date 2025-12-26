<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutProductsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Jobs\SendLowStockNotification;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        // Fetch all products
        $products = Product::all();

        // Fetch the product IDs that the user already has in their cart
        $cartProducts = Auth::user()->cartItems()->pluck('product_id')->toArray();

        // Pass products and cart product IDs to the frontend
        return Inertia::render('products/Index', [
            'products' => $products,
            'cartProducts' => $cartProducts,  // Send only the product IDs
        ]);
    }

    public function checkout(CheckoutProductsRequest $request)
    {
        $products = $request->validated()['products'];

        $user = Auth::user();

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return;
        }

        // Threshold for low stock
        $lowStockThreshold = 3;
        $lowStockProducts = [];

        DB::transaction(function () use ($cartItems, $lowStockThreshold, &$lowStockProducts) {
            foreach ($cartItems as $item) {
                $product = $item->product;

                if ($product->stock_quantity < $item->quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                $product->decrement('stock_quantity', $item->quantity);

                // Reload product to get updated stock
                $product->refresh();

                // Check if stock is below threshold
                if ($product->stock_quantity < $lowStockThreshold) {
                    $lowStockProducts[] = $product;
                }
            }

            CartItem::where('user_id', auth()->id())->delete();
        });

        // Dispatch email job after transaction
        SendLowStockNotification::dispatch(collect($lowStockProducts));
    }

}

