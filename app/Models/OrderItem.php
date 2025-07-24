<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        // POPRAWKA - Zawsze ładuj zdjęcia produktu
        return $this->belongsTo(Product::class)->with(['images']);
    }

    // Accessor dla sformatowanej ceny
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2, ',', ' ') . ' zł';
    }

    // Accessor dla całkowitej wartości pozycji
    public function getTotalPriceAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    // Accessor dla sformatowanej całkowitej wartości
    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price, 2, ',', ' ') . ' zł';
    }

    // NOWE - Accessor dla głównego zdjęcia produktu
    public function getProductImageAttribute(): ?string
    {
        if ($this->product && $this->product->primary_image_url) {
            return $this->product->primary_image_url;
        }
        return null;
    }

    // NOWE - Accessor dla danych zdjęć do galerii
    public function getProductImagesAttribute(): array
    {
        if ($this->product && $this->product->images) {
            return $this->product->images->map(function($image) {
                return [
                    'url' => $image->image_url,
                    'alt' => $image->alt_text ?? $this->product->name
                ];
            })->toArray();
        }
        return [];
    }
}
