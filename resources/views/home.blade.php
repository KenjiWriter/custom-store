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
</script>
@endpush
