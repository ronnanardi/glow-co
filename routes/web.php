<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Shop\AddressController;
use App\Http\Controllers\Admin\StockReportController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;


Route::get('/', [LandingController::class, 'index'])->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::resource('/products', App\Http\Controllers\Admin\ProductController::class)->except(['show']);
    Route::resource('/orders', AdminOrderController::class)->only(['index', 'show']);
    Route::post('/orders/{order}/confirm-payment', [AdminOrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/shipment', [AdminOrderController::class, 'addShipment'])->name('orders.add-shipment');
    Route::resource('/vouchers', VoucherController::class)->except(['show']);
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/stock-report', [StockReportController::class, 'index'])->name('stock-report.index');
    Route::patch('/stock-report/{product}', [StockReportController::class, 'updateStock'])->name('stock-report.update');
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');
    Route::get('/sales-report/export-excel', [SalesReportController::class, 'exportExcel'])->name('sales-report.export-excel');
    Route::get('/sales-report/export-pdf', [SalesReportController::class, 'exportPdf'])->name('sales-report.export-pdf');
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
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
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/ongkir', [CheckoutController::class, 'checkOngkir'])->name('checkout.ongkir');
    Route::post('/checkout/voucher', [CheckoutController::class, 'applyVoucher'])->name('checkout.voucher');

    // Orders
    Route::get('/orders', [App\Http\Controllers\Shop\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Shop\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/payment', [App\Http\Controllers\Shop\PaymentController::class, 'upload'])->name('orders.payment.upload');
    Route::post('/orders/{order}/complete', [App\Http\Controllers\Shop\OrderController::class, 'complete'])->name('orders.complete');

    // Alamat
    Route::resource('/addresses', AddressController::class)->except(['show']);

    // Destinasi
    Route::get('/destinations/search', [App\Http\Controllers\Shop\DestinationController::class, 'search'])->name('destinations.search');

    // Buy Now — langsung ke checkout dengan 1 produk
    Route::post('/buy-now', [App\Http\Controllers\Shop\CartController::class, 'buyNow'])->name('cart.buy-now');

    // Review
    Route::get('/reviews/{orderItem}/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{orderItem}', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

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
