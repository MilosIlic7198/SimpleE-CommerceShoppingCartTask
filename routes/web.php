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


    Route::get('/cart', [CartController::class, 'index'])->name('cartIndex');
    Route::post('/cart/store/{product}', [CartController::class, 'store'])->name('storeCart');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('updateCart');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('removeCart');
});

require __DIR__.'/settings.php';

