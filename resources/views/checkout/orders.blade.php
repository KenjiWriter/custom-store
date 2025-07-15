@extends('layouts.app')

@section('title', 'Moje zamówienia')

@push('styles')
<style>
/* Orders page styles */
.orders-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.orders-header {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    text-align: center;
}

.orders-header h1 {
    margin: 0 0 1rem 0;
    font-size: 2.5rem;
}

.orders-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 12px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.orders-filters {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px var(--shadow-color);
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-weight: 600;
    color: var(--text-primary);
}

.filter-group select,
.filter-group input {
    padding: 0.75rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.filter-group select:focus,
.filter-group input:focus {
    border-color: var(--accent-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-filter {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--shadow-color);
}

.btn-clear {
    background: var(--bg-secondary);
    color: var(--text-secondary);
    border: 2px solid var(--border-color);
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-clear:hover {
    border-color: var(--accent-primary);
    color: var(--accent-primary);
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.order-card {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px var(--shadow-color);
}

.order-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px var(--shadow-color);
    border-color: var(--accent-primary);
}

.order-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.order-info {
    flex: 1;
}

.order-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 0.5rem;
}

.order-date {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.order-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.order-total {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
}

.status-badges {
    display: flex;
    gap: 0.5rem;
}

.status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #d1fae5; color: #065f46; }
.status-processing { background: #dbeafe; color: #1e40af; }
.status-shipped { background: #e0e7ff; color: #3730a3; }
.status-delivered { background: #dcfce7; color: #166534; }
.status-cancelled { background: #fee2e2; color: #991b1b; }
.status-returned { background: #fef3c7; color: #92400e; }

.payment-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.payment-pending { background: #fef3c7; color: #92400e; }
.payment-paid { background: #d1fae5; color: #065f46; }
.payment-failed { background: #fee2e2; color: #991b1b; }
.payment-refunded { background: #e0e7ff; color: #3730a3; }

.order-items {
    padding: 1.5rem;
}

.order-items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.order-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.order-item:hover {
    transform: scale(1.05);
}

.order-item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
    margin-bottom: 0.5rem;
}

.order-item-name {
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.order-item-quantity {
    font-size: 0.7rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.order-item-price {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.order-actions {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color);
    background: var(--bg-secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-view-details {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view-details:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--shadow-color);
}

.empty-orders {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    box-shadow: 0 4px 20px var(--shadow-color);
}

.empty-orders h3 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.empty-orders p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* Pagination styles */
.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .orders-header h1 {
        font-size: 2rem;
    }

    .orders-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .filters-grid {
        grid-template-columns: 1fr;
    }

    .order-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-meta {
        align-items: flex-start;
        width: 100%;
    }

    .order-items-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    }

    .order-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>
@endpush

@section('content')
<div class="orders-container">
    <!-- Header ze statystykami -->
    <div class="orders-header">
        <h1>📋 Moje zamówienia</h1>
        <p>Historia wszystkich Twoich zamówień w jednym miejscu</p>

        <div class="orders-stats">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_orders'] }}</div>
                <div class="stat-label">Łączna liczba zamówień</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($stats['total_spent'], 2) }} zł</div>
                <div class="stat-label">Łączna kwota wydana</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['pending_orders'] }}</div>
                <div class="stat-label">Zamówienia oczekujące</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['delivered_orders'] }}</div>
                <div class="stat-label">Zamówienia dostarczone</div>
            </div>
        </div>
    </div>

    <!-- Filtry -->
    <div class="orders-filters">
        <form method="GET" action="{{ route('checkout.orders') }}">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="status">Status zamówienia</label>
                    <select name="status" id="status">
                        <option value="">Wszystkie statusy</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Oczekuje</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>✅ Potwierdzone</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>📦 Przetwarzane</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>🚚 Wysłane</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>📮 Dostarczone</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Anulowane</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="date_from">Data od</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="filter-group">
                    <label for="date_to">Data do</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
                </div>

                <div class="filter-group">
                    <label for="sort">Sortowanie</label>
                    <select name="sort" id="sort">
                        <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Data zamówienia</option>
                        <option value="total_amount" {{ request('sort') === 'total_amount' ? 'selected' : '' }}>Kwota</option>
                        <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">🔍 Filtruj</button>
                    <a href="{{ route('checkout.orders') }}" class="btn-clear">🔄 Wyczyść</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista zamówień -->
    @if($orders->count() > 0)
        <div class="orders-list">
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <div class="order-number">{{ $order->order_number }}</div>
                            <div class="order-date">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                        </div>

                        <div class="order-meta">
                            <div class="order-total">{{ $order->formatted_total_amount }}</div>
                            <div class="status-badges">
                                {!! $order->status_badge !!}
                                {!! $order->payment_status_badge !!}
                            </div>
                        </div>
                    </div>

                    <div class="order-items">
                        <div class="order-items-grid">
                            @foreach($order->items->take(6) as $item)
                                <div class="order-item">
                                    @if($item->product && $item->product->primary_image_url)
                                        <img src="{{ $item->product->primary_image_url }}"
                                             alt="{{ $item->product->name }}"
                                             class="order-item-image">
                                    @else
                                        <div class="order-item-image" style="background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                                            📷
                                        </div>
                                    @endif

                                    <div class="order-item-name">
                                        {{ $item->product ? \Str::limit($item->product->name, 30) : 'Produkt usunięty' }}
                                    </div>
                                    <div class="order-item-quantity">{{ $item->quantity }} szt.</div>
                                    <div class="order-item-price">{{ number_format($item->price, 2) }} zł</div>
                                </div>
                            @endforeach

                            @if($order->items->count() > 6)
                                <div class="order-item">
                                    <div class="order-item-image" style="background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-size: 1.5rem;">
                                        +{{ $order->items->count() - 6 }}
                                    </div>
                                    <div class="order-item-name">więcej produktów</div>
                                </div>
                            @endif
                        </div>

                        <p><strong>Produkty:</strong> {{ $order->total_items_count }} szt. | <strong>Sposób płatności:</strong> {{ ucfirst($order->payment_method) }}</p>
                    </div>

                    <div class="order-actions">
                        <div>
                            <small class="text-muted">Zamówienie z {{ $order->created_at->diffForHumans() }}</small>
                        </div>
                        <a href="{{ route('checkout.order.details', $order) }}" class="btn-view-details">
                            👁️ Zobacz szczegóły
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacja -->
        @if($orders->hasPages())
            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <!-- Pusta lista zamówień -->
        <div class="empty-orders">
            <h3>📦 Brak zamówień</h3>
            <p>Nie masz jeszcze żadnych zamówień. Przeglądaj nasz sklep i złóż pierwsze zamówienie!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                🛍️ Rozpocznij zakupy
            </a>
        </div>
    @endif
</div>
@endsection
