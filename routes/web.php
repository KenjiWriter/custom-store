<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::get('/', function () {
    $products = Product::with(['images' => function($query) {
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

    // WISHLIST ROUTES - CHRONIONE AUTORYZACJĄ
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/', [WishlistController::class, 'store'])->name('store');
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{productId}', [WishlistController::class, 'destroy'])->name('destroy');
        Route::get('/count', [WishlistController::class, 'count'])->name('count');
        Route::get('/check/{productId}', [WishlistController::class, 'check'])->name('check');
    });
});

// Produkty - dostępne dla wszystkich
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/{id}/images', [ProductController::class, 'getImages'])->name('images');
});

require __DIR__.'/auth.php';
