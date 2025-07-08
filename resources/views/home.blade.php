<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Strona Główna</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .product-name {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .product-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .product-price {
            font-size: 1.3em;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 10px;
        }
        
        .product-stock {
            font-size: 0.9em;
            color: #27ae60;
        }
        
        .no-image {
            width: 100%;
            height: 200px;
            background-color: #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            margin-bottom: 15px;
        }
        
        .pagination {
            margin-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nasz Sklep</h1>
            <p>Odkryj najlepsze produkty w najlepszych cenach</p>
        </div>

        <div class="products-grid">
            @forelse($products as $product)
                <div class="product-card">
                    @if($product->primaryImage)
                        <img src="{{ $product->primary_image_url }}" 
                             alt="{{ $product->primaryImage->alt_text ?? $product->name }}" 
                             class="product-image">
                    @else
                        <div class="no-image">
                            Brak zdjęcia
                        </div>
                    @endif
                    
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-description">
                        {{ Str::limit($product->description, 100) }}
                    </div>
                    <div class="product-price">{{ $product->formatted_price }}</div>
                    <div class="product-stock">
                        @if($product->isInStock())
                            Dostępne: {{ $product->stock_quantity }} szt.
                        @else
                            Brak w magazynie
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <p>Brak produktów do wyświetlenia.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>
</body>
</html>