@extends('layouts.app')

@section('title', $product->name . ' - Nasz Sklep')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-show.css') }}">
<link rel="stylesheet" href="{{ asset('css/image-gallery-modal.css') }}">
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
                        <button class="btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                            🛒 Dodaj do koszyka
                        </button>
                        <button class="btn-buy-now" onclick="buyNow({{ $product->id }})">
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
                        <button class="requires-auth btn-add-to-cart" data-action="add-to-cart">
                            🛒 Dodaj do koszyka
                        </button>
                        <button class="requires-auth btn-buy-now" data-action="buy-now">
                            ⚡ Kup teraz
                        </button>
                    @else
                        <button class="btn-notify" disabled>
                            🔔 Powiadom o dostępności
                        </button>
                    @endif
                    <button class="requires-auth btn-wishlist" data-action="add-to-favorites">
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

<!-- Użyj komponentu image gallery modal -->
<x-image-gallery-modal />
@endsection

@push('scripts')
<script src="{{ asset('js/image-gallery-modal.js') }}"></script>
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

        // Obsługa klawiatury - TYLKO gdy modal NIE jest otwarty
        document.addEventListener('keydown', (e) => {
            // Sprawdź czy modal jest otwarty
            const modal = document.getElementById('imageModal');
            const isModalOpen = modal && modal.style.display === 'flex';

            if (isModalOpen) return; // Jeśli modal otwarty, nie rób nic

            if (e.target.tagName.toLowerCase() === 'input' ||
                e.target.tagName.toLowerCase() === 'textarea') {
                return; // Nie przeszkadzaj w formularzach
            }

            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.previousImage();
            }
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.nextImage();
            }
        });
    }

    changeMainImage(index) {
        if (index < 0 || index >= this.images.length) return;

        this.currentIndex = index;
        const mainImage = document.getElementById('mainImage');
        const thumbnails = document.querySelectorAll('.product-gallery .thumbnail');

        if (mainImage && this.images[index]) {
            mainImage.src = this.images[index].url;
            mainImage.alt = this.images[index].alt;
        }

        // Aktualizuj active class na miniaturkach
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });
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

// Funkcje akcji produktu
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
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '✅ Dodano!';
            button.style.background = '#27ae60';

            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
            }, 2000);
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
                button.style.background = '#e74c3c';
                button.style.color = 'white';
            } else {
                button.innerHTML = '❤️ Dodaj do ulubionych';
                button.classList.remove('in-wishlist');
                button.style.background = '';
                button.style.color = '';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Wystąpił błąd');
    });
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
