@extends('layouts.app')

@section('title', 'Szybkie zakupy - ' . $product->name)

@push('styles')
<style>
/* Buy Now checkout styles */
.checkout-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.checkout-container h1 {
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2.5rem;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.checkout-layout {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 3rem;
    margin-top: 2rem;
}

.checkout-form {
    background: var(--bg-secondary);
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid var(--border-light);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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

/* POPRAWKA - Payment methods bez marginesów */
.payment-methods {
    display: grid;
    gap: 1rem; /* Zmniejszone z 1.5rem */
    margin-bottom: 0; /* USUNIĘTE margin-bottom: 2rem */
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

/* POPRAWKA - Payment details bezpośrednio pod metodami */
.payment-details {
    margin-top: 1rem; /* Zmniejszone z 2.5rem */
    padding: 2rem;
    background: var(--bg-secondary);
    border-radius: 15px;
    border-left: 5px solid var(--accent-primary);
    /* DODANE - animacja pojawiania się */
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
    background: var(--bg-secondary);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid var(--border-light);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.order-summary h3 {
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
    color: var(--text-primary);
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--accent-primary);
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-light);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid var(--border-light);
}

.summary-image-placeholder {
    width: 60px;
    height: 60px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-size: 1.5rem;
    border: 2px solid var(--border-light);
}

.summary-details {
    flex: 1;
    min-width: 0;
}

.summary-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.summary-quantity {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.summary-price {
    font-weight: 600;
    color: var(--accent-primary);
    text-align: right;
}

.summary-total {
    border-top: 2px solid var(--border-light);
    padding-top: 1rem;
    margin-top: 1rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.summary-row:last-child {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 0;
    padding-top: 0.5rem;
    border-top: 1px solid var(--border-light);
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
    <h1>🛒 Szybkie zakupy</h1>

    <div class="checkout-layout">
        <!-- Formularz zamówienia -->
        <div class="checkout-form">
            <form method="POST" action="{{ route('checkout.process-buy-now') }}">
                @csrf

                <!-- Ukryte pola produktu -->
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="{{ $quantity }}">

                <!-- Dane osobowe -->
                <div class="form-section">
                    <h3>👤 Dane osobowe</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Imię *</label>
                            <input type="text" name="first_name" id="first_name"
                                   value="{{ old('first_name', $user->first_name ?? '') }}" required>
                            @error('first_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="last_name">Nazwisko *</label>
                            <input type="text" name="last_name" id="last_name"
                                   value="{{ old('last_name', $user->last_name ?? '') }}" required>
                            @error('last_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon *</label>
                            <input type="tel" name="phone" id="phone"
                                   value="{{ old('phone', $lastOrder->phone ?? '') }}" required>
                            @error('phone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Adres dostawy -->
                <div class="form-section">
                    <h3>🏠 Adres dostawy</h3>

                    <div class="form-group">
                        <label for="address">Ulica i numer *</label>
                        <input type="text" name="address" id="address"
                               value="{{ old('address', $lastOrder->address ?? '') }}" required>
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">Miasto *</label>
                            <input type="text" name="city" id="city"
                                   value="{{ old('city', $lastOrder->city ?? '') }}" required>
                            @error('city')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Kod pocztowy *</label>
                            <input type="text" name="postal_code" id="postal_code"
                                   value="{{ old('postal_code', $lastOrder->postal_code ?? '') }}" required>
                            @error('postal_code')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country">Kraj</label>
                        <input type="text" name="country" id="country"
                               value="{{ old('country', $lastOrder->country ?? 'Polska') }}">
                        @error('country')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Metoda płatności -->
                <div class="form-section">
                    <h3>💳 Metoda płatności</h3>

                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                            <span class="payment-label">🚚 Płatność przy odbiorze</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="card">
                            <span class="payment-label">💳 Karta płatnicza</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="blik">
                            <span class="payment-label">📱 BLIK</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="transfer">
                            <span class="payment-label">🏦 Przelew bankowy</span>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="paypal">
                            <span class="payment-label">🅿️ PayPal</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <!-- POPRAWKA - Formularze płatności bezpośrednio pod metodami -->

                    <!-- Formularz karty -->
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

                    <!-- Formularz BLIK -->
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

                <!-- Przyciski -->
                <div class="form-actions">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">← Powrót</a>
                    <button type="submit" class="btn btn-primary" id="submitButton">
                        💳 Złóż zamówienie ({{ number_format($cartTotal, 2) }} zł)
                    </button>
                </div>
            </form>
        </div>

        <!-- Podsumowanie zamówienia -->
        <div class="order-summary">
            <h3>📋 Podsumowanie zamówienia</h3>

            <!-- Produkt -->
            <div class="summary-item">
                @if($product->primary_image_url)
                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="summary-image">
                @else
                    <div class="summary-image-placeholder">📦</div>
                @endif

                <div class="summary-details">
                    <div class="summary-name">{{ $product->name }}</div>
                    <div class="summary-quantity">Ilość: {{ $quantity }} szt.</div>
                </div>

                <div class="summary-price">{{ number_format($product->price * $quantity, 2) }} zł</div>
            </div>

            <!-- Podsumowanie finansowe -->
            <div class="summary-total">
                <div class="summary-row">
                    <span>Wartość produktów:</span>
                    <span>{{ number_format($cartTotal, 2) }} zł</span>
                </div>

                <div class="summary-row">
                    <span>Dostawa:</span>
                    <span>Bezpłatna</span>
                </div>

                <div class="summary-row">
                    <span>Łącznie:</span>
                    <span>{{ number_format($cartTotal, 2) }} zł</span>
                </div>
            </div>

            <!-- Informacja o bezpieczeństwie -->
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
                submitButton.innerHTML = '💳 Zapłać kartą ({{ number_format($cartTotal, 2) }} zł)';
                break;
            case 'blik':
                blikDetails.style.display = 'block';
                document.getElementById('blik_code').setAttribute('required', 'required');
                submitButton.innerHTML = '📱 Zapłać BLIK ({{ number_format($cartTotal, 2) }} zł)';
                break;
            case 'paypal':
                submitButton.innerHTML = '🅿️ Zapłać PayPal ({{ number_format($cartTotal, 2) }} zł)';
                break;
            case 'transfer':
                submitButton.innerHTML = '🏦 Zapłać przelewem ({{ number_format($cartTotal, 2) }} zł)';
                break;
            case 'cash_on_delivery':
                submitButton.innerHTML = '📦 Złóż zamówienie ({{ number_format($cartTotal, 2) }} zł)';
                break;
            default:
                submitButton.innerHTML = '💳 Złóż zamówienie ({{ number_format($cartTotal, 2) }} zł)';
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
});
</script>
@endsection
