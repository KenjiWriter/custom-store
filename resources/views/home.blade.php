@extends('layouts.app')

@section('title', 'Sklep - Najlepsze produkty online')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/image-gallery-modal.css') }}">
@endpush
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
            <div class="product-card" data-product-id="{{ $product->id }}">
                <div class="product-image">
                    @if($product->primaryImage)
                        <img src="{{ $product->primary_image_url }}"
                             alt="{{ $product->name }}"
                             onclick="openProductImageModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->formatted_price }}', '{{ route('products.show', $product->id) }}')">
                    @else
                        <div class="no-image">📷 Brak zdjęcia</div>
                    @endif
                </div>
                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <p class="price">{{ $product->formatted_price }}</p>
                    <p class="description">{{ Str::limit($product->description, 100) }}</p>

                    <!-- Przyciski akcji -->
                    <div class="product-actions">
                        @auth
                            <button class="btn btn-primary" onclick="addToCart({{ $product->id }})">
                                🛒 Dodaj do koszyka
                            </button>
                            <button class="btn btn-secondary" onclick="buyNow({{ $product->id }})">
                                ⚡ Kup teraz
                            </button>
                            <button class="btn btn-wishlist" onclick="toggleWishlist({{ $product->id }})">
                                ❤️ Ulubione
                            </button>
                        @else
                            <button class="requires-auth btn btn-primary" data-action="add-to-cart">
                                🛒 Dodaj do koszyka
                            </button>
                            <button class="requires-auth btn btn-secondary" data-action="buy-now">
                                ⚡ Kup teraz
                            </button>
                            <button class="requires-auth btn btn-wishlist" data-action="add-to-favorites">
                                ❤️ Ulubione
                            </button>
                        @endauth
                    </div>

                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline">
                        Zobacz szczegóły
                    </a>
                </div>
                <div class="product-stock">
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
<x-image-gallery-modal />
<script src="{{ asset('js/image-gallery-modal.js') }}"></script>

<script>
// Funkcje dla akcji produktów na stronie głównej
function addToCart(productId) {
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produkt został dodany do koszyka!');
            // Opcjonalnie: aktualizuj licznik koszyka
        } else {
            alert('Wystąpił błąd podczas dodawania do koszyka');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Wystąpił błąd podczas dodawania do koszyka');
    });
}

function buyNow(productId) {
    if (confirm('Czy chcesz przejść do kasy?')) {
        window.location.href = `/checkout/product/${productId}`;
    }
}

function toggleWishlist(productId) {
    fetch(`/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = event.target;
            if (data.added) {
                button.innerHTML = '💖 W ulubionych';
                button.classList.add('in-wishlist');
            } else {
                button.innerHTML = '❤️ Ulubione';
                button.classList.remove('in-wishlist');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Wystąpił błąd');
    });
}
</script>
@endsection
