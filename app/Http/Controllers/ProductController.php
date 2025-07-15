<?php
/* filepath: c:\xampp\htdocs\custom-store\app\Http\Controllers\ProductController.php */

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['images' => function($query) {
            $query->orderBy('sort_order')->orderBy('is_primary', 'desc');
        }])->findOrFail($id);

        // Pobierz podobne produkty z tej samej kategorii
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->with(['images' => function($query) {
                $query->where('is_primary', true)->first();
            }])
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function getImages($id)
    {
        $product = Product::with(['images' => function($query) {
            $query->orderBy('sort_order')->orderBy('is_primary', 'desc');
        }])->find($id);

        if (!$product) {
            return response()->json(['error' => 'Produkt nie znaleziony'], 404);
        }

        $images = $product->images->map(function($image) {
            return [
                'url' => asset('storage/' . $image->image_path),
                'alt' => $image->alt_text ?? $product->name
            ];
        });

        return response()->json([
            'images' => $images,
            'product' => [
                'name' => $product->name,
                'price' => $product->formatted_price,
                'url' => route('products.show', $product->id)
            ]
        ]);
    }

    /**
     * Sprawdź dostępność produktu
     */
    public function checkStock(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($id);
            $requestedQuantity = $request->quantity;

            return response()->json([
                'available' => $product->stock_quantity >= $requestedQuantity,
                'stock_quantity' => $product->stock_quantity,
                'message' => $product->stock_quantity >= $requestedQuantity
                    ? 'Produkt dostępny'
                    : "Niewystarczająca ilość w magazynie. Dostępne: {$product->stock_quantity} szt."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'message' => 'Błąd podczas sprawdzania dostępności'
            ], 500);
        }
    }
}
