<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Product;
use App\Mail\LowStockMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class SendLowStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Collection $products) {}

        public function handle(): void
        {
            $admin = User::where('is_admin', true)->first();
            Mail::to($admin->email)->send(new LowStockMail($this->products));
        }
}

