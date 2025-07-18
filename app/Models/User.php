<?php
/* filepath: c:\xampp\htdocs\custom-store\app\Models\User.php */
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELACJE DO KOSZYKA I ZAMÓWIEŃ

    /**
     * Relacja do koszyka
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relacja do zamówień
     */
    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    /**
     * Pobierz liczbę produktów w koszyku
     */
    public function getCartCountAttribute()
    {
        return Cart::getUserCartCount($this->id);
    }

    /**
     * Pobierz wartość koszyka
     */
    public function getCartTotalAttribute()
    {
        return Cart::getUserCartTotal($this->id);
    }

    /**
     * Dodaj produkt do koszyka
     */
    public function addToCart($productId, $quantity = 1)
    {
        // Pobierz produkt
        $product = Product::findOrFail($productId);

        // Dodaj lub zaktualizuj produkt w koszyku
        $cartItem = $this->cartItems()->updateOrCreate(
            ['product_id' => $productId],
            [
                'quantity' => DB::raw("quantity + {$quantity}"),
            ]
        );

        return $cartItem;
    }

    /**
     * Pobierz produkty z koszyka
     */
    public function getCartItems()
    {
        return Cart::getUserCartItems($this->id);
    }

    // RELACJE DO WISHLIST

    /**
     * Relacja do ulubionych produktów
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Relacja many-to-many do produktów przez wishlist
     */
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps()
                    ->orderBy('wishlists.created_at', 'desc');
    }

    /**
     * Sprawdź czy produkt jest w ulubionych
     */
    public function hasInWishlist($productId)
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }

    /**
     * Dodaj produkt do ulubionych
     */
    public function addToWishlist($productId)
    {
        return Wishlist::addToWishlist($this->id, $productId);
    }

    /**
     * Usuń produkt z ulubionych
     */
    public function removeFromWishlist($productId)
    {
        return Wishlist::removeFromWishlist($this->id, $productId);
    }

    /**
     * Przełącz status produktu w ulubionych
     */
    public function toggleWishlist($productId)
    {
        if ($this->hasInWishlist($productId)) {
            $this->removeFromWishlist($productId);
            return false; // Usunięto
        } else {
            $this->addToWishlist($productId);
            return true; // Dodano
        }
    }

    /**
     * Pobierz liczbę ulubionych produktów
     */
    public function getWishlistCountAttribute()
    {
        return $this->wishlists()->count();
    }
}
