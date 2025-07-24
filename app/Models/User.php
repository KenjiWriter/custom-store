<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELACJE DO KOSZYKA I ZAMÓWIEŃ
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    // RELACJA DO ADRESÓW
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_default', true);
    }

    // RELACJE DO WISHLIST
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps()
                    ->orderBy('wishlists.created_at', 'desc');
    }

    // AKCESORY
    public function getCartCountAttribute()
    {
        return Cart::getUserCartCount($this->id);
    }

    public function getCartTotalAttribute()
    {
        return Cart::getUserCartTotal($this->id);
    }

    public function getWishlistCountAttribute()
    {
        return Wishlist::getUserWishlistCount($this->id);
    }

    public function getFullNameAttribute()
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?: $this->name;
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    public function getTotalSpentAttribute()
    {
        return $this->orders()->where('payment_status', 'paid')->sum('total_amount');
    }

    public function getLastOrderAttribute()
    {
        return $this->orders()->latest()->first();
    }

    public function getDefaultAddressAttribute()
    {
        return $this->orders()->latest()->first();
    }

    // METODY KOSZYKA
    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock_quantity < $quantity) {
            throw new \Exception("Niewystarczająca ilość produktu w magazynie. Dostępne: {$product->stock_quantity} szt.");
        }

        $cartItem = $this->cartItems()->updateOrCreate(
            ['product_id' => $productId],
            [
                'quantity' => DB::raw("quantity + {$quantity}"),
            ]
        );

        return $cartItem;
    }

    public function getCartItems()
    {
        return Cart::getUserCartItems($this->id);
    }

    // METODY WISHLIST
    public function hasInWishlist($productId)
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }

    public function addToWishlist($productId)
    {
        return Wishlist::addToWishlist($this->id, $productId);
    }

    public function removeFromWishlist($productId)
    {
        return Wishlist::removeFromWishlist($this->id, $productId);
    }

    public function toggleWishlist($productId)
    {
        if ($this->hasInWishlist($productId)) {
            $this->removeFromWishlist($productId);
            return false;
        } else {
            $this->addToWishlist($productId);
            return true;
        }
    }

    // METODY ADRESÓW
    public function createAddress(array $data, bool $setAsDefault = false)
    {
        $address = $this->addresses()->create($data);

        if ($setAsDefault || $this->addresses()->count() === 1) {
            $address->setAsDefault();
        }

        return $address;
    }

    public function updateDefaultAddress(array $data)
    {
        $defaultAddress = $this->default_address;

        if ($defaultAddress) {
            $defaultAddress->update($data);
            return $defaultAddress;
        }

        return $this->createAddress($data, true);
    }
}
