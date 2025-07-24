@extends('layouts.app')

@section('title', 'Finalizacja zamówienia')

@push('styles')
<style>
/* Buy Now checkout styles - SKOPIOWANE Z BUY-NOW */
.checkout-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
    background: var(--bg-primary);
}

.checkout-container h1 {
    text-align: center;
    color: var(--accent-primary);
    margin-bottom: 3rem;
    font-size: 2.5rem;
    font-weight: 800;
    text-shadow: 0 2px 4px var(--shadow-color);
}

/* 🔥 GŁÓWNY LAYOUT - 2 KOLUMNY */
.checkout-layout {
    display: grid;
    grid-template-columns: 2fr 1fr; /* Formularz większy, podsumowanie mniejsze */
    gap: 3rem;
    align-items: start;
}

/* LEWA KOLUMNA - FORMULARZ */
.checkout-form {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
}

.form-section {
    margin-bottom: 2.5rem;
}

.form-section h3 {
    margin: 0 0 2rem 0;
    font-size: 1.4rem;
    color: var(--text-primary);
    padding-bottom: 0.75rem;
    border-bottom: 3px solid var(--accent-primary);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1.5rem;
}

.form-group label {
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.05rem;
}

.form-group input,
.form-group select {
    padding: 1rem;
    border: 2px solid var(--border-light);
    border-radius: 10px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 1.05rem;
    transition: all 0.3s ease;
    min-height: 50px;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 4px rgba(var(--accent-primary-rgb), 0.1);
    transform: translateY(-2px);
}

