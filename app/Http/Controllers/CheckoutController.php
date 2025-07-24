<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Strona checkout - finalizacja zamówienia z koszyka
     */
    public function index()
    {
        $cartItems = Cart::getUserCartItems(Auth::id());

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Koszyk jest pusty!');
        }

        // 🔥 POPRAWKA - Oblicz cenę na podstawie aktualnej ceny produktu
        $cartTotal = 0;
        foreach ($cartItems as $item) {
            if ($item->product) {
                // Dodaj aktualną cenę do obiektu cart item
                $item->current_price = $item->product->price;
                $item->line_total = $item->product->price * $item->quantity;
                $cartTotal += $item->line_total;
            } else {
                // Jeśli produkt został usunięty
                $item->current_price = 0;
                $item->line_total = 0;
            }
        }

        $user = Auth::user();
        $defaultAddress = $user->default_address;
        $lastOrder = $user->last_order;

        return view('checkout.index', compact('cartItems', 'cartTotal', 'user', 'defaultAddress', 'lastOrder'));
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
            $product = Product::with(['images'])->findOrFail($request->product_id);

            // Sprawdź dostępność
            if ($product->stock_quantity < $quantity) {
                return back()->with('error', "Niewystarczająca ilość produktu w magazynie. Dostępne: {$product->stock_quantity} szt.");
            }

            $cartTotal = $product->price * $quantity;
            $user = Auth::user();
            $defaultAddress = $user->default_address;
            $lastOrder = $user->last_order;

            return view('checkout.buy-now', compact('product', 'quantity', 'cartTotal', 'user', 'defaultAddress', 'lastOrder'));

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

            // Walidacja dodatkowych pól dla płatności
            if ($request->payment_method === 'card') {
                $request->validate([
                    'card_number' => 'required|string|min:13|max:19',
                    'expiry_date' => 'required|string|size:5',
                    'cvv' => 'required|string|min:3|max:4',
                    'card_holder' => 'required|string|min:2'
                ]);
            } elseif ($request->payment_method === 'blik') {
                $request->validate([
                    'blik_code' => 'required|string|size:6'
                ]);
            }

            \Log::info('🔥 processOrder rozpoczęte', [
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method
            ]);

            DB::beginTransaction();

            $order = Order::createFromCart(Auth::id(), $request->only([
                'first_name', 'last_name', 'email', 'phone',
                'address', 'city', 'postal_code', 'country'
            ]), $request->payment_method);

            DB::commit();

            \Log::info('🔥 processOrder zakończone pomyślnie', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            return $this->handlePaymentAndRedirect($order, $request);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('🔥 Błąd processOrder: ' . $e->getMessage());
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

            // Walidacja dodatkowych pól dla płatności
            if ($request->payment_method === 'card') {
                $request->validate([
                    'card_number' => 'required|string|min:13|max:19',
                    'expiry_date' => 'required|string|size:5',
                    'cvv' => 'required|string|min:3|max:4',
                    'card_holder' => 'required|string|min:2'
                ]);
            } elseif ($request->payment_method === 'blik') {
                $request->validate([
                    'blik_code' => 'required|string|size:6'
                ]);
            }

            \Log::info('🔥 processBuyNow rozpoczęte', [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'payment_method' => $request->payment_method
            ]);

            DB::beginTransaction();

            $order = Order::createFromBuyNow(
                Auth::id(),
                $request->product_id,
                $request->quantity,
                $request->only([
                    'first_name', 'last_name', 'email', 'phone',
                    'address', 'city', 'postal_code', 'country'
                ]),
                $request->payment_method
            );

            DB::commit();

            \Log::info('🔥 processBuyNow zakończone pomyślnie', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            return $this->handlePaymentAndRedirect($order, $request);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('🔥 Błąd processBuyNow: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * 🔥 ZUNIFIKOWANA OBSŁUGA PŁATNOŚCI I PRZEKIEROWAŃ
     */
    private function handlePaymentAndRedirect(Order $order, Request $request = null)
    {
        try {
            \Log::info('🔥 handlePaymentAndRedirect rozpoczęte', [
                'order_id' => $order->id,
                'payment_method' => $order->payment_method
            ]);

            switch ($order->payment_method) {
                case 'card':
                case 'blik':
                    // Dla kart i BLIK - automatyczne potwierdzenie
                    $order->update([
                        'status' => 'confirmed',
                        'payment_status' => 'paid',
                        'payment_date' => now()
                    ]);
                    $message = "✅ Płatność została pomyślnie przetworzona!";
                    break;

                case 'paypal':
                    // PayPal - przekierowanie do płatności
                    return redirect()->route('checkout.payment.paypal.return', $order->order_number)
                                   ->with('info', 'Przekierowuję do PayPal...');

                case 'transfer':
                    // Przelew - przekierowanie do Przelewy24
                    return redirect()->route('checkout.payment.transfer.return', $order->order_number)
                                   ->with('info', 'Przekierowuję do systemu płatności...');

                case 'cash_on_delivery':
                    // Za pobraniem - pozostaw pending
                    $message = "✅ Zamówienie zostało złożone! Zapłacisz przy odbiorze.";
                    break;

                default:
                    $message = "✅ Zamówienie zostało złożone pomyślnie!";
                    break;
            }

            // 🔥 ZAWSZE PRZEKIERUJ NA SUCCESS
            $successUrl = route('checkout.success', $order->order_number);
            \Log::info('🔥 Przekierowuję na: ' . $successUrl);

            return redirect($successUrl)->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('🔥 Błąd handlePaymentAndRedirect: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Wystąpił błąd podczas przetwarzania płatności.');
        }
    }

    /**
     * STRONA SUKCESU - Potwierdzenie zamówienia
     */
    public function success($orderNumber)
    {
        try {
            \Log::info('🔥 Strona sukcesu dla zamówienia: ' . $orderNumber);

            $order = Order::where('order_number', $orderNumber)
                         ->where('user_id', Auth::id())
                         ->with(['items.product.images', 'address'])
                         ->firstOrFail();

            \Log::info('🔥 Zamówienie znalezione', [
                'order_id' => $order->id,
                'items_count' => $order->items->count()
            ]);

            return view('checkout.success', compact('order'));

        } catch (\Exception $e) {
            \Log::error('🔥 Błąd strony sukcesu: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Nie znaleziono zamówienia.');
        }
    }

    /**
     * Historia zamówień użytkownika
     */
    public function orders(Request $request)
    {
        try {
            $query = Auth::user()->orders()->with(['items.product.images', 'address']);

            // Filtrowanie po statusie
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filtrowanie po statusie płatności
            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            // Filtrowanie po metodzie płatności
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            // Filtrowanie po dacie
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Wyszukiwanie po numerze zamówienia
            if ($request->filled('search')) {
                $query->where('order_number', 'like', '%' . $request->search . '%');
            }

            // Sortowanie
            $sortBy = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $orders = $query->paginate(10)->withQueryString();

            // Statystyki
            $stats = [
                'total_orders' => Auth::user()->orders()->count(),
                'total_spent' => Auth::user()->orders()->where('payment_status', 'paid')->sum('total_amount'),
                'pending_orders' => Auth::user()->orders()->where('status', 'pending')->count(),
                'delivered_orders' => Auth::user()->orders()->where('status', 'delivered')->count(),
                'paid_orders' => Auth::user()->orders()->where('payment_status', 'paid')->count(),
            ];

            return view('checkout.orders', compact('orders', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Błąd listy zamówień: ' . $e->getMessage());
            return view('checkout.orders', ['orders' => collect(), 'stats' => []]);
        }
    }

    /**
     * Szczegóły zamówienia
     */
    public function orderDetails(Order $order)
    {
        try {
            // Sprawdź czy zamówienie należy do zalogowanego użytkownika
            if ($order->user_id !== Auth::id()) {
                abort(403, 'Brak dostępu do tego zamówienia.');
            }

            $order->load(['items.product.images', 'user', 'address']);

            return view('checkout.order-details', compact('order'));

        } catch (\Exception $e) {
            \Log::error('Błąd szczegółów zamówienia: ' . $e->getMessage());
            return redirect()->route('checkout.orders')->with('error', 'Nie można załadować szczegółów zamówienia.');
        }
    }

    /**
     * Anuluj zamówienie
     */
    public function cancelOrder(Order $order)
    {
        try {
            // Sprawdź czy zamówienie należy do użytkownika
            if ($order->user_id !== Auth::id()) {
                abort(403, 'Brak dostępu do tego zamówienia.');
            }

            // Sprawdź czy zamówienie można anulować
            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return back()->with('error', 'Nie można anulować tego zamówienia.');
            }

            DB::beginTransaction();

            // Przywróć stan magazynowy
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            // Zaktualizuj status zamówienia
            $order->update([
                'status' => 'cancelled',
                'payment_status' => $order->payment_status === 'paid' ? 'refunded' : 'failed'
            ]);

            DB::commit();

            return back()->with('success', 'Zamówienie zostało anulowane.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Wystąpił błąd podczas anulowania zamówienia.');
        }
    }

    /**
     * Powrót z PayPal
     */
    public function paypalReturn($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        $order->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
            'status' => 'confirmed'
        ]);

        return redirect()->route('checkout.success', $order->order_number)
                       ->with('success', "✅ Płatność PayPal zakończona pomyślnie!");
    }

    /**
     * Powrót z Przelewy24
     */
    public function transferReturn($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        $order->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
            'status' => 'confirmed'
        ]);

        return redirect()->route('checkout.success', $order->order_number)
                       ->with('success', "✅ Przelew zakończony pomyślnie!");
    }

    /**
     * Śledzenie zamówienia
     */
    public function trackOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', Auth::id())
                     ->with(['items.product.images', 'address'])
                     ->firstOrFail();

        // Historia statusów (można rozszerzyć o osobną tabelę)
        $statusHistory = [
            [
                'status' => 'pending',
                'date' => $order->created_at,
                'description' => 'Zamówienie zostało złożone'
            ],
        ];

        if ($order->payment_status === 'paid') {
            $statusHistory[] = [
                'status' => 'paid',
                'date' => $order->payment_date,
                'description' => 'Płatność została zrealizowana'
            ];
        }

        if ($order->status === 'confirmed') {
            $statusHistory[] = [
                'status' => 'confirmed',
                'date' => $order->updated_at,
                'description' => 'Zamówienie zostało potwierdzone'
            ];
        }

        return view('checkout.track-order', compact('order', 'statusHistory'));
    }
}
