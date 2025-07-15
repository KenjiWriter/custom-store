<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'sku',
        'category'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // DODANE RELACJE DLA WISHLIST

    /**
     * Relacja do wishlist
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Relacja many-to-many do użytkowników przez wishlist
     */
    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')
                    ->withTimestamps();
    }

    /**
     * Sprawdź czy produkt jest w ulubionych określonego użytkownika
     */
    public function isInUserWishlist($userId)
    {
        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    /**
     * Pobierz liczbę użytkowników którzy dodali ten produkt do ulubionych
     */
    public function getWishlistCountAttribute()
    {
        return $this->wishlists()->count();
    }

    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;

        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }

        return null;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' zł';
    }

    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    public function getCardHtmlAttribute()
    {
        $isAuthenticated = auth()->check();
        $stockClass = !$this->isInStock() ? 'out-of-stock' : '';
        $imageHtml = '';

        // POPRAWKA - kliknięcie w zdjęcie otwiera modal, nie przekierowuje
        if ($this->primaryImage) {
            $imageHtml = '<div class="product-image" onclick="event.preventDefault(); event.stopPropagation(); openProductImageModal(' . $this->id . ', \'' . addslashes($this->name) . '\', \'' . $this->formatted_price . '\', \'' . route('products.show', $this->id) . '\')">
                            <img src="' . $this->primary_image_url . '"
                                 alt="' . htmlspecialchars($this->name) . '">
                         </div>';
        } else {
            $imageHtml = '<div class="no-image" onclick="event.preventDefault(); event.stopPropagation(); openProductImageModal(' . $this->id . ', \'' . addslashes($this->name) . '\', \'' . $this->formatted_price . '\', \'' . route('products.show', $this->id) . '\')">📷 Brak zdjęcia</div>';
        }

        // Auth buttons
        $authButtonsHtml = '';

        if ($isAuthenticated) {
            $user = auth()->user();
            $isInWishlist = $user->hasInWishlist($this->id);

            if ($this->isInStock()) {
                $authButtonsHtml = '
                    <button class="btn-add-to-cart"
                            data-product-id="' . $this->id . '"
                            data-original-text="🛒 Dodaj do koszyka">
                        🛒 Dodaj do koszyka
                    </button>
                    <button class="btn-buy-now"
                            data-product-id="' . $this->id . '"
                            data-original-text="⚡ Kup teraz">
                        ⚡ Kup teraz
                    </button>';
            } else {
                $authButtonsHtml = '
                    <button class="btn-notify" disabled>
                        🔔 Powiadom o dostępności
                    </button>';
            }

            // PRZYCISK WISHLIST - z dynamicznym stanem
            $wishlistIcon = $isInWishlist ? '💖' : '❤️';
            $wishlistText = $isInWishlist ? 'W ulubionych' : 'Dodaj do ulubionych';
            $wishlistClass = $isInWishlist ? 'btn-wishlist in-wishlist' : 'btn-wishlist';

            $authButtonsHtml .= '
                <button class="' . $wishlistClass . '"
                        onclick="toggleWishlist(' . $this->id . ', this)"
                        data-product-id="' . $this->id . '"
                        data-in-wishlist="' . ($isInWishlist ? 'true' : 'false') . '">
                    ' . $wishlistIcon . ' ' . $wishlistText . '
                </button>';
        } else {
            if ($this->isInStock()) {
                $authButtonsHtml = '
                    <button class="requires-auth btn-add-to-cart"
                            data-action="add-to-cart"
                            data-product-id="' . $this->id . '"
                            data-product-name="' . htmlspecialchars($this->name) . '">
                        🛒 Dodaj do koszyka
                    </button>
                    <button class="requires-auth btn-buy-now"
                            data-action="buy-now"
                            data-product-id="' . $this->id . '"
                            data-product-name="' . htmlspecialchars($this->name) . '">
                        ⚡ Kup teraz
                    </button>';
            } else {
                $authButtonsHtml = '
                    <button class="requires-auth btn-notify"
                            data-action="notify"
                            data-product-id="' . $this->id . '"
                            data-product-name="' . htmlspecialchars($this->name) . '">
                        🔔 Powiadom o dostępności
                    </button>';
            }

            $authButtonsHtml .= '
                <button class="requires-auth btn-wishlist"
                        data-action="add-to-wishlist"
                        data-product-id="' . $this->id . '"
                        data-product-name="' . htmlspecialchars($this->name) . '">
                    ❤️ Dodaj do ulubionych
                </button>';
        }

        return '
        <div class="product-card" data-product-id="' . $this->id . '">
            ' . $imageHtml . '
            <div class="product-info">
                <a href="' . route('products.show', $this->id) . '" class="product-link">
                    <div class="product-name">' . htmlspecialchars($this->name) . '</div>
                    <div class="product-description">
                        ' . htmlspecialchars(\Str::limit($this->description, 120)) . '
                    </div>
                    <div class="product-price">' . $this->formatted_price . '</div>
                </a>
                <div class="product-actions">
                    ' . $authButtonsHtml . '
                </div>
                <div class="product-stock ' . $stockClass . '">
                    ' . ($this->isInStock()
                        ? '✅ Dostępne: ' . $this->stock_quantity . ' szt.'
                        : '❌ Brak w magazynie') . '
                </div>
            </div>
        </div>';
    }
}
