<?php
// filepath: c:\xampp\htdocs\custom-store\app\Http\Controllers\WishlistController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Wyświetl listę ulubionych produktów
     */
    public function index()
    {
        $user = Auth::user();

        // Pobierz ulubione produkty z paginacją
        $favoriteProducts = $user->favoriteProducts()
            ->with(['images' => function($query) {
                $query->where('is_primary', true)->first();
            }])
            ->paginate(12);

        return view('wishlist.index', compact('favoriteProducts'));
    }

    /**
     * Dodaj produkt do ulubionych (AJAX)
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $user = Auth::user();
            $productId = $request->product_id;

            // Sprawdź czy produkt już jest w ulubionych
            if ($user->hasInWishlist($productId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produkt jest już w Twoich ulubionych!',
                    'action' => 'already_exists'
                ], 409);
            }

            // Dodaj do ulubionych
            $wishlistItem = $user->addToWishlist($productId);
            $product = Product::find($productId);

            return response()->json([
                'success' => true,
                'message' => "Produkt '{$product->name}' został dodany do ulubionych!",
                'action' => 'added',
                'wishlist_count' => $user->wishlist_count,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas dodawania do ulubionych.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Usuń produkt z ulubionych (AJAX)
     */
    public function destroy(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            $product = Product::findOrFail($productId);

            // Sprawdź czy produkt jest w ulubionych
            if (!$user->hasInWishlist($productId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produkt nie znajduje się w Twoich ulubionych!',
                    'action' => 'not_found'
                ], 404);
            }

            // Usuń z ulubionych
            $user->removeFromWishlist($productId);

            return response()->json([
                'success' => true,
                'message' => "Produkt '{$product->name}' został usunięty z ulubionych!",
                'action' => 'removed',
                'wishlist_count' => $user->wishlist_count,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas usuwania z ulubionych.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Przełącz status produktu w ulubionych (AJAX)
     */
    public function toggle(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $user = Auth::user();
            $productId = $request->product_id;
            $product = Product::findOrFail($productId);

            // Przełącz status
            $added = $user->toggleWishlist($productId);

            return response()->json([
                'success' => true,
                'message' => $added
                    ? "Produkt '{$product->name}' został dodany do ulubionych!"
                    : "Produkt '{$product->name}' został usunięty z ulubionych!",
                'action' => $added ? 'added' : 'removed',
                'is_in_wishlist' => $added,
                'wishlist_count' => $user->wishlist_count,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas operacji.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pobierz liczbę ulubionych produktów (AJAX)
     */
    public function count()
    {
        $user = Auth::user();

        return response()->json([
            'count' => $user->wishlist_count
        ]);
    }

    /**
     * Sprawdź czy produkt jest w ulubionych (AJAX)
     */
    public function check($productId)
    {
        $user = Auth::user();
        $isInWishlist = $user->hasInWishlist($productId);

        return response()->json([
            'is_in_wishlist' => $isInWishlist,
            'wishlist_count' => $user->wishlist_count
        ]);
    }
}
