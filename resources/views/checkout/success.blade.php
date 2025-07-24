
@extends('layouts.app')

@section('title', 'Dziękujemy za zakup!')

@push('styles')
<style>
/* Success page styles - POPRAWIONY DESIGN */
.success-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
    background: var(--bg-primary);
    min-height: 80vh;
}

.success-header {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 3rem 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
    position: relative;
    overflow: hidden;
}

.success-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(45deg);
    animation: shine 3s ease-in-out infinite;
}

@keyframes shine {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
}

.success-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    animation: bounce 2s infinite;
    text-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-20px);
    }
    60% {
        transform: translateY(-10px);
    }
}

.success-header h1 {
    margin: 0 0 1rem 0;
    font-size: 2.5rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.success-header p {
    margin: 0;
    font-size: 1.2rem;
    opacity: 0.95;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

/* POPRAWIONE KONTENERY - CIEMNIEJSZE TŁO */
.info-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 25px var(--shadow-color);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.info-card h3 {
    margin: 0 0 1.5rem 0;
    color: var(--accent-primary);
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
}

.order-number {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: inline-block;
    box-shadow: 0 4px 15px var(--glow-color);
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.95rem;
}

.info-value {
    color: var(--text-primary);
    font-weight: 700;
    text-align: right;
    font-size: 0.95rem;
}

/* POPRAWIONE PRODUKTY */
.order-items {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 25px var(--shadow-color);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.order-items h3 {
    margin: 0 0 1.5rem 0;
    color: var(--accent-primary);
    font-size: 1.4rem;
    font-weight: 700;
}

.item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
}

.item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
}

.item-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    background: var(--bg-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.item-details {
    flex: 1;
}

.item-name {
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.item-meta {
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
}

.item-price {
    font-weight: 700;
    color: var(--accent-primary);
    font-size: 1.1rem;
}

/* POPRAWIONE KROKI */
.next-steps {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border: 2px solid var(--accent-primary);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.next-steps h3 {
    margin: 0 0 1.5rem 0;
    color: var(--accent-primary);
    text-align: center;
    font-size: 1.4rem;
    font-weight: 700;
}

.steps-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.steps-list li {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
    padding: 1.5rem;
    background: var(--bg-card);
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
    box-shadow: 0 4px 15px var(--shadow-color);
}

.steps-list li:hover {
    transform: translateX(5px);
    box-shadow: 0 8px 25px var(--shadow-color);
}

.step-number {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 2px 8px var(--glow-color);
}

.step-text {
    flex: 1;
    line-height: 1.6;
    color: var(--text-primary);
}

.step-text strong {
    color: var(--accent-primary);
    font-weight: 700;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

/* POPRAWIONE PRZYCISKI */
.btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    box-shadow: 0 4px 15px var(--glow-color);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--glow-color);
}

.btn-secondary {
    background: var(--bg-card);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
    box-shadow: 0 4px 15px var(--shadow-color);
}

.btn-secondary:hover {
    background: var(--bg-secondary);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-color);
}

.btn-outline {
    background: transparent;
    color: var(--accent-primary);
    border: 2px solid var(--accent-primary);
}

.btn-outline:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateY(-2px);
}

/* 🔥 POPRAWIONE BADGE STYLES - DOKŁADNIE JAK CHCESZ */
.badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payment-pending {
    background: linear-gradient(135deg, #2bfb24ff, #13f50bff);
    color: #087411ff;
    box-shadow: 0 2px 8px rgba(10, 79, 4, 0.3);
}

.payment-paid {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.payment-failed {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.payment-refunded {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
}

.status-pending {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #92400e;
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
}

.status-confirmed {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.status-processing {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.status-cancelled {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.status-shipped {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
}

.status-delivered {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

/* Dark mode improvements */
[data-theme="dark"] .info-card,
[data-theme="dark"] .order-items {
    background: rgba(20, 20, 20, 0.8);
    border-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .steps-list li {
    background: rgba(20, 20, 20, 0.8);
    border-color: rgba(255, 255, 255, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .success-header {
        padding: 2rem 1rem;
    }

    .success-header h1 {
        font-size: 2rem;
    }

    .success-icon {
        font-size: 3rem;
    }

    .order-info-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
        align-items: center;
    }

    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .info-value {
        text-align: left;
    }

    .item {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }

    .info-card,
    .order-items {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="success-container">
    <!-- Header sukcesu -->
    <div class="success-header">
        <div class="success-icon">🎉</div>
        <h1>Dziękujemy za zakup!</h1>
        <p>Twoje zamówienie zostało pomyślnie złożone i jest już przetwarzane</p>
    </div>

    <!-- Informacje o zamówieniu -->
    <div class="order-info-grid">
        <!-- Podstawowe informacje -->
        <div class="info-card">
            <h3>📋 Szczegóły zamówienia</h3>
            <div class="order-number">{{ $order->order_number }}</div>

            <div class="info-row">
                <span class="info-label">Data zamówienia:</span>
                <span class="info-value">{{ $order->created_at->format('d.m.Y H:i') }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    {{-- 🔥 HARDCODED OCZEKUJĄCY STATUS --}}
                    <span class="badge status-pending">⏳ Oczekujący</span>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Metoda płatności:</span>
                <span class="info-value">{{ $order->payment_method_name ?? 'Płatność kartą' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Status płatności:</span>
                <span class="info-value">
                    {{-- 🔥 HARDCODED POTWIERDZONA PŁATNOŚĆ --}}
                    @if($order->payment_method === 'cash_on_delivery')
                        <span class="badge payment-pending">💳 POTWIERDZONA</span>
                    @else
                        <span class="badge payment-paid">✅ Potwierdzona</span>
                    @endif
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Łączna kwota:</span>
                <span class="info-value">{{ number_format($order->total_amount, 2, ',', ' ') }} zł</span>
            </div>
        </div>

        <!-- Adres dostawy -->
        <div class="info-card">
            <h3>🚚 Adres dostawy</h3>

            <div class="info-row">
                <span class="info-label">Imię i nazwisko:</span>
                <span class="info-value">
                    @if($order->address)
                        {{ $order->address->first_name }} {{ $order->address->last_name }}
                    @else
                        {{ $order->first_name ?? '' }} {{ $order->last_name ?? '' }}
                    @endif
                </span>
            </div>

            @if($order->address)
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->address->email }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Telefon:</span>
                    <span class="info-value">{{ $order->address->phone }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Adres:</span>
                    <span class="info-value">{{ $order->address->address }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Miasto:</span>
                    <span class="info-value">{{ $order->address->postal_code }} {{ $order->address->city }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Kraj:</span>
                    <span class="info-value">{{ $order->address->country }}</span>
                </div>
            @else
                <!-- Fallback dla starych danych -->
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->email ?? auth()->user()->email }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Telefon:</span>
                    <span class="info-value">{{ $order->phone ?? 'Brak danych' }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Adres:</span>
                    <span class="info-value">{{ $order->address ?? 'Brak danych' }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Miasto:</span>
                    <span class="info-value">{{ $order->postal_code ?? '' }} {{ $order->city ?? '' }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Kraj:</span>
                    <span class="info-value">{{ $order->country ?? 'Polska' }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Lista produktów -->
    <div class="order-items">
        <h3>🛍️ Zamówione produkty ({{ $order->items->count() }} szt.)</h3>

        @foreach($order->items as $item)
            <div class="item">
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
                    <div class="item-meta">
                        Ilość: {{ $item->quantity }} szt. •
                        Cena za szt.: {{ number_format($item->price, 2, ',', ' ') }} zł
                    </div>
                </div>

                <div class="item-price">
                    {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} zł
                </div>
            </div>
        @endforeach
    </div>

    <!-- Kolejne kroki -->
    <div class="next-steps">
        <h3>📦 Co dalej?</h3>
        <ul class="steps-list">
            <li>
                <div class="step-number">1</div>
                <div class="step-text">
                    <strong>Potwierdzenie zamówienia</strong><br>
                    Otrzymasz email z potwierdzeniem zamówienia na adres:
                    {{ $order->address ? $order->address->email : ($order->email ?? auth()->user()->email) }}
                </div>
            </li>
            <li>
                <div class="step-number">2</div>
                <div class="step-text">
                    <strong>Przetwarzanie zamówienia</strong><br>
                    Nasz zespół przygotuje Twoje zamówienie do wysyłki w ciągu 1-2 dni roboczych
                </div>
            </li>
            <li>
                <div class="step-number">3</div>
                <div class="step-text">
                    <strong>Wysyłka</strong><br>
                    Otrzymasz informację o wysyłce wraz z numerem śledzenia przesyłki
                </div>
            </li>
            <li>
                <div class="step-number">4</div>
                <div class="step-text">
                    <strong>Dostawa</strong><br>
                    Zamówienie zostanie dostarczone na podany adres w ciągu 2-3 dni roboczych
                </div>
            </li>
        </ul>
    </div>

    <!-- Przyciski akcji -->
    <div class="action-buttons">
        <a href="{{ route('checkout.orders') }}" class="btn btn-primary">
            📋 Moje zamówienia
        </a>

        <a href="{{ route('checkout.order-details', $order->id) }}" class="btn btn-outline">
            👁️ Szczegóły zamówienia
        </a>

        <a href="{{ route('home') }}" class="btn btn-secondary">
            🏠 Powrót do sklepu
        </a>
    </div>

    <!-- Dodatkowe informacje -->
    <div class="info-card" style="margin-top: 2rem; text-align: center;">
        <h3>🎯 Potrzebujesz pomocy?</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Jeśli masz pytania dotyczące zamówienia, skontaktuj się z naszym zespołem obsługi klienta:</p>
        <div style="margin-top: 1rem;">
            <p style="color: var(--text-primary);"><strong>📧 Email:</strong> zamowienia@naszsklek.pl</p>
            <p style="color: var(--text-primary);"><strong>📞 Telefon:</strong> +48 123 456 789</p>
            <p style="color: var(--text-primary);"><strong>⏰ Godziny pracy:</strong> Pn-Pt: 8:00-18:00, Sb: 9:00-15:00</p>
        </div>
    </div>
</div>

<script>
// Pokazuj konfetti po załadowaniu strony
document.addEventListener('DOMContentLoaded', function() {
    // Prosta animacja konfetti
    setTimeout(() => {
        console.log('🎉 Zamówienie zostało złożone pomyślnie!');
    }, 500);

    // Animacja pojawienia się elementów
    const cards = document.querySelectorAll('.info-card, .order-items, .next-steps');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection
