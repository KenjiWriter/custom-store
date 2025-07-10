<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    // DODANE RELACJE DLA WISHLIST

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
