<?php

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

        // Sugerowane produkty z tej samej kategorii
        $relatedProducts = Product::with(['primaryImage'])
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}