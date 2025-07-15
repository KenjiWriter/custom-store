<?php
/* filepath: c:\xampp\htdocs\custom-store\app\Models\Cart.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    // Relacje
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Akcesory
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2) . ' zł';
    }

    // Statyczne metody
    public static function addToCart($userId, $productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);

        // Sprawdź dostępność
        if ($product->stock_quantity < $quantity) {
            throw new \Exception("Niewystarczająca ilość produktu w magazynie. Dostępne: {$product->stock_quantity} szt.");
        }

        $cartItem = self::where('user_id', $userId)
                       ->where('product_id', $productId)
                       ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            // Sprawdź czy nowa ilość nie przekracza stanu
            if ($product->stock_quantity < $newQuantity) {
                throw new \Exception("Niewystarczająca ilość produktu w magazynie. Dostępne: {$product->stock_quantity} szt.");
            }

            $cartItem->update(['quantity' => $newQuantity]);
            return $cartItem;
        }

        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->price
        ]);
    }

    public static function getUserCartItems($userId)
    {
        return self::with('product.images')
                  ->where('user_id', $userId)
                  ->get();
    }

    public static function getUserCartTotal($userId)
    {
        return self::where('user_id', $userId)->sum(\DB::raw('quantity * price'));
    }

    public static function getUserCartCount($userId)
    {
        return self::where('user_id', $userId)->sum('quantity');
    }

    public static function clearUserCart($userId)
    {
        return self::where('user_id', $userId)->delete();
    }
}
