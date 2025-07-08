@extends('layouts.app')

@section('title', 'Sklep - Najlepsze produkty online')


@section('content')
<div class="container">
    <!-- Sekcja powitalna -->
    <div class="header">
        <h1>Witaj w naszym sklepie!</h1>
        <p>Odkryj najlepsze produkty w najlepszych cenach</p>
    </div>

    <!-- Sekcja wyróżnionych produktów -->
    <div class="featured-section">
        <h2>🔥 Najlepsze oferty</h2>
        <p>Sprawdź nasze najnowsze produkty i skorzystaj z wyjątkowych promocji</p>
    </div>

    <!-- Siatka produktów -->
    <div class="products-grid">
        @forelse($products as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->id) }}">
                    @if($product->primaryImage)
                        <img src="{{ $product->primary_image_url }}" 
                             alt="{{ $product->primaryImage->alt_text ?? $product->name }}" 
                             class="product-image">
                    @else
                        <div class="no-image">
                            📷 Brak zdjęcia
                        </div>
                    @endif
                    
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-description">
                        {{ Str::limit($product->description, 120) }}
                    </div>
                    <div class="product-price">{{ $product->formatted_price }}</div>
                </a>
                <div class="product-stock {{ !$product->isInStock() ? 'out-of-stock' : '' }}">
                    @if($product->isInStock())
                        ✅ Dostępne: {{ $product->stock_quantity }} szt.
                    @else
                        ❌ Brak w magazynie
                    @endif
                </div>
            </div>
        @empty
            <div class="no-products">
                <h3>🛍️ Brak produktów</h3>
                <p>Aktualnie nie mamy produktów do wyświetlenia. Sprawdź ponownie wkrótce!</p>
            </div>
        @endforelse
    </div>

    <!-- Paginacja -->
    @if($products->hasPages())
        <div class="pagination">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection