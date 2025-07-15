<?php
/* filepath: c:\xampp\htdocs\custom-store\routes\web.php */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $products = \App\Models\Product::with(['images' => function($query) {
        $query->where('is_primary', true);
    }])->paginate(12);

    return view('home', compact('products'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Wishlist - tylko dla zalogowanych
Route::middleware(['auth'])->group(function () {
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/', [WishlistController::class, 'store'])->name('store');
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{productId}', [WishlistController::class, 'destroy'])->name('destroy');
        Route::get('/count', [WishlistController::class, 'count'])->name('count');
        Route::get('/check/{productId}', [WishlistController::class, 'check'])->name('check');
    });

    // Koszyk - tylko dla zalogowanych
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::put('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
        Route::get('/count', [CartController::class, 'count'])->name('count');
        Route::delete('/clear/all', [CartController::class, 'clear'])->name('clear');
    });

    // Checkout i zamówienia
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/order', [CheckoutController::class, 'processOrder'])->name('process-order');
        Route::get('/buy-now', [CheckoutController::class, 'buyNow'])->name('buy-now');
        Route::post('/buy-now', [CheckoutController::class, 'processBuyNow'])->name('process-buy-now');
        Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
        Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders');
    });
});

// Produkty - dostępne dla wszystkich
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/{id}/images', [ProductController::class, 'getImages'])->name('images');
    Route::post('/{id}/check-stock', [ProductController::class, 'checkStock'])->name('check-stock');
});

require __DIR__.'/auth.php';

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
