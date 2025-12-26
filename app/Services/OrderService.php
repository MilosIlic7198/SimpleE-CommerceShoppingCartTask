<?php

namespace App\Services;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    protected $lowStockThreshold = 3;

    public function checkout(Collection $cartItems)
    {
        $lowStockProducts = [];

        DB::transaction(function () use ($cartItems, &$lowStockProducts) {
            foreach ($cartItems as $item) {
                $product = $item->product;

                if ($product->stock_quantity < $item->quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                $this->createOrder($item, $product);

                $product->decrement('stock_quantity', $item->quantity);
                $product->refresh();

                if ($product->stock_quantity < $this->lowStockThreshold) {
                    $lowStockProducts[] = $product;
                }
            }
        });

        return $lowStockProducts;
    }

    private function createOrder(CartItem $item, $product)
    {
        return Order::create([
            'user_id' => $item->user_id,
            'product_id' => $product->id,
            'quantity' => $item->quantity,
            'price_at_purchase' => $product->price,
            'total' => $item->quantity * $product->price,
        ]);
    }
}

