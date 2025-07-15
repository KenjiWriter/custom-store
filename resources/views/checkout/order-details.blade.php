@extends('layouts.app')

@section('title', 'Szczegóły zamówienia - ' . $order->order_number)

@push('styles')
<style>
/* Order details styles */
.order-details-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.order-details-header {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    text-align: center;
}

.order-details-header h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
}

.order-details-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.details-card {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px var(--shadow-color);
}

.details-card h3 {
    margin: 0 0 1.5rem 0;
    color: var(--accent-primary);
    font-size: 1.3rem;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 0.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: var(--text-secondary);
}

.detail-value {
    color: var(--text-primary);
    text-align: right;
}

.items-section {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px var(--shadow-color);
    margin-bottom: 2rem;
}

.items-header {
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.items-header h3 {
    margin: 0;
    color: var(--accent-primary);
    font-size: 1.3rem;
}

.items-list {
    padding: 0;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item:hover {
    background: var(--bg-secondary);
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.item-placeholder {
    width: 80px;
    height: 80px;
    background: var(--bg-secondary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1.5rem;
    flex-shrink: 0;
    color: var(--text-secondary);
    font-size: 2rem;
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.item-sku {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.item-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.4;
}

.item-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
    text-align: right;
}

.item-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.item-quantity {
    font-size: 0.9rem;
    color: var(--text-secondary);
    background: var(--bg-secondary);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
}

.item-total {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
}

.order-summary {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px var(--shadow-color);
    margin-bottom: 2rem;
}

.order-summary h3 {
    margin: 0 0 1.5rem 0;
    color: var(--accent-primary);
    font-size: 1.3rem;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 0.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-row:last-child {
    border-bottom: none;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-top: 2px solid var(--border-color);
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--bg-secondary);
    color: var(--text-primary);
    text-decoration: none;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
    font-weight: 600;
}

.back-button:hover {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--shadow-color);
}

/* Status badges */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
}

.payment-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .item-image,
    .item-placeholder {
        margin-right: 0;
        align-self: center;
    }

    .item-meta {
        align-items: flex-start;
        text-align: left;
        width: 100%;
    }

    .summary-row,
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .detail-value {
        text-align: left;
    }
}
</style>
@endpush

@section('content')
<div class="order-details-container">
    <!-- Header -->
    <div class="order-details-header">
        <h1>📋 Szczegóły zamówienia</h1>
        <p>{{ $order->order_number }} - {{ $order->created_at->format('d.m.Y H:i') }}</p>
    </div>

    <!-- Szczegóły zamówienia -->
    <div class="details-grid">
        <!-- Informacje o zamówieniu -->
        <div class="details-card">
            <h3>📦 Informacje o zamówieniu</h3>

            <div class="detail-row">
                <span class="detail-label">Numer zamówienia:</span>
                <span class="detail-value">{{ $order->order_number }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Data zamówienia:</span>
                <span class="detail-value">{{ $order->created_at->format('d.m.Y H:i') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">{!! $order->status_badge !!}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status płatności:</span>
                <span class="detail-value">{!! $order->payment_status_badge !!}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Sposób płatności:</span>
                <span class="detail-value">{{ ucfirst($order->payment_method) }}</span>
            </div>

            @if($order->payment_date)
            <div class="detail-row">
                <span class="detail-label">Data płatności:</span>
                <span class="detail-value">{{ $order->payment_date->format('d.m.Y H:i') }}</span>
            </div>
            @endif
        </div>

        <!-- Dane dostawy -->
        <div class="details-card">
            <h3>🚚 Dane dostawy</h3>

            <div class="detail-row">
                <span class="detail-label">Imię i nazwisko:</span>
                <span class="detail-value">{{ $order->full_name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $order->email }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Telefon:</span>
                <span class="detail-value">{{ $order->phone }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Adres:</span>
                <span class="detail-value">{{ $order->full_address }}</span>
            </div>
        </div>
    </div>

    <!-- Lista produktów -->
    <div class="items-section">
        <div class="items-header">
            <h3>🛍️ Zamówione produkty ({{ $order->total_items_count }} szt.)</h3>
        </div>

        <div class="items-list">
            @foreach($order->items as $item)
                <div class="order-item">
                    @if($item->product && $item->product->primary_image_url)
                        <img src="{{ $item->product->primary_image_url }}"
                             alt="{{ $item->product->name }}"
                             class="item-image">
                    @else
                        <div class="item-placeholder">📷</div>
                    @endif

                    <div class="item-details">
                        <div class="item-name">
                            {{ $item->product ? $item->product->name : 'Produkt usunięty' }}
                        </div>

                        @if($item->product && $item->product->sku)
                            <div class="item-sku">SKU: {{ $item->product->sku }}</div>
                        @endif

                        @if($item->product && $item->product->description)
                            <div class="item-description">
                                {{ \Str::limit($item->product->description, 150) }}
                            </div>
                        @endif
                    </div>

                    <div class="item-meta">
                        <div class="item-price">{{ number_format($item->price, 2) }} zł</div>
                        <div class="item-quantity">{{ $item->quantity }} szt.</div>
                        <div class="item-total">{{ number_format($item->price * $item->quantity, 2) }} zł</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Podsumowanie -->
    <div class="order-summary">
        <h3>💰 Podsumowanie zamówienia</h3>

        <div class="summary-row">
            <span>Wartość produktów:</span>
            <span>{{ $order->formatted_total_amount }}</span>
        </div>

        <div class="summary-row">
            <span>Dostawa:</span>
            <span>Bezpłatna</span>
        </div>

        <div class="summary-row">
            <span>Łączna kwota:</span>
            <span>{{ $order->formatted_total_amount }}</span>
        </div>
    </div>

    <!-- Przycisk powrotu -->
    <a href="{{ route('checkout.orders') }}" class="back-button">
        ← Powrót do listy zamówień
    </a>
</div>
@endsection
