<?php
/* filepath: c:\xampp\htdocs\custom-store\app\Http\Controllers\CartController.php */
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Pokaż zawartość koszyka
     */
    public function index()
    {
        $cartItems = Auth::user()->getCartItems();
        $cartTotal = Auth::user()->cart_total;

        return view('cart.index', compact('cartItems', 'cartTotal'));
    }

    /**
     * Dodaj produkt do koszyka (AJAX)
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'integer|min:1|max:100'
            ]);

            $quantity = $request->quantity ?? 1;
            $product = Product::findOrFail($request->product_id);

            // Sprawdź dostępność
            if ($product->stock_quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Niewystarczająca ilość produktu w magazynie. Dostępne: {$product->stock_quantity} szt."
                ], 400);
            }

            $cartItem = Auth::user()->addToCart($request->product_id, $quantity);

            return response()->json([
                'success' => true,
                'message' => "Produkt '{$product->name}' został dodany do koszyka!",
                'cart_count' => Auth::user()->cart_count,
                'cart_total' => number_format(Auth::user()->cart_total, 2) . ' zł'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aktualizuj ilość produktu w koszyku (AJAX)
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:100'
            ]);

            $cartItem = Cart::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();

            // Sprawdź dostępność
            if ($cartItem->product->stock_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Niewystarczająca ilość produktu w magazynie. Dostępne: {$cartItem->product->stock_quantity} szt."
                ], 400);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            return response()->json([
                'success' => true,
                'message' => 'Ilość produktu została zaktualizowana!',
                'cart_count' => Auth::user()->cart_count,
                'cart_total' => number_format(Auth::user()->cart_total, 2) . ' zł',
                'item_total' => $cartItem->formatted_total_price
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Usuń produkt z koszyka (AJAX)
     */
    public function destroy($id)
    {
        try {
            $cartItem = Cart::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();

            $productName = $cartItem->product->name;
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => "Produkt '{$productName}' został usunięty z koszyka!",
                'cart_count' => Auth::user()->cart_count,
                'cart_total' => number_format(Auth::user()->cart_total, 2) . ' zł'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pobierz liczbę produktów w koszyku (AJAX)
     */
    public function count()
    {
        return response()->json([
            'count' => Auth::user()->cart_count,
            'total' => number_format(Auth::user()->cart_total, 2) . ' zł'
        ]);
    }

    /**
     * Wyczyść cały koszyk (AJAX)
     */
    public function clear()
    {
        try {
            Cart::clearUserCart(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Koszyk został wyczyszczony!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
