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
    <div style="background: rgba(255, 255, 255, 0.1);  -webkit-backdrop-filter: blur(20px); backdrop-filter: blur(20px); color: rgba(255, 255, 255, 0.9); padding: 1.5rem 2rem; margin: 2rem 0; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.1); position: relative; overflow: hidden; animation: debugSlideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);">
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
@endpush
