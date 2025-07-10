@extends('layouts.app')

@section('title', 'Moje ulubione produkty - Nasz Sklep')

@push('styles')
<style>
    .wishlist-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem 0;
        background: var(--bg-card);
        border-radius: 20px;
        border: 1px solid var(--border-color);
    }

    .wishlist-header h1 {
        font-size: 2.5rem;
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-weight: 800;
    }

    .wishlist-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 1.5rem;
    }

    .stat-item {
        background: var(--bg-secondary);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        text-align: center;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--accent-primary);
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .empty-wishlist {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-card);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        margin: 2rem 0;
    }

    .empty-wishlist h3 {
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .empty-wishlist p {
        color: var(--text-secondary);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .wishlist-product-card {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .wishlist-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px var(--shadow-color);
    }

    .wishlist-product-image {
        width: 100%;
        height: 200px;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1rem;
        position: relative;
        cursor: pointer;
    }

    .wishlist-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .wishlist-product-image:hover img {
        transform: scale(1.05);
    }

    .wishlist-no-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--bg-secondary), var(--bg-card));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .wishlist-product-info h3 {
        font-size: 1.3rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .wishlist-product-info h3 a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .wishlist-product-info h3 a:hover {
        color: var(--accent-primary);
    }

    .wishlist-product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--accent-primary);
        margin: 1rem 0;
    }

    .wishlist-product-description {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .wishlist-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .wishlist-actions .btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-remove-wishlist {
        background: #dc3545;
        color: white;
    }

    .btn-remove-wishlist:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    .btn-view-product {
        background: var(--accent-primary);
        color: white;
    }

    .btn-view-product:hover {
        background: var(--accent-secondary);
        transform: translateY(-2px);
    }

    .wishlist-date {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--bg-secondary);
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        font-size: 0.8rem;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
    }

    .pagination-wrapper {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .wishlist-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .wishlist-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .wishlist-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Header z statystykami -->
    <div class="wishlist-header">
        <h1>💖 Moje ulubione produkty</h1>
        <p>Tutaj znajdziesz wszystkie produkty które dodałeś do ulubionych</p>

        <div class="wishlist-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $favoriteProducts->total() }}</div>
                <div class="stat-label">Ulubionych produktów</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $favoriteProducts->currentPage() }}</div>
                <div class="stat-label">Strona</div>
            </div>
        </div>
    </div>

    @if($favoriteProducts->count() > 0)
        <!-- Siatka ulubionych produktów -->
        <div class="wishlist-grid">
            @foreach($favoriteProducts as $product)
                <div class="wishlist-product-card" data-product-id="{{ $product->id }}">
                    <!-- Data dodania -->
                    <div class="wishlist-date">
                        {{ $product->pivot->created_at->format('d.m.Y') }}
                    </div>

                    <!-- Zdjęcie produktu -->
                    @if($product->primaryImage)
                        <div class="wishlist-product-image"
                             onclick="openProductImageModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->formatted_price }}', '{{ route('products.show', $product->id) }}')">
                            <img src="{{ $product->primary_image_url }}"
                                 alt="{{ $product->name }}">
                        </div>
                    @else
                        <div class="wishlist-no-image">📷</div>
                    @endif

                    <!-- Informacje o produkcie -->
                    <div class="wishlist-product-info">
                        <h3>
                            <a href="{{ route('products.show', $product->id) }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="wishlist-product-price">{{ $product->formatted_price }}</div>
                        <div class="wishlist-product-description">
                            {{ Str::limit($product->description, 100) }}
                        </div>
                    </div>

                    <!-- Akcje -->
                    <div class="wishlist-actions">
                        <button class="btn btn-remove-wishlist"
                                onclick="removeFromWishlist({{ $product->id }}, this)">
                            ❌ Usuń z ulubionych
                        </button>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-view-product">
                            👁️ Zobacz produkt
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacja -->
        @if($favoriteProducts->hasPages())
            <div class="pagination-wrapper">
                {{ $favoriteProducts->links() }}
            </div>
        @endif
    @else
        <!-- Pusta lista ulubionych -->
        <div class="empty-wishlist">
            <h3>💔 Brak ulubionych produktów</h3>
            <p>Nie masz jeszcze żadnych ulubionych produktów. Przeglądaj nasz sklep i dodawaj produkty do ulubionych!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                🛍️ Przeglądaj produkty
            </a>
        </div>
    @endif
</div>

<script>
// Funkcja usuwania z ulubionych
async function removeFromWishlist(productId, button) {
    try {
        button.disabled = true;
        button.innerHTML = '⏳ Usuwanie...';

        const response = await fetch(`/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.success) {
            // Animacja usunięcia
            const card = button.closest('.wishlist-product-card');
            card.style.transform = 'scale(0.9)';
            card.style.opacity = '0';

            setTimeout(() => {
                card.remove();

                // Sprawdź czy zostały jakieś produkty
                const remainingCards = document.querySelectorAll('.wishlist-product-card');
                if (remainingCards.length === 0) {
                    location.reload(); // Przeładuj żeby pokazać "empty" widok
                }
            }, 300);

            // Pokaż powiadomienie
            showNotification(data.message, 'success');

            // Aktualizuj licznik w navbar
            const counter = document.getElementById('wishlistCounter');
            if (counter) {
                counter.textContent = data.wishlist_count;
                if (data.wishlist_count === 0) {
                    counter.style.display = 'none';
                }
            }
        } else {
            button.disabled = false;
            button.innerHTML = '❌ Usuń z ulubionych';
            showNotification(data.message, 'error');
        }
    } catch (error) {
        console.error('Błąd usuwania z ulubionych:', error);
        button.disabled = false;
        button.innerHTML = '❌ Usuń z ulubionych';
        showNotification('Wystąpił błąd podczas usuwania z ulubionych', 'error');
    }
}

// Prosta funkcja powiadomień
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        z-index: 10000;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: slideInRight 0.3s ease;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease forwards';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Dodaj style animacji
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endsection
