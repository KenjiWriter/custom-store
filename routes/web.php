<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Strona główna
Route::get('/', [ProductController::class, 'home'])->name('home');

// 🔥 DODANA TRASA DASHBOARD
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// 🔥 STRONY STATYCZNE - Z KONTROLERAMI
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.submit');
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Trasy wymagające uwierzytelnienia
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔥 ADRESY UŻYTKOWNIKA
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
        Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });

    // Wishlist - tylko dla zalogowanych
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
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
        Route::get('/count', [CartController::class, 'count'])->name('count');
        Route::delete('/clear/all', [CartController::class, 'clear'])->name('clear');
    });

    // 🔥 CHECKOUT ROUTES - KOMPLETNE Z ADRESAMI
    Route::prefix('checkout')->name('checkout.')->group(function () {
        // Główne strony checkout
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'processOrder'])->name('process');

        // Buy Now routes
        Route::post('/buy-now', [CheckoutController::class, 'buyNow'])->name('buy-now');
        Route::post('/buy-now/process', [CheckoutController::class, 'processBuyNow'])->name('process-buy-now');

        // ✅ TRASA SUCCESS
        Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');

        // Powroty z płatności zewnętrznych
        Route::get('/payment/paypal/{orderNumber}/return', [CheckoutController::class, 'paypalReturn'])->name('payment.paypal.return');
        Route::get('/payment/transfer/{orderNumber}/return', [CheckoutController::class, 'transferReturn'])->name('payment.transfer.return');

        // Historia i szczegóły zamówień
        Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [CheckoutController::class, 'orderDetails'])->name('order-details');
        Route::post('/orders/{order}/cancel', [CheckoutController::class, 'cancelOrder'])->name('order.cancel');

        // Śledzenie zamówienia
        Route::get('/track/{orderNumber}', [CheckoutController::class, 'trackOrder'])->name('track');
    });
});

// Produkty - dostępne dla wszystkich
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/{id}/images', [ProductController::class, 'getImages'])->name('images');
    Route::post('/{id}/check-stock', [ProductController::class, 'checkStock'])->name('check-stock');
});

// 🔥 DODATKOWE TRASY PRODUKTÓW (jeśli potrzebne)
Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

require __DIR__.'/auth.php';

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// 🔥 DODATKOWE TRASY API (jeśli potrzebne)
Route::prefix('api')->name('api.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
        Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
    });
});

// 🔥 FALLBACK ROUTE (opcjonalnie)
Route::fallback(function () {
    return view('errors.404');
});
