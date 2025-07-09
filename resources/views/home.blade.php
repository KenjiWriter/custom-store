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

    <!-- DEBUG INFO -->
    <div style="background: #f0f0f0; padding: 10px; margin: 20px 0; border-radius: 5px;">
        <p><strong>Debug Info:</strong></p>
        <p>Liczba produktów: {{ $products->count() }}</p>
        <p>Zalogowany: {{ auth()->check() ? 'TAK' : 'NIE' }}</p>
        <p>User ID: {{ auth()->id() ?? 'Brak' }}</p>
    </div>

    <!-- Siatka produktów -->
    <div class="products-grid">
        @forelse($products as $product)
            <!-- UŻYJ METODY Z MODELU -->
            {!! $product->card_html !!}
        @empty
            <div class="no-products">
                <h3>🛍️ Brak produktów</h3>
                <p>Aktualnie nie mamy produktów do wyświetlenia. Sprawdź ponownie wkrótce!</p>
                <p><a href="{{ route('home') }}">Odśwież stronę</a></p>
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

@endsection

@push('scripts')
<script src="{{ asset('js/image-gallery-modal.js') }}"></script>
<script>
// Funkcje dla akcji produktów na stronie głównej
function addToCart(productId) {
    console.log('Adding to cart:', productId);
    alert('Produkt został dodany do koszyka! (ID: ' + productId + ')');

    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '✅ Dodano!';
    button.style.background = '#27ae60';

    setTimeout(() => {
        button.innerHTML = originalText;
        button.style.background = '';
    }, 2000);
}

function buyNow(productId) {
    console.log('Buy now:', productId);
    if (confirm('Czy chcesz przejść do kasy? (Produkt ID: ' + productId + ')')) {
        alert('Przekierowywanie do kasy...');
    }
}

function toggleWishlist(productId) {
    console.log('Toggle wishlist:', productId);
    const button = event.target;
    const isInWishlist = button.classList.contains('in-wishlist');

    if (isInWishlist) {
        button.innerHTML = '❤️ Dodaj do ulubionych';
        button.classList.remove('in-wishlist');
        button.style.background = '';
        alert('Usunięto z ulubionych! (ID: ' + productId + ')');
    } else {
        button.innerHTML = '💖 W ulubionych';
        button.classList.add('in-wishlist');
        button.style.background = '#e74c3c';
        button.style.color = 'white';
        alert('Dodano do ulubionych! (ID: ' + productId + ')');
    }
}

// Debug info
console.log('Home page scripts loaded');
console.log('Auth check:', @json(auth()->check()));
</script>
@endpush
