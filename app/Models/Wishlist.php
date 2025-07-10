<?php
// filepath: c:\xampp\htdocs\custom-store\app\Models\Wishlist.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacja do użytkownika
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacja do produktu
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Sprawdź czy produkt jest w ulubionych użytkownika
    public static function isInWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->exists();
    }

    // Dodaj produkt do ulubionych
    public static function addToWishlist($userId, $productId)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    // Usuń produkt z ulubionych
    public static function removeFromWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->delete();
    }

    // Pobierz liczbę ulubionych produktów użytkownika
    public static function getUserWishlistCount($userId)
    {
        return self::where('user_id', $userId)->count();
    }
}
