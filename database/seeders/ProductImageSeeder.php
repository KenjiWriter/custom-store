<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('Brak produktów w bazie danych. Uruchom najpierw ProductSeeder.');
            return;
        }

        foreach ($products as $product) {
            // Sprawdź czy produkt już ma obrazki
            if ($product->images()->count() > 0) {
                $this->command->info("Produkt '{$product->name}' już ma obrazki. Pomijam...");
                continue;
            }

            $this->addImagesForProduct($product);
        }
    }

    private function addImagesForProduct(Product $product)
    {
        // Stwórz katalog dla produktu
        $productDir = "products/{$product->id}";
        Storage::disk('public')->makeDirectory($productDir);

        $this->command->info("Dodaję obrazki dla produktu: {$product->name}");

        // Pobierz 4 obrazki dla każdego produktu
        for ($i = 1; $i <= 4; $i++) {
            $imageName = "image_{$i}.jpg";
            $imagePath = "{$productDir}/{$imageName}";

            // Próbuj pobrać obrazek z różnych źródeł
            $imageDownloaded = $this->tryDownloadImage($product, $i, $imagePath);
            
            if (!$imageDownloaded) {
                // Jeśli nie udało się pobrać, stwórz lokalny placeholder
                $this->createLocalPlaceholderImage($productDir, $imageName, $product, $i);
            }

            // Dodaj rekord do bazy danych
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
                'alt_text' => "{$product->name} - Zdjęcie {$i}",
                'sort_order' => $i,
                'is_primary' => $i === 1
            ]);

            $this->command->info("✅ Dodano obrazek {$i} dla produktu: {$product->name}");
        }

        $this->command->info("✅ Zakończono dodawanie obrazków dla produktu: {$product->name}");
    }

    private function tryDownloadImage(Product $product, int $imageNumber, string $imagePath): bool
    {
        // Lista URL-i do wypróbowania (bez HTTPS jeśli problemy z SSL)
        $imageUrls = [
            "http://picsum.photos/800/600?random=" . ($product->id * 10 + $imageNumber),
            "http://via.placeholder.com/800x600/cccccc/333333?text=Product+{$product->id}-{$imageNumber}",
        ];

        foreach ($imageUrls as $url) {
            try {
                $this->command->info("Próbuję pobrać z: {$url}");
                
                // Konfiguracja HTTP z wyłączonym sprawdzaniem SSL
                $response = Http::withOptions([
                    'verify' => false, // Wyłącz weryfikację SSL
                    'timeout' => 15,
                    'connect_timeout' => 10,
                ])->get($url);
                
                if ($response->successful() && $response->body()) {
                    Storage::disk('public')->put($imagePath, $response->body());
                    $this->command->info("✅ Pobrano obrazek z: {$url}");
                    return true;
                }
            } catch (\Exception $e) {
                $this->command->warn("❌ Błąd z {$url}: " . $e->getMessage());
                continue;
            }
        }

        return false;
    }

    private function createLocalPlaceholderImage(string $productDir, string $imageName, Product $product, int $imageNumber)
    {
        try {
            // Stwórz prosty obrazek SVG jako placeholder
            $svgContent = $this->generateSvgPlaceholder($product, $imageNumber);
            $imagePath = "{$productDir}/{$imageName}";
            
            // Zapisz SVG jako obrazek
            Storage::disk('public')->put($imagePath, $svgContent);
            
            $this->command->info("📄 Utworzono lokalny placeholder {$imageNumber} dla produktu: {$product->name}");
        } catch (\Exception $e) {
            $this->command->error("❌ Nie udało się utworzyć placeholder'a: " . $e->getMessage());
        }
    }

    private function generateSvgPlaceholder(Product $product, int $imageNumber): string
    {
        $colors = ['#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6'];
        $bgColor = $colors[($product->id + $imageNumber) % count($colors)];
        $textColor = '#ffffff';
        
        return <<<SVG
<svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">
    <rect width="800" height="600" fill="{$bgColor}"/>
    <text x="400" y="280" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" fill="{$textColor}">
        {$product->name}
    </text>
    <text x="400" y="320" font-family="Arial, sans-serif" font-size="18" text-anchor="middle" fill="{$textColor}">
        Zdjęcie {$imageNumber}
    </text>
    <text x="400" y="350" font-family="Arial, sans-serif" font-size="14" text-anchor="middle" fill="{$textColor}">
        800 x 600
    </text>
</svg>
SVG;
    }
}