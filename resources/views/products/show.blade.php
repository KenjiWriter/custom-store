@extends('layouts.app')

@section('title', $product->name . ' - Nasz Sklep')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-show.css') }}">
<link rel="stylesheet" href="{{ asset('css/image-gallery-modal.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initCart(); // Inicjalizuj CartManager na stronie produktu
    });
</script>
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
                         onclick="openProductImageModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->formatted_price }}', '{{ route('products.show', $product->id) }}')">
                    <div class="zoom-hint">🔍 Kliknij aby powiększyć</div>
                </div>

                <!-- Miniaturki -->
                @if($product->images->count() > 1)
                <div class="thumbnails">
                    <button class="nav-btn prev-btn">‹</button>
                    <div class="thumbnails-container">
                        @foreach($product->images as $index => $image)
                            <img class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                 src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->alt_text ?? $product->name }}"
                                 data-index="{{ $index }}">
                        @endforeach
                    </div>
                    <button class="nav-btn next-btn">›</button>
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
                @auth
                    @if($product->isInStock())
                        <button class="btn-add-to-cart" 
                                data-product-id="{{ $product->id }}" 
                                data-original-text="🛒 Dodaj do koszyka">
                            🛒 Dodaj do koszyka
                        </button>
                        <button class="btn-buy-now" 
                                data-product-id="{{ $product->id }}" 
                                data-original-text="⚡ Kup teraz">
                            ⚡ Kup teraz
                        </button>
                    @else
                        <button class="btn-notify" disabled>
                            🔔 Powiadom o dostępności
                        </button>
                    @endif
                    <button class="btn-wishlist" onclick="toggleWishlist({{ $product->id }})">
                        ❤️ Dodaj do ulubionych
                    </button>
                @else
                    @if($product->isInStock())
                        <button class="requires-auth btn-add-to-cart" 
                                data-action="add-to-cart" 
                                data-product-id="{{ $product->id }}" 
                                data-product-name="{{ $product->name }}">
                            🛒 Dodaj do koszyka
                        </button>
                        <button class="requires-auth btn-buy-now" 
                                data-action="buy-now" 
                                data-product-id="{{ $product->id }}" 
                                data-product-name="{{ $product->name }}">
                            ⚡ Kup teraz
                        </button>
                    @else
                        <button class="requires-auth btn-notify" 
                                data-action="notify-availability" 
                                data-product-id="{{ $product->id }}" 
                                data-product-name="{{ $product->name }}">
                            🔔 Powiadom o dostępności
                        </button>
                    @endif
                    <button class="requires-auth btn-wishlist" 
                            data-action="add-to-favorites" 
                            data-product-id="{{ $product->id }}" 
                            data-product-name="{{ $product->name }}">
                        ❤️ Dodaj do ulubionych
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Produkty powiązane -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
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
@endsection

@push('scripts')
<script>
// Lokalna funkcjonalność dla strony produktu
const productImages = @json($product->images->map(function($image) {
    return [
        'url' => asset('storage/' . $image->image_path),
        'alt' => $image->alt_text ?? $product->name
    ];
}));

// ProductGallery klasa do zarządzania lokalną galerią (BEZ INTERFEROWANIA Z MODALEM)
class ProductGallery {
    constructor(images) {
        this.images = images;
        this.currentIndex = 0;
        this.init();
    }

    init() {
        // Event listeners dla miniaturek
        const thumbnails = document.querySelectorAll('.product-gallery .thumbnail');
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.changeMainImage(index);
            });
        });

        // Event listeners dla przycisków nawigacji
        const prevBtn = document.querySelector('.product-gallery .prev-btn');
        const nextBtn = document.querySelector('.product-gallery .next-btn');

        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.previousImage();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.nextImage();
            });
        }

        // Obsługa klawiatury - TYLKO gdy modali NIE są otwarte
        document.addEventListener('keydown', (e) => {
            // Sprawdź czy jakikolwiek modal jest otwarty
            const imageModal = document.getElementById('imageModal');
            const authModal = document.getElementById('authModal');
            const isModalOpen = (imageModal && imageModal.style.display === 'flex') ||
                              (authModal && authModal.style.display === 'block');
            
            if (isModalOpen) return; // Jeśli modal otwarty, nie rób nic
            
            if (e.target.tagName.toLowerCase() === 'input' || 
                e.target.tagName.toLowerCase() === 'textarea') {
                return; // Nie przeszkadzaj w formularzach
            }

            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.previousImage();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.nextImage();
            }
        });
    }

    changeMainImage(index) {
        if (index < 0 || index >= this.images.length) return;
        
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = this.images[index].url;
            mainImage.alt = this.images[index].alt;
            this.currentIndex = index;
            
            // Update active thumbnail - TYLKO w product gallery
            document.querySelectorAll('.product-gallery .thumbnail').forEach((thumb, idx) => {
                thumb.classList.toggle('active', idx === index);
            });
        }
    }

    previousImage() {
        if (this.images.length <= 1) return;
        
        const newIndex = this.currentIndex > 0 ? this.currentIndex - 1 : this.images.length - 1;
        this.changeMainImage(newIndex);
    }

    nextImage() {
        if (this.images.length <= 1) return;
        
        const newIndex = this.currentIndex < this.images.length - 1 ? this.currentIndex + 1 : 0;
        this.changeMainImage(newIndex);
    }
}

function toggleWishlist(productId) {
    const button = event.target;
    const isInWishlist = button.classList.contains('in-wishlist');
    
    if (isInWishlist) {
        button.innerHTML = '❤️ Dodaj do ulubionych';
        button.classList.remove('in-wishlist');
        button.style.background = '';
        alert('Usunięto z ulubionych!');
    } else {
        button.innerHTML = '💖 W ulubionych';
        button.classList.add('in-wishlist');
        button.style.background = '#e74c3c';
        button.style.color = 'white';
        alert('Dodano do ulubionych!');
    }
    
    console.log('Toggle wishlist produkt ID:', productId);
}

// Inicjalizacja po załadowaniu DOM
document.addEventListener('DOMContentLoaded', function() {
    // Poczekaj na załadowanie global modal JS
    setTimeout(() => {
        if (productImages && productImages.length > 0) {
            window.productGallery = new ProductGallery(productImages);
            console.log('Product gallery initialized with', productImages.length, 'images');
        }
    }, 100);
});
</script>
@endpush