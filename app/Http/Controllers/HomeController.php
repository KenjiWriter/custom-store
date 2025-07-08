<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['primaryImage'])
            ->where('is_available', true)
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->paginate(12);

        return view('home', compact('products'));
    }
}