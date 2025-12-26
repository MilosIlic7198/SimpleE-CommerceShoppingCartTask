<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyOrdersMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get orders from the last minute... just for testing purposes!
        $oneMinuteAgo = Carbon::now()->subMinute();

        $orders = Order::query()
            ->where('created_at', '>=', $oneMinuteAgo)
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'product_name' => $first->product->name,
                    'quantity'      => $items->sum('quantity'),
                    'revenue'       => $items->sum('total'),
                ];
            });

        if ($orders->isEmpty()) {
            $body = "No orders from the last evening.\n\nReport generated at: " . now()->format('Y-m-d H:i:s');
        } else {
            $lines = $orders->map(function ($order) {
                return "{$order['product_name']}: {$order['quantity']} ordered (Revenue: \${$order['revenue']})";
            });

            $body = "Orders from the last evening:\n\n"
                . $lines->join("\n")
                . "\n\nReport generated at: " . now()->format('Y-m-d H:i:s');
        }

        $admin = User::where('is_admin', true)->first();
        Mail::raw($body, function ($message) use($admin) {
            $message->to($admin->email)
                    ->subject('Last Evening Orders Report - ' . now()->format('H:i:s'));
        });
    }
}

