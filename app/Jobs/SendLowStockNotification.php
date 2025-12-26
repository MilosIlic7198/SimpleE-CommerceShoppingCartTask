<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class SendLowStockNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Collection $products) {}

        public function handle(): void
        {
            $admin = User::where('is_admin', true)->first();

            $lines = collect($this->products)->map(function ($product) {
                return "{$product->name} — {$product->stock_quantity} left";
            })->implode("\n");

            Mail::raw(
                "The following products are low on stock:\n\n{$lines}",
                function ($message) use ($admin) {
                    $message
                        ->to($admin->email)
                        ->subject('Low Stock Alert');
                }
            );
        }

}

