
<div id="cartModal" class="cart-modal" style="display: none;">
    <div class="cart-modal-overlay" onclick="closeCartModal()"></div>

    <div class="cart-modal-content">
        <div class="cart-modal-header">
            <h2>🛒 Twój koszyk</h2>
            <button class="cart-close-btn" onclick="closeCartModal()" aria-label="Zamknij koszyk">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="cart-modal-body" id="cartModalBody">
            <div class="cart-loading">
                <div class="cart-spinner"></div>
                <p>Ładowanie koszyka...</p>
            </div>
        </div>

        <div class="cart-modal-footer" id="cartModalFooter" style="display: none;">
            <div class="cart-total">
                <strong>Razem: <span id="cartModalTotal">0,00 zł</span></strong>
            </div>
            <div class="cart-actions">
                <button class="btn btn-secondary" onclick="closeCartModal()">Kontynuuj zakupy</button>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary">Przejdź do kasy</a>
            </div>
        </div>
    </div>
</div>
