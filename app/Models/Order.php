<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacje
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Akcesory
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2, ',', ' ') . ' zł';
    }

    public function getFullNameAttribute()
    {
        return $this->address ? $this->address->full_name : 'Brak danych';
    }

    public function getFullAddressAttribute()
    {
        return $this->address ? $this->address->full_address : 'Brak danych';
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => ['text' => 'Oczekujące', 'class' => 'status-pending', 'icon' => '⏳'],
            'confirmed' => ['text' => 'Potwierdzone', 'class' => 'status-confirmed', 'icon' => '✅'],
            'processing' => ['text' => 'W realizacji', 'class' => 'status-processing', 'icon' => '🔄'],
            'shipped' => ['text' => 'Wysłane', 'class' => 'status-shipped', 'icon' => '🚚'],
            'delivered' => ['text' => 'Dostarczone', 'class' => 'status-delivered', 'icon' => '📦'],
            'cancelled' => ['text' => 'Anulowane', 'class' => 'status-cancelled', 'icon' => '❌']
        ];

        $status = $statuses[$this->status] ?? $statuses['pending'];

        return '<span class="badge ' . $status['class'] . '">' .
               $status['icon'] . ' ' . $status['text'] .
               '</span>';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => ['text' => 'Oczekuje płatności', 'class' => 'payment-pending', 'icon' => '💳'],
            'paid' => ['text' => 'Opłacone', 'class' => 'payment-paid', 'icon' => '✅'],
            'failed' => ['text' => 'Nieudana', 'class' => 'payment-failed', 'icon' => '❌'],
            'refunded' => ['text' => 'Zwrócone', 'class' => 'payment-refunded', 'icon' => '↩️']
        ];

        $status = $statuses[$this->payment_status] ?? $statuses['pending'];

        return '<span class="badge ' . $status['class'] . '">' .
               $status['icon'] . ' ' . $status['text'] .
               '</span>';
    }

    public function getPaymentMethodNameAttribute()
    {
        $methods = [
            'cash_on_delivery' => 'Płatność przy odbiorze',
            'card' => 'Karta płatnicza',
            'blik' => 'BLIK',
            'transfer' => 'Przelew bankowy',
            'paypal' => 'PayPal'
        ];

        return $methods[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    public function getTotalItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getTotalProductsCountAttribute()
    {
        return $this->items->count();
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
               $this->created_at->diffInHours(now()) < 24;
    }

    public function getHasInvoiceAttribute()
    {
        return $this->payment_status === 'paid';
    }

    public function getEstimatedDeliveryDateAttribute()
    {
        $days = match($this->payment_method) {
            'cash_on_delivery' => 3,
            'card', 'blik', 'transfer', 'paypal' => 2,
            default => 3
        };

        return $this->created_at->addDays($days);
    }

    // Statyczne metody
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public static function createFromCart($userId, $customerData, $paymentMethod)
    {
        try {
            DB::beginTransaction();

            $cartItems = Cart::getUserCartItems($userId);

            if ($cartItems->isEmpty()) {
                throw new \Exception('Koszyk jest pusty!');
            }

            // Sprawdź dostępność wszystkich produktów
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->product) {
                    throw new \Exception("Jeden z produktów został usunięty z oferty.");
                }

                if ($cartItem->product->stock_quantity < $cartItem->quantity) {
                    throw new \Exception("Niewystarczająca ilość produktu {$cartItem->product->name}. Dostępne: {$cartItem->product->stock_quantity} szt.");
                }
            }

            // Oblicz całkowitą kwotę
            $totalAmount = $cartItems->sum(function ($cartItem) {
                return $cartItem->product ? $cartItem->product->price * $cartItem->quantity : 0;
            });

            // Znajdź lub utwórz adres
            $user = User::find($userId);
            $address = $user->addresses()->updateOrCreate(
                [
                    'first_name' => $customerData['first_name'],
                    'last_name' => $customerData['last_name'],
                    'address' => $customerData['address'],
                    'city' => $customerData['city'],
                    'postal_code' => $customerData['postal_code']
                ],
                [
                    'email' => $customerData['email'],
                    'phone' => $customerData['phone'],
                    'country' => $customerData['country'] ?? 'Polska'
                ]
            );

            // Utwórz zamówienie
            $order = self::create([
                'order_number' => self::generateOrderNumber(),
                'user_id' => $userId,
                'address_id' => $address->id,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            \Log::info('🔥 Zamówienie utworzone z koszyka', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $userId,
                'total_amount' => $totalAmount
            ]);

            // Dodaj produkty do zamówienia
            foreach ($cartItems as $cartItem) {
                $orderItem = $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price
                ]);

                \Log::info('🔥 Dodano item do zamówienia', [
                    'order_item_id' => $orderItem->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity
                ]);

                // Zmniejsz stan magazynowy
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Wyczyść koszyk
            Cart::clearCart($userId);

            DB::commit();
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('🔥 Błąd createFromCart: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function createFromBuyNow($userId, $productId, $quantity, $customerData, $paymentMethod)
    {
        try {
            DB::beginTransaction();

            $product = Product::with(['images'])->findOrFail($productId);

            // Sprawdź dostępność
            if ($product->stock_quantity < $quantity) {
                throw new \Exception("Niewystarczająca ilość produktu {$product->name}. Dostępne: {$product->stock_quantity} szt.");
            }

            // Oblicz całkowitą kwotę
            $totalAmount = $product->price * $quantity;

            // Znajdź lub utwórz adres
            $user = User::find($userId);
            $address = $user->addresses()->updateOrCreate(
                [
                    'first_name' => $customerData['first_name'],
                    'last_name' => $customerData['last_name'],
                    'address' => $customerData['address'],
                    'city' => $customerData['city'],
                    'postal_code' => $customerData['postal_code']
                ],
                [
                    'email' => $customerData['email'],
                    'phone' => $customerData['phone'],
                    'country' => $customerData['country'] ?? 'Polska'
                ]
            );

            // Utwórz zamówienie
            $order = self::create([
                'order_number' => self::generateOrderNumber(),
                'user_id' => $userId,
                'address_id' => $address->id,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            \Log::info('🔥 Zamówienie Buy Now utworzone', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $userId,
                'product_id' => $productId,
                'total_amount' => $totalAmount
            ]);

            // Dodaj produkt do zamówienia
            $orderItem = $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);

            \Log::info('🔥 Dodano item Buy Now do zamówienia', [
                'order_item_id' => $orderItem->id,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);

            // Zmniejsz stan magazynowy
            $product->decrement('stock_quantity', $quantity);

            DB::commit();
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('🔥 Błąd createFromBuyNow: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function getUserOrdersWithImages($userId)
    {
        return self::where('user_id', $userId)
                  ->with(['items.product.images', 'address'])
                  ->orderBy('created_at', 'desc')
                  ->get();
    }

    public static function getOrderWithImages($orderId, $userId = null)
    {
        $query = self::with(['items.product.images', 'user', 'address']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->findOrFail($orderId);
    }
}
