<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'total_amount',
        'payment_method',
        'payment_status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'payment_data',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_data' => 'array',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacje
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Akcesory
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2) . ' zł';
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->postal_code . ' ' . $this->city . ', ' . $this->country;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="status-badge status-pending">⏳ Oczekuje</span>',
            'confirmed' => '<span class="status-badge status-confirmed">✅ Potwierdzone</span>',
            'processing' => '<span class="status-badge status-processing">📦 Przetwarzane</span>',
            'shipped' => '<span class="status-badge status-shipped">🚚 Wysłane</span>',
            'delivered' => '<span class="status-badge status-delivered">📮 Dostarczone</span>',
            'cancelled' => '<span class="status-badge status-cancelled">❌ Anulowane</span>',
            'returned' => '<span class="status-badge status-returned">↩️ Zwrócone</span>'
        ];

        return $badges[$this->status] ?? '<span class="status-badge status-unknown">❓ Nieznany</span>';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="payment-badge payment-pending">⏳ Oczekuje</span>',
            'paid' => '<span class="payment-badge payment-paid">💳 Opłacone</span>',
            'failed' => '<span class="payment-badge payment-failed">❌ Nieudane</span>',
            'refunded' => '<span class="payment-badge payment-refunded">💰 Zwrócone</span>'
        ];

        return $badges[$this->payment_status] ?? '<span class="payment-badge payment-unknown">❓ Nieznany</span>';
    }

    public function getTotalItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Statyczne metody
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public static function createFromCart($userId, $addressData, $paymentMethod = 'transfer')
    {
        $cartItems = Cart::getUserCartItems($userId);

        if ($cartItems->isEmpty()) {
            throw new \Exception('Koszyk jest pusty!');
        }

        // Sprawdź dostępność produktów
        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                throw new \Exception("Produkt '{$item->product->name}' nie jest dostępny w wymaganej ilości. Dostępne: {$item->product->stock_quantity} szt.");
            }
        }

        $totalAmount = Cart::getUserCartTotal($userId);

        $order = self::create([
            'order_number' => self::generateOrderNumber(),
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'first_name' => $addressData['first_name'],
            'last_name' => $addressData['last_name'],
            'email' => $addressData['email'],
            'phone' => $addressData['phone'],
            'address' => $addressData['address'],
            'city' => $addressData['city'],
            'postal_code' => $addressData['postal_code'],
            'country' => $addressData['country'] ?? 'Polska',
        ]);

        // Stwórz items zamówienia
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Zmniejsz stan magazynowy
            $item->product->decrement('stock_quantity', $item->quantity);
        }

        // Wyczyść koszyk
        Cart::clearUserCart($userId);

        return $order;
    }
}
