<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->with(['images']);
    }

    // Statyczne metody dla łatwego zarządzania koszykiem
    public static function getUserCartItems($userId)
    {
        return self::where('user_id', $userId)
                  ->with(['product.images'])
                  ->get();
    }

    public static function getUserCartTotal($userId)
    {
        return self::where('user_id', $userId)
                  ->with('product')
                  ->get()
                  ->sum(function ($cartItem) {
                      return $cartItem->product->price * $cartItem->quantity;
                  });
    }

    public static function getUserCartCount($userId)
    {
        return self::where('user_id', $userId)->sum('quantity');
    }

    public static function addProduct($userId, $productId, $quantity = 1)
    {
        $existingItem = self::where('user_id', $userId)
                           ->where('product_id', $productId)
                           ->first();

        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->save();
            return $existingItem;
        }

        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    public static function removeProduct($userId, $productId)
    {
        return self::where('user_id', $userId)
                  ->where('product_id', $productId)
                  ->delete();
    }

    public static function updateQuantity($userId, $productId, $quantity)
    {
        if ($quantity <= 0) {
            return self::removeProduct($userId, $productId);
        }

        return self::where('user_id', $userId)
                  ->where('product_id', $productId)
                  ->update(['quantity' => $quantity]);
    }

    public static function clearCart($userId)
{
    return self::where('user_id', $userId)->delete();
}

public static function clearUserCart($userId)
{
    return self::clearCart($userId);
}
}
