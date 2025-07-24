<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Pobierz zawartość koszyka (AJAX)
     */
    public function index()
    {
        try {
            $cartItems = Cart::where('user_id', Auth::id())
                            ->with(['product' => function($query) {
                                $query->with(['images' => function($imageQuery) {
                                    $imageQuery->orderBy('is_primary', 'desc')
                                           ->orderBy('sort_order', 'asc');
                                }]);
                            }])
                            ->get();

            // 🔥 POPRAWKA - Mapuj dane z prawidłowymi URL-ami zdjęć i cenami
            $items = $cartItems->map(function($item) {
                $product = $item->product;

                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'product' => $product ? [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => $product->price,
                        'formatted_price' => $product->formatted_price,
                        'primary_image_url' => $product->primary_image_url, // Używa accessora z modelu Product
                    ] : null,
                    'formatted_price' => $product ? $product->formatted_price : '0,00 zł',
                    'formatted_total_price' => $product ?
                        number_format($product->price * $item->quantity, 2, ',', ' ') . ' zł' :
                        '0,00 zł'
                ];
            });

            $cartTotal = $cartItems->sum(function($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            });

            return response()->json([
                'success' => true,
                'items' => $items,
                'total' => number_format($cartTotal, 2, ',', ' ') . ' zł',
                'count' => $cartItems->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Błąd CartController::index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'items' => [],
                'total' => '0,00 zł',
                'count' => 0
            ], 500);
        }
    }

    /**
     * Dodaj produkt do koszyka
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'integer|min:1|max:100'
            ]);

            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;

            // Sprawdź czy produkt istnieje i jest dostępny
            $product = Product::findOrFail($productId);

            if ($product->stock_quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Niewystarczająca ilość w magazynie. Dostępne: {$product->stock_quantity} szt."
                ], 400);
            }

            // Sprawdź czy produkt już jest w koszyku
            $existingCartItem = Cart::where('user_id', Auth::id())
                                  ->where('product_id', $productId)
                                  ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $quantity;

                if ($product->stock_quantity < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Maksymalna dostępna ilość: {$product->stock_quantity} szt. (masz już {$existingCartItem->quantity} w koszyku)"
                    ], 400);
                }

                $existingCartItem->update(['quantity' => $newQuantity]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }

            // Pobierz nową liczbę produktów w koszyku
            $cartCount = Cart::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => "Produkt został dodany do koszyka!",
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Błąd CartController::store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas dodawania do koszyka'
            ], 500);
        }
    }

    /**
     * Aktualizuj ilość produktu w koszyku
     */
    public function update(Request $request, $cartId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:100'
            ]);

            $cartItem = Cart::where('id', $cartId)
                          ->where('user_id', Auth::id())
                          ->with('product')
                          ->firstOrFail();

            $newQuantity = $request->quantity;

            // Sprawdź dostępność
            if ($cartItem->product && $cartItem->product->stock_quantity < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Niewystarczająca ilość w magazynie. Dostępne: {$cartItem->product->stock_quantity} szt."
                ], 400);
            }

            $cartItem->update(['quantity' => $newQuantity]);

            $cartCount = Cart::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Ilość została zaktualizowana',
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Błąd CartController::update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas aktualizacji'
            ], 500);
        }
    }

    /**
     * Usuń produkt z koszyka
     */
    public function destroy($cartId)
    {
        try {
            $cartItem = Cart::where('id', $cartId)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

            $cartItem->delete();

            $cartCount = Cart::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Produkt został usunięty z koszyka',
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Błąd CartController::destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas usuwania'
            ], 500);
        }
    }

    /**
     * Pobierz liczbę produktów w koszyku
     */
    public function count()
    {
        try {
            $count = Cart::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0
            ]);
        }
    }
}
