<div id="cartModal" class="cart-modal" style="display: none;">
    <div class="cart-modal-overlay" onclick="closeCartModal()"></div>

    <div class="cart-modal-content">
        <div class="cart-modal-header">
            <div class="cart-header-content">
                <h2>🛒 Twój koszyk</h2>
                <div class="cart-item-count" id="cartModalItemCount">0 produktów</div>
            </div>
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
            <div class="cart-summary">
                <div class="cart-summary-row">
                    <span>Wartość produktów:</span>
                    <span id="cartModalSubtotal">0,00 zł</span>
                </div>
                <div class="cart-summary-row">
                    <span>Dostawa:</span>
                    <span class="delivery-free">Bezpłatna</span>
                </div>
                <div class="cart-total-row">
                    <strong>Łącznie: <span id="cartModalTotal">0,00 zł</span></strong>
                </div>
            </div>

            <div class="cart-actions">
                <button class="btn btn-secondary" onclick="closeCartModal()">
                    🛍️ Kontynuuj zakupy
                </button>
                <button class="btn btn-primary btn-checkout" onclick="goToCheckout()" id="checkoutButton">
                    💳 Przejdź do płatności
                </button>
            </div>
        </div>
    </div>
</div>