.error {
    color: #e74c3c;
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

/* Payment methods bez marginesów */
.payment-methods {
    display: grid;
    gap: 1rem;
    margin-bottom: 0;
}

.payment-option {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: var(--bg-primary);
    border: 2px solid var(--border-light);
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 70px;
}

.payment-option:hover {
    border-color: var(--accent-primary);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.payment-option input[type="radio"] {
    margin-right: 1.5rem;
    transform: scale(1.4);
}

.payment-option input[type="radio"]:checked + .payment-label {
    color: var(--accent-primary);
    font-weight: 700;
}

.payment-label {
    font-size: 1.2rem;
    color: var(--text-primary);
}

/* Payment details bezpośrednio pod metodami */
.payment-details {
    margin-top: 1rem;
    padding: 2rem;
    background: var(--bg-secondary);
    border-radius: 15px;
    border-left: 5px solid var(--accent-primary);
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.payment-details h4 {
    margin: 0 0 1.5rem 0;
    color: var(--accent-primary);
    font-size: 1.2rem;
}

.card-number-input {
    font-family: 'Courier New', monospace;
    font-size: 1.3rem;
    letter-spacing: 0.1rem;
}

.blik-input {
    font-family: 'Courier New', monospace;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 0.3rem;
    text-align: center;
}

.blik-instructions {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
}

.form-actions {
    display: flex;
    gap: 1.5rem;
    justify-content: space-between;
    align-items: center;
    margin-top: 3rem;
    padding-top: 2.5rem;
    border-top: 2px solid var(--border-light);
}

.btn {
    padding: 1.25rem 2.5rem;
    border: none;
    border-radius: 15px;
    font-size: 1.15rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    min-height: 55px;
}

.btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border: 2px solid var(--border-light);
}

.btn-secondary:hover {
    background: var(--bg-secondary);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    box-shadow: 0 6px 20px rgba(var(--accent-primary-rgb), 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(var(--accent-primary-rgb), 0.4);
}

/* Order Summary Sidebar */
.order-summary {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
    position: sticky;
    top: 2rem; /* Sticky positioning */
    max-height: calc(100vh - 4rem);
    overflow-y: auto;
}

.order-summary h3 {
    color: var(--accent-primary);
    margin-bottom: 2rem;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    border-bottom: 2px solid var(--accent-primary);
    padding-bottom: 1rem;
}

.address-selection {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.address-option {
    position: relative;
}

.address-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.address-card {
    display: block;
    padding: 1.5rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    background: var(--bg-primary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.address-card:hover {
    border-color: var(--accent-primary);
    background: var(--bg-card);
}

.address-option input[type="radio"]:checked + .address-card {
    border-color: var(--accent-primary);
    background: linear-gradient(135deg, rgba(118, 75, 162, 0.05) 0%, transparent 100%);
    box-shadow: 0 4px 15px var(--glow-color);
}

.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.address-badge {
    background: var(--accent-primary);
    color: white;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.address-details {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.4;
}

.new-address {
    border-style: dashed;
    text-align: center;
}

.new-address .address-header {
    justify-content: center;
    color: var(--accent-primary);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    cursor: pointer;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.checkbox-label:hover {
    background: var(--bg-card);
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: var(--accent-primary);
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    flex-shrink: 0;
}

.summary-image-placeholder {
    width: 80px;
    height: 80px;
    background: var(--bg-secondary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--text-secondary);
    flex-shrink: 0;
}

.summary-details {
    flex: 1;
    min-width: 0;
}

.summary-name {
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 1rem;
    line-height: 1.4;
}

.summary-quantity {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.summary-price {
    font-weight: 700;
    color: var(--accent-primary);
    font-size: 1.1rem;
    text-align: right;
    flex-shrink: 0;
}

/* PODSUMOWANIE FINANSOWE */
.summary-total {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--border-color);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.summary-row:last-child {
    margin-bottom: 0;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--accent-primary);
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.security-notice {
    background: rgba(var(--accent-primary-rgb), 0.1);
    border: 1px solid rgba(var(--accent-primary-rgb), 0.3);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
    text-align: center;
}

.security-notice h4 {
    margin: 0 0 0.5rem 0;
    color: var(--accent-primary);
    font-size: 0.9rem;
}

.security-notice p {
    margin: 0;
    font-size: 0.8rem;
    color: var(--text-secondary);
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 768px) {
    .checkout-layout {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .checkout-form {
        padding: 2rem;
    }

    .order-summary {
        position: static;
        order: -1;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .checkout-container h1 {
        font-size: 2rem;
    }
}
</style>
@endpush

@section('content')
<div class="checkout-container">
    <h1>🛒 Finalizacja zamówienia</h1>

    {{-- 🔥 GŁÓWNY LAYOUT 2-KOLUMNOWY --}}
    <div class="checkout-layout">

        {{-- LEWA KOLUMNA - FORMULARZ --}}
        <div class="checkout-form">
            <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
                @csrf

                {{-- 🔥 SEKCJA WYBORU ADRESU (PIERWSZA) --}}
                @if($userAddresses->count() > 0)
                <div class="form-section">
                    <h3>📍 Wybierz adres dostawy</h3>

                    <div class="address-selection">
                        @foreach($userAddresses as $address)
                            <div class="address-option">
                                <input type="radio"
                                       name="selected_address_id"
                                       id="address_{{ $address->id }}"
                                       value="{{ $address->id }}"
                                       {{ $defaultAddress && $defaultAddress->id === $address->id ? 'checked' : '' }}>

                                <label for="address_{{ $address->id }}" class="address-card">
                                    <div class="address-header">
                                        <strong>{{ $address->first_name }} {{ $address->last_name }}</strong>
                                        @if($address->is_default)
                                            <span class="address-badge">Domyślny</span>
                                        @endif
                                    </div>
                                    <div class="address-details">
                                        {{ $address->address }}<br>
                                        {{ $address->postal_code }} {{ $address->city }}<br>
                                        📧 {{ $address->email }} | 📞 {{ $address->phone }}
                                    </div>
                                </label>
                            </div>
                        @endforeach

                        {{-- Opcja nowego adresu --}}
                        <div class="address-option">
                            <input type="radio" name="selected_address_id" id="new_address" value="new">
                            <label for="new_address" class="address-card new-address">
                                <div class="address-header">
                                    <strong>➕ Dodaj nowy adres</strong>
                                </div>
                                <div class="address-details">
                                    Użyj nowego adresu dostawy
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                {{-- 🔥 FORMULARZ DANYCH (POKAZUJ WARUNKOWO) --}}
                <div class="form-section" id="address-form" style="{{ $userAddresses->count() > 0 ? 'display: none;' : '' }}">
                    <h3>📍 Dane dostawy</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Imię *</label>
                            <input type="text" name="first_name" id="first_name" required
                                   value="{{ old('first_name', $defaultAddress->first_name ?? $user->first_name ?? '') }}">
                            @error('first_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="last_name">Nazwisko *</label>
                            <input type="text" name="last_name" id="last_name" required
                                   value="{{ old('last_name', $defaultAddress->last_name ?? $user->last_name ?? '') }}">
                            @error('last_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email', $defaultAddress->email ?? $user->email) }}">
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon *</label>
                            <input type="tel" name="phone" id="phone" required
                                   value="{{ old('phone', $defaultAddress->phone ?? '') }}">
                            @error('phone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Adres *</label>
                        <input type="text" name="address" id="address" required
                               value="{{ old('address', $defaultAddress->address ?? '') }}"
                               placeholder="ul. Przykładowa 123/45">
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="postal_code">Kod pocztowy *</label>
                            <input type="text" name="postal_code" id="postal_code" required
                                   value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}"
                                   placeholder="00-000">
                            @error('postal_code')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city">Miasto *</label>
                            <input type="text" name="city" id="city" required
                                   value="{{ old('city', $defaultAddress->city ?? '') }}">
                            @error('city')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country">Kraj</label>
                        <select name="country" id="country">
                            <option value="Polska" {{ old('country', $defaultAddress->country ?? 'Polska') == 'Polska' ? 'selected' : '' }}>Polska</option>
                            <option value="Czechy" {{ old('country') == 'Czechy' ? 'selected' : '' }}>Czechy</option>
                            <option value="Słowacja" {{ old('country') == 'Słowacja' ? 'selected' : '' }}>Słowacja</option>
                            <option value="Niemcy" {{ old('country') == 'Niemcy' ? 'selected' : '' }}>Niemcy</option>
                        </select>
                        @error('country')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 🔥 OPCJA ZAPISU JAKO DOMYŚLNY --}}
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="save_as_default" value="1"
                                   {{ old('save_as_default') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Zapisz jako domyślny adres
                        </label>
                    </div>
                </div>

                {{-- METODA PŁATNOŚCI --}}
                <div class="form-section">
                    <h3>💳 Metoda płatności</h3>

                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cash_on_delivery" {{ old('payment_method', 'cash_on_delivery') == 'cash_on_delivery' ? 'checked' : '' }}>
                            <span class="payment-label">🚚 Płatność przy odbiorze</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="card" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                            <span class="payment-label">💳 Karta płatnicza</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="blik" {{ old('payment_method') == 'blik' ? 'checked' : '' }}>
                            <span class="payment-label">📱 BLIK</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="transfer" {{ old('payment_method') == 'transfer' ? 'checked' : '' }}>
                            <span class="payment-label">🏦 Przelew bankowy</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                            <span class="payment-label">🅿️ PayPal</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    {{-- Formularz karty --}}
                    <div class="payment-details" id="card-details" style="display: none;">
                        <h4>💳 Dane karty płatniczej</h4>

                        <div class="form-group">
                            <label for="card_number">Numer karty *</label>
                            <input type="text" id="card_number" name="card_number" class="card-number-input"
                                   placeholder="1234 5678 9012 3456" maxlength="19">
                            @error('card_number')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry_date">Data ważności *</label>
                                <input type="text" id="expiry_date" name="expiry_date"
                                       placeholder="MM/RR" maxlength="5">
                                @error('expiry_date')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV *</label>
                                <input type="text" id="cvv" name="cvv"
                                       placeholder="123" maxlength="4">
                                @error('cvv')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="card_holder">Imię i nazwisko na karcie *</label>
                            <input type="text" id="card_holder" name="card_holder"
                                   placeholder="Jak na karcie">
                            @error('card_holder')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Formularz BLIK --}}
                    <div class="payment-details" id="blik-details" style="display: none;">
                        <h4>📱 Płatność BLIK</h4>

                        <div class="blik-instructions">
                            <h4>📱 Jak wygenerować kod BLIK:</h4>
                            <ol style="text-align: left; margin: 1rem 0; padding-left: 2rem;">
                                <li>Otwórz aplikację swojego banku</li>
                                <li>Znajdź funkcję BLIK</li>
                                <li>Wygeneruj 6-cyfrowy kod</li>
                                <li>Wpisz kod poniżej</li>
                            </ol>
                            <p><small>Kod BLIK jest ważny przez 2 minuty</small></p>
                        </div>

                        <div class="form-group">
                            <label for="blik_code">Kod BLIK (6 cyfr) *</label>
                            <input type="text" id="blik_code" name="blik_code" class="blik-input"
                                   placeholder="123456" maxlength="6">
                            @error('blik_code')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Przyciski --}}
                <div class="form-actions">
                    <a href="{{ route('cart.index') }}" class="btn btn-secondary">← Powrót do koszyka</a>
                    <button type="submit" class="btn btn-primary" id="submitButton">
                        💳 Złóż zamówienie ({{ number_format($cartTotal, 2, ',', ' ') }} zł)
                    </button>
                </div>
            </form>
        </div>

        {{-- PRAWA KOLUMNA - PODSUMOWANIE ZAMÓWIENIA --}}
        <div class="order-summary">
            <h3>📋 Podsumowanie zamówienia</h3>

            {{-- Produkty z koszyka --}}
            @foreach($cartItems as $item)
                <div class="summary-item">
                    @if($item->product && $item->product->primary_image_url)
                        <img src="{{ $item->product->primary_image_url }}"
                             alt="{{ $item->product->name }}"
                             class="summary-image">
                    @else
                        <div class="summary-image-placeholder">📦</div>
                    @endif

                    <div class="summary-details">
                        <div class="summary-name">
                            {{ $item->product ? $item->product->name : 'Produkt usunięty' }}
                        </div>
                        <div class="summary-quantity">Ilość: {{ $item->quantity }} szt.</div>
                    </div>

                    <div class="summary-price">
                        {{ number_format($item->line_total ?? 0, 2, ',', ' ') }} zł
                    </div>
                </div>
            @endforeach

            {{-- Podsumowanie finansowe --}}
            <div class="summary-total">
                <div class="summary-row">
                    <span>Wartość produktów ({{ $cartItems->count() }} szt.):</span>
                    <span>{{ number_format($cartTotal, 2, ',', ' ') }} zł</span>
                </div>

                <div class="summary-row">
                    <span>Dostawa:</span>
                    <span>Bezpłatna</span>
                </div>

                <div class="summary-row">
                    <span>Łącznie:</span>
                    <span>{{ number_format($cartTotal, 2, ',', ' ') }} zł</span>
                </div>
            </div>

            {{-- Informacja o bezpieczeństwie --}}
            <div class="security-notice">
                <h4>🔒 Bezpieczne zamówienie</h4>
                <p>Twoje dane są chronione szyfrowaniem SSL. Gwarantujemy bezpieczeństwo Twoich danych osobowych.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obsługa wyboru metody płatności
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('card-details');
    const blikDetails = document.getElementById('blik-details');
    const submitButton = document.getElementById('submitButton');

    function updatePaymentForm() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) return;

        // Ukryj wszystkie formularze
        cardDetails.style.display = 'none';
        blikDetails.style.display = 'none';

        // Usuń required z wszystkich pól
        document.querySelectorAll('.payment-details input').forEach(input => {
            input.removeAttribute('required');
        });

        // Pokaż odpowiedni formularz i ustaw required
        switch (selectedMethod.value) {
            case 'card':
                cardDetails.style.display = 'block';
                document.querySelectorAll('#card-details input').forEach(input => {
                    input.setAttribute('required', 'required');
                });
                submitButton.innerHTML = '💳 Zapłać kartą ({{ number_format($cartTotal, 2, ",", " ") }} zł)';
                break;
            case 'blik':
                blikDetails.style.display = 'block';
                document.getElementById('blik_code').setAttribute('required', 'required');
                submitButton.innerHTML = '📱 Zapłać BLIK ({{ number_format($cartTotal, 2, ",", " ") }} zł)';
                break;
            case 'paypal':
                submitButton.innerHTML = '🅿️ Zapłać PayPal ({{ number_format($cartTotal, 2, ",", " ") }} zł)';
                break;
            case 'transfer':
                submitButton.innerHTML = '🏦 Zapłać przelewem ({{ number_format($cartTotal, 2, ",", " ") }} zł)';
                break;
            case 'cash_on_delivery':
                submitButton.innerHTML = '📦 Złóż zamówienie ({{ number_format($cartTotal, 2, ",", " ") }} zł)';
                break;
            default:
                submitButton.innerHTML = '💳 Złóż zamówienie ({{ number_format($cartTotal, 2, ",", " ") }} zł)';
        }
    }

    // Event listenery dla radio buttons
    paymentMethods.forEach(method => {
        method.addEventListener('change', updatePaymentForm);
    });

    // Inicjalna aktualizacja
    updatePaymentForm();

    // Formatowanie numeru karty
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            if (formattedValue.length > 19) formattedValue = formattedValue.substring(0, 19);
            e.target.value = formattedValue;
        });
    }

    // Formatowanie daty ważności
    const expiryInput = document.getElementById('expiry_date');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    // CVV tylko cyfry
    const cvvInput = document.getElementById('cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }

    // BLIK kod tylko cyfry
    const blikInput = document.getElementById('blik_code');
    if (blikInput) {
        blikInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }

    // Formatowanie kodu pocztowego
    const postalCode = document.getElementById('postal_code');
    if (postalCode) {
        postalCode.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '-' + value.substring(2, 5);
            }
            e.target.value = value;
        });
    }

    // Obsługa formularza
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        submitButton.disabled = true;
        submitButton.innerHTML = '⏳ Przetwarzanie...';
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const addressOptions = document.querySelectorAll('input[name="selected_address_id"]');
    const addressForm = document.getElementById('address-form');

    // Obsługa wyboru adresu
    addressOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.value === 'new') {
                // Pokaż formularz nowego adresu
                addressForm.style.display = 'block';
                // Wyczyść pola
                addressForm.querySelectorAll('input').forEach(input => {
                    if (input.type !== 'checkbox') {
                        input.value = '';
                    }
                });
            } else {
                // Ukryj formularz i wypełnij danymi wybranego adresu
                addressForm.style.display = 'none';
                fillAddressForm(this.value);
            }
        });
    });

    function fillAddressForm(addressId) {
        // Znajdź dane adresu i wypełnij formularz
        const addresses = @json($userAddresses);
        const selectedAddress = addresses.find(addr => addr.id == addressId);

        if (selectedAddress) {
            document.getElementById('first_name').value = selectedAddress.first_name;
            document.getElementById('last_name').value = selectedAddress.last_name;
            document.getElementById('email').value = selectedAddress.email;
            document.getElementById('phone').value = selectedAddress.phone;
            document.getElementById('address').value = selectedAddress.address;
            document.getElementById('postal_code').value = selectedAddress.postal_code;
            document.getElementById('city').value = selectedAddress.city;
            document.getElementById('country').value = selectedAddress.country;
        }
    }
});
</script>
@endsection
