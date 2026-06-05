<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LandingController::class, 'index'])->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::resource('/products', App\Http\Controllers\Admin\ProductController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [App\Http\Controllers\Shop\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [App\Http\Controllers\Shop\CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [App\Http\Controllers\Shop\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [App\Http\Controllers\Shop\CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::get('/checkout', [App\Http\Controllers\Shop\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\Shop\CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
     Route::get('/orders', [App\Http\Controllers\Shop\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Shop\OrderController::class, 'show'])->name('orders.show');
});

// -- debugging --
Route::get('/cek-status', function () {
    if (Auth::check()) {
        return [
            'status' => 'SUDAH LOGIN',
            'name'   => Auth::user()->name,
            'email'  => Auth::user()->email,
            'role'   => Auth::user()->role,
        ];
    }
    return ['status' => 'BELUM LOGIN'];
});

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});
require __DIR__.'/auth.php';
