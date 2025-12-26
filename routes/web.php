<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Group routes that need authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('productsIndex');
    Route::put('/products', [ProductController::class, 'checkout'])->name('checkoutProducts');

    Route::get('/cart', [CartController::class, 'index'])->name('cartIndex');

    Route::post('/cart/{product}', [CartController::class, 'store'])->name('storeCart');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('updateCart');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('destroyCart');
});

require __DIR__.'/settings.php';

