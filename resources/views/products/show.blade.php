@extends('layouts.app')

@section('title', $product->name . ' - Nasz Sklep')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-show.css') }}">
@endpush

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Strona główna</a>
        <span>/</span>
        <span>{{ $product->category ?? 'Produkty' }}</span>
        <span>/</span>
        <span>{{ $product->name }}</span>
    </nav>

    <div class="product-details">
        <!-- Galeria zdjęć -->
        <div class="product-gallery">
            @if($product->images->count() > 0)
                <!-- Główne zdjęcie -->
                <div class="main-image">
                    <img id="mainImage" 
                         src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                         alt="{{ $product->images->first()->alt_text ?? $product->name }}"
                         onclick="openImageModal(this.src, '{{ $product->images->first()->alt_text ?? $product->name }}')">
                    <div class="zoom-hint">🔍 Kliknij aby powiększyć</div>
                </div>

                <!-- Miniaturki -->
                @if($product->images->count() > 1)
                <div class="thumbnails">
                    <button class="nav-btn prev-btn" onclick="previousImage()">‹</button>
                    <div class="thumbnails-container">
                        @foreach($product->images as $index => $image)
                            <img class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                 src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->alt_text ?? $product->name }}"
                                 onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', '{{ $image->alt_text ?? $product->name }}', {{ $index }})">
                        @endforeach
                    </div>
                    <button class="nav-btn next-btn" onclick="nextImage()">›</button>
                </div>
                @endif
            @else
                <div class="no-image-large">
                    📷 Brak zdjęć produktu
                </div>
            @endif
        </div>

        <!-- Informacje o produkcie -->
        <div class="product-info">
            <h1>{{ $product->name }}</h1>
            
            @if($product->sku)
                <p class="product-sku">SKU: {{ $product->sku }}</p>
            @endif

            <div class="product-price">{{ $product->formatted_price }}</div>

            <div class="product-stock {{ !$product->isInStock() ? 'out-of-stock' : '' }}">
                @if($product->isInStock())
                    <span class="stock-icon">✅</span>
                    <span>Dostępne: {{ $product->stock_quantity }} szt.</span>
                @else
                    <span class="stock-icon">❌</span>
                    <span>Brak w magazynie</span>
                @endif
            </div>

            <div class="product-description">
                <h3>Opis produktu</h3>
                <p>{{ $product->description }}</p>
            </div>

            @if($product->category)
                <div class="product-category">
                    <strong>Kategoria:</strong> {{ $product->category }}
                </div>
            @endif

            <!-- Przyciski akcji -->
            <div class="product-actions">
                @if($product->isInStock())
                    <button class="btn-add-to-cart">
                        🛒 Dodaj do koszyka
                    </button>
                    <button class="btn-buy-now">
                        ⚡ Kup teraz
                    </button>
                @else
                    <button class="btn-notify" disabled>
                        🔔 Powiadom o dostępności
                    </button>
                @endif
                <button class="btn-wishlist">
                    ❤️ Dodaj do ulubionych
                </button>
            </div>
        </div>
    </div>

    <!-- Produkty powiązane -->
    @if($relatedProducts->count() > 0)
    <div class="related-products">
        <h2>Podobne produkty</h2>
        <div class="related-grid">
            @foreach($relatedProducts as $relatedProduct)
                <div class="related-card">
                    <a href="{{ route('products.show', $relatedProduct->id) }}">
                        @if($relatedProduct->primaryImage)
                            <img src="{{ $relatedProduct->primary_image_url }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="related-image">
                        @else
                            <div class="related-no-image">📷</div>
                        @endif
                        <div class="related-info">
                            <h4>{{ $relatedProduct->name }}</h4>
                            <p class="related-price">{{ $relatedProduct->formatted_price }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Modal do powiększania zdjęć -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <div class="modal-content">
        <span class="close-btn" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="">
        <div class="modal-nav">
            <button class="modal-nav-btn prev" onclick="modalPreviousImage(event)">‹</button>
            <button class="modal-nav-btn next" onclick="modalNextImage(event)">›</button>
        </div>
        <div class="modal-caption" id="modalCaption"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentImageIndex = 0;
const images = @json($product->images->map(function($image) {
    return [
        'url' => asset('storage/' . $image->image_path),
        'alt' => $image->alt_text ?? $product->name
    ];
}));

function changeMainImage(src, alt, index) {
    document.getElementById('mainImage').src = src;
    document.getElementById('mainImage').alt = alt;
    currentImageIndex = index;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
    document.querySelectorAll('.thumbnail')[index].classList.add('active');
}

function previousImage() {
    if (images.length <= 1) return;
    currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
    changeMainImage(images[currentImageIndex].url, images[currentImageIndex].alt, currentImageIndex);
}

function nextImage() {
    if (images.length <= 1) return;
    currentImageIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
    changeMainImage(images[currentImageIndex].url, images[currentImageIndex].alt, currentImageIndex);
}

function openImageModal(src, alt) {
    document.getElementById('imageModal').style.display = 'flex';
    document.getElementById('modalImage').src = src;
    document.getElementById('modalCaption').textContent = alt;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function modalPreviousImage(event) {
    event.stopPropagation();
    previousImage();
    document.getElementById('modalImage').src = images[currentImageIndex].url;
    document.getElementById('modalCaption').textContent = images[currentImageIndex].alt;
}

function modalNextImage(event) {
    event.stopPropagation();
    nextImage();
    document.getElementById('modalImage').src = images[currentImageIndex].url;
    document.getElementById('modalCaption').textContent = images[currentImageIndex].alt;
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('imageModal').style.display === 'flex') {
        if (e.key === 'Escape') closeImageModal();
        if (e.key === 'ArrowLeft') modalPreviousImage(e);
        if (e.key === 'ArrowRight') modalNextImage(e);
    }
});
</script>
@endpush