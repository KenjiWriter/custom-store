<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Laptop Dell XPS 13',
                'description' => 'Nowoczesny laptop ultrabook z procesorem Intel Core i7 i ekranem 13 cali.',
                'price' => 4999.99,
                'stock_quantity' => 10,
                'sku' => 'DELL-XPS-13',
                'category' => 'Elektronika'
            ],
            [
                'name' => 'Słuchawki Sony WH-1000XM4',
                'description' => 'Bezprzewodowe słuchawki z aktywną redukcją szumów.',
                'price' => 1299.99,
                'stock_quantity' => 25,
                'sku' => 'SONY-WH-1000XM4',
                'category' => 'Audio'
            ],
            [
                'name' => 'Smartwatch Apple Watch Series 9',
                'description' => 'Najnowszy smartwatch od Apple z wieloma funkcjami zdrowotnymi.',
                'price' => 1899.99,
                'stock_quantity' => 15,
                'sku' => 'APPLE-WATCH-S9',
                'category' => 'Wearables'
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}