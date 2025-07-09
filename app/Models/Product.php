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
        'price' => 'decimal:2'
    ];

    // Relacje
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // Atrybuty
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', ' ') . ' zł';
    }

    public function getPrimaryImageUrlAttribute()
    {
        if ($this->primaryImage) {
            return asset('storage/' . $this->primaryImage->image_path);
        }
        return null;
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
        
        if ($this->primaryImage) {
            $imageHtml = '<img src="' . $this->primary_image_url . '" 
                             alt="' . htmlspecialchars($this->name) . '" 
                             class="product-image"
                             onclick="openProductImageModal(' . $this->id . ', \'' . addslashes($this->name) . '\', \'' . $this->formatted_price . '\', \'' . route('products.show', $this->id) . '\')">';
        } else {
            $imageHtml = '<div class="no-image">📷 Brak zdjęcia</div>';
        }

        $authButtonsHtml = '';
        if ($isAuthenticated) {
            if ($this->isInStock()) {
                $authButtonsHtml = '
                    <button class="btn-add-to-cart" onclick="addToCart(' . $this->id . ')">
                        🛒 Dodaj do koszyka
                    </button>
                    <button class="btn-buy-now" onclick="buyNow(' . $this->id . ')">
                        ⚡ Kup teraz
                    </button>';
            } else {
                $authButtonsHtml = '
                    <button class="btn-notify" disabled>
                        🔔 Powiadom o dostępności
                    </button>';
            }
            $authButtonsHtml .= '
                <button class="btn-wishlist" onclick="toggleWishlist(' . $this->id . ')">
                    ❤️ Dodaj do ulubionych
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
                            data-action="notify-availability" 
                            data-product-id="' . $this->id . '" 
                            data-product-name="' . htmlspecialchars($this->name) . '">
                        🔔 Powiadom o dostępności
                    </button>';
            }
            $authButtonsHtml .= '
                <button class="requires-auth btn-wishlist" 
                        data-action="add-to-favorites" 
                        data-product-id="' . $this->id . '" 
                        data-product-name="' . htmlspecialchars($this->name) . '">
                    ❤️ Dodaj do ulubionych
                </button>';
        }

        return '
        <div class="product-card" data-product-id="' . $this->id . '">
            <a href="' . route('products.show', $this->id) . '">
                ' . $imageHtml . '
                <div class="product-name">' . htmlspecialchars($this->name) . '</div>
                <div class="product-description">
                    ' . htmlspecialchars(\Str::limit($this->description, 120)) . '
                </div>
                <div class="product-price">' . $this->formatted_price . '</div>
            </a>
            <div class="product-stock ' . $stockClass . '">
                ' . ($this->isInStock() 
                    ? '✅ Dostępne: ' . $this->stock_quantity . ' szt.' 
                    : '❌ Brak w magazynie') . '
            </div>
            <div class="product-actions">
                ' . $authButtonsHtml . '
                <a href="' . route('products.show', $this->id) . '" class="btn-details">
                    👁️ Zobacz szczegóły
                </a>
            </div>
        </div>';
    }
}