<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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
            ],
            [
                'name' => 'Smartphone Samsung Galaxy S24',
                'description' => 'Flagowy smartfon z aparatem 200MP i wyświetlaczem 6.8 cala.',
                'price' => 3499.99,
                'stock_quantity' => 20,
                'sku' => 'SAMSUNG-S24',
                'category' => 'Elektronika'
            ],
            [
                'name' => 'Kamera Canon EOS R6',
                'description' => 'Profesjonalna kamera bezlusterkowa z pełnoklatkową matrycą.',
                'price' => 8999.99,
                'stock_quantity' => 8,
                'sku' => 'CANON-R6',
                'category' => 'Foto'
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            $this->addImagesForProduct($product);
        }
    }

    private function addImagesForProduct(Product $product)
    {
        // Stwórz katalog dla produktu
        $productDir = "products/{$product->id}";
        Storage::disk('public')->makeDirectory($productDir);

        // Pobierz 4 obrazki dla każdego produktu
        for ($i = 1; $i <= 4; $i++) {
            $imageUrl = "https://picsum.photos/800/600?random=" . ($product->id * 10 + $i);
            $imageName = "image_{$i}.jpg";
            $imagePath = "{$productDir}/{$imageName}";

            try {
                // Pobierz obrazek z internetu
                $response = Http::timeout(30)->get($imageUrl);
                
                if ($response->successful()) {
                    // Zapisz obrazek
                    Storage::disk('public')->put($imagePath, $response->body());
                    
                    // Dodaj rekord do bazy danych
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'alt_text' => "{$product->name} - Zdjęcie {$i}",
                        'sort_order' => $i,
                        'is_primary' => $i === 1 // Pierwsze zdjęcie jako główne
                    ]);

                    $this->command->info("Dodano obrazek {$i} dla produktu: {$product->name}");
                } else {
                    $this->command->warn("Nie udało się pobrać obrazka {$i} dla produktu: {$product->name}");
                }
            } catch (\Exception $e) {
                $this->command->error("Błąd podczas pobierania obrazka {$i} dla produktu {$product->name}: " . $e->getMessage());
            }
        }
    }
}