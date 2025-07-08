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
        'is_available',
        'sku',
        'category'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        return $primaryImage ? asset('storage/' . $primaryImage->image_path) : null;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' zł';
    }

    public function isInStock()
    {
        return $this->stock_quantity > 0 && $this->is_available;
    }
}