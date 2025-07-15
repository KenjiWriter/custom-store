<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{

    /**
     * Pokaż stronę checkout
     */
    public function index(Request $request)
    {
        $cartItems = Auth::user()->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Koszyk jest pusty!');
        }

        // Sprawdź dostępność produktów
        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Produkt '{$item->product->name}' nie jest dostępny w wymaganej ilości.");
            }
        }

        $cartTotal = Auth::user()->cart_total;

        // Pobierz dane użytkownika do formularza
        $user = Auth::user();
        $lastOrder = $user->orders()->first();

        return view('checkout.index', compact('cartItems', 'cartTotal', 'user', 'lastOrder'));
    }

    /**
     * Szybkie zakupy (Buy Now) - single product
     */
    public function buyNow(Request $request)
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
                return back()->with('error', "Niewystarczająca ilość produktu w magazynie. Dostępne: {$product->stock_quantity} szt.");
            }

            // Dane pseudo-koszyka dla Buy Now
            $buyNowItems = collect([
                (object) [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total_price' => $product->price * $quantity,
                    'formatted_total_price' => number_format($product->price * $quantity, 2) . ' zł'
                ]
            ]);

            $cartTotal = $product->price * $quantity;
            $user = Auth::user();
            $lastOrder = $user->orders()->first();

            return view('checkout.buy-now', compact('buyNowItems', 'cartTotal', 'user', 'lastOrder', 'product', 'quantity'));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Przetwórz zamówienie z koszyka
     */
    public function processOrder(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'country' => 'string|max:100',
                'payment_method' => 'required|in:transfer,card,blik,paypal,cash_on_delivery'
            ]);

            DB::beginTransaction();

            $order = Order::createFromCart(Auth::id(), $request->only([
                'first_name', 'last_name', 'email', 'phone',
                'address', 'city', 'postal_code', 'country'
            ]), $request->payment_method);

            DB::commit();

            return redirect()->route('checkout.success', $order->order_number)
                           ->with('success', 'Zamówienie zostało złożone pomyślnie!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Przetwórz zamówienie Buy Now
     */
    public function processBuyNow(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:100',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'country' => 'string|max:100',
                'payment_method' => 'required|in:transfer,card,blik,paypal,cash_on_delivery'
            ]);

            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;

            // Sprawdź dostępność
            if ($product->stock_quantity < $quantity) {
                throw new \Exception("Niewystarczająca ilość produktu w magazynie. Dostępne: {$product->stock_quantity} szt.");
            }

            $totalAmount = $product->price * $quantity;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'Polska',
            ]);

            // Stwórz item zamówienia
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);

            // Zmniejsz stan magazynowy
            $product->decrement('stock_quantity', $quantity);

            DB::commit();

            return redirect()->route('checkout.success', $order->order_number)
                           ->with('success', 'Zamówienie zostało złożone pomyślnie!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Strona potwierdzenia zamówienia
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', Auth::id())
                     ->with('items.product')
                     ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    /**
     * Historia zamówień użytkownika
     */
    public function orders(Request $request)
    {
        $query = Auth::user()->orders()->with(['items.product.images']);

        // Filtrowanie po statusie
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filtrowanie po dacie
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sortowanie
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(10)->withQueryString();

        // Statystyki
        $stats = [
            'total_orders' => Auth::user()->orders()->count(),
            'total_spent' => Auth::user()->orders()->sum('total_amount'),
            'pending_orders' => Auth::user()->orders()->where('status', 'pending')->count(),
            'delivered_orders' => Auth::user()->orders()->where('status', 'delivered')->count(),
        ];

        return view('checkout.orders', compact('orders', 'stats'));
    }

    /**
     * Szczegóły zamówienia
     */
    public function orderDetails(Order $order)
    {
        // Sprawdź czy zamówienie należy do zalogowanego użytkownika
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Brak dostępu do tego zamówienia.');
        }

        $order->load(['items.product.images', 'user']);

        return view('checkout.order-details', compact('order'));
    }
}
