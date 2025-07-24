class CartManager {
    constructor() {
        this.cartCount = 0;
        this.isOpen = false;
        this.modal = null;
    }

    init() {
        this.bindEvents();
        this.updateCartDisplay();

        // Auto-update cart count co 30 sekund
        setInterval(() => {
            this.updateCartCount();
        }, 30000);

        // Dodaj style animacji dla powiadomień (tylko raz)
        if (!document.querySelector('#notificationStyles')) {
            const notificationStyles = document.createElement('style');
            notificationStyles.id = 'notificationStyles';
            notificationStyles.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }

                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(notificationStyles);
        }
    }

    bindEvents() {
        // Event delegation dla przycisków dodawania do koszyka
        document.addEventListener('click', (e) => {
            // Obsługa przycisków Add to Cart
            if (e.target.matches('.btn-add-to-cart, .btn-add-to-cart *')) {
                e.preventDefault();
                const button = e.target.closest('.btn-add-to-cart');
                const productId = button.dataset.productId;
                const quantity = this.getQuantityFromButton(button);

                if (productId) {
                    this.addToCart(productId, quantity, button);
                }
            }

            // Kup teraz
            if (e.target.matches('.btn-buy-now, .btn-buy-now *')) {
                e.preventDefault();
                const button = e.target.closest('.btn-buy-now');
                const productId = button.dataset.productId;
                const quantity = this.getQuantityFromButton(button);

                if (productId) {
                    this.buyNow(productId, quantity);
                }
            }

            // Otwórz koszyk
            if (e.target.matches('.cart-trigger, .cart-trigger *')) {
                e.preventDefault();
                this.openCartModal();
            }

            // NOWE - Przejdź do checkout
            if (e.target.matches('.btn-checkout, .btn-checkout *')) {
                e.preventDefault();
                this.goToCheckout();
            }
        });

        // Zamknij modal przy kliknięciu w overlay
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('cart-modal-overlay')) {
                this.closeCartModal();
            }
        });

        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeCartModal();
            }
        });
    }

    getQuantityFromButton(button) {
        // Szukaj w tym samym kontenerze co przycisk
        const container = button.closest('.product-actions, .quick-add-form, .product-controls');
        if (container) {
            const quantityInput = container.querySelector('input[name="quantity"], .quantity-input');
            if (quantityInput) {
                return parseInt(quantityInput.value) || 1;
            }
        }
        return 1;
    }

    async addToCart(productId, quantity = 1, button = null) {
        try {
            if (button) {
                button.disabled = true;
                button.innerHTML = '⏳ Dodawanie...';
                button.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
            }

            const response = await fetch('/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                if (button) {
                    button.innerHTML = '✅ Dodano!';
                    button.style.background = 'linear-gradient(135deg, #10b981, #059669)';

                    setTimeout(() => {
                        button.innerHTML = '🛒 Dodaj do koszyka';
                        button.style.background = '';
                        button.disabled = false;
                    }, 2000);
                }

                this.updateCartCounter(data.cart_count);
                this.showNotification(data.message, 'success');
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Błąd dodawania do koszyka:', error);

            if (button) {
                button.innerHTML = '❌ Błąd';
                button.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';

                setTimeout(() => {
                    button.innerHTML = '🛒 Dodaj do koszyka';
                    button.style.background = '';
                    button.disabled = false;
                }, 2000);
            }

            this.showNotification(error.message || 'Błąd podczas dodawania do koszyka', 'error');
        }
    }

    async buyNow(productId, quantity = 1) {
        try {
            // Przekieruj do strony buy-now z parametrami
            const url = new URL('/checkout/buy-now', window.location.origin);
            url.searchParams.append('product_id', productId);
            url.searchParams.append('quantity', quantity);

            window.location.href = url.toString();
        } catch (error) {
            console.error('Błąd Buy Now:', error);
            this.showNotification('Wystąpił błąd podczas przekierowania', 'error');
        }
    }

    // NOWA FUNKCJA - przejdź do checkout
    async goToCheckout() {
        try {
            // Sprawdź czy koszyk nie jest pusty
            const response = await fetch('/cart/count', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.count === 0) {
                this.showNotification('Koszyk jest pusty! Dodaj produkty przed przejściem do kasy.', 'error');
                return;
            }

            // Przekieruj do checkout
            window.location.href = '/checkout';
        } catch (error) {
            console.error('Błąd przejścia do checkout:', error);
            // Fallback - zawsze spróbuj przekierować
            window.location.href = '/checkout';
        }
    }

    async openCartModal() {
        this.isOpen = true;
        const modal = document.getElementById('cartModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            await this.loadCartContent();
        }
    }

    closeCartModal() {
        this.isOpen = false;
        const modal = document.getElementById('cartModal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
            document.body.style.overflow = '';
        }
    }

    async loadCartContent() {
        try {
            const bodyElement = document.getElementById('cartModalBody');
            const footerElement = document.getElementById('cartModalFooter');
            const itemCount = document.getElementById('cartModalItemCount');

            if (!bodyElement) return;

            bodyElement.innerHTML = `
                <div class="cart-loading">
                    <div class="cart-spinner"></div>
                    <p>Ładowanie koszyka...</p>
                </div>
            `;

            const response = await fetch('/cart', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Błąd pobierania koszyka');
            }

            const data = await response.json();

            // Aktualizuj licznik w headerze
            if (itemCount) {
                const count = data.items ? data.items.length : 0;
                itemCount.textContent = `${count} ${count === 1 ? 'produkt' : count < 5 ? 'produkty' : 'produktów'}`;
            }

            this.renderCartContent(data.items || [], data.total || '0,00 zł');
        } catch (error) {
            console.error('Błąd ładowania koszyka:', error);
            const bodyElement = document.getElementById('cartModalBody');
            if (bodyElement) {
                bodyElement.innerHTML = `
                    <div class="cart-empty">
                        <div class="cart-empty-icon">❌</div>
                        <h3>Błąd ładowania</h3>
                        <p>Nie udało się załadować zawartości koszyka</p>
                        <button class="btn btn-primary" onclick="cart.loadCartContent()">Spróbuj ponownie</button>
                    </div>
                `;
            }
        }
    }

     renderCartContent(items, total) {
        const bodyElement = document.getElementById('cartModalBody');
        const footerElement = document.getElementById('cartModalFooter');
        const subtotalElement = document.getElementById('cartModalSubtotal');
        const totalElement = document.getElementById('cartModalTotal');

        if (!bodyElement) return;

        if (items.length === 0) {
            bodyElement.innerHTML = `
                <div class="cart-empty">
                    <div class="cart-empty-icon">🛒</div>
                    <h3>Twój koszyk jest pusty</h3>
                    <p>Dodaj produkty, aby zobaczyć je tutaj</p>
                    <button class="btn btn-primary" onclick="cart.closeCartModal()">
                        🛍️ Kontynuuj zakupy
                    </button>
                </div>
            `;
            if (footerElement) footerElement.style.display = 'none';
            return;
        }

        // 🔥 POPRAWKA - Renderuj produkty w koszyku z prawidłowymi zdjęciami i cenami
        const itemsHtml = items.map(item => {
            // Poprawka dla zdjęcia
            let imageHtml = '';
            if (item.product && item.product.primary_image_url) {
                imageHtml = `<img src="${item.product.primary_image_url}" alt="${item.product.name}" class="cart-item-image">`;
            } else {
                imageHtml = '<div class="cart-item-placeholder">📷</div>';
            }

            // Poprawka dla cen - użyj danych z API
            const unitPrice = item.product ? item.product.formatted_price : '0,00 zł';
            const totalPrice = item.formatted_total_price ||
                              (item.product ?
                                `${(parseFloat(item.product.price) * parseInt(item.quantity)).toFixed(2).replace('.', ',')} zł` :
                                '0,00 zł');

            return `
                <div class="cart-item" data-cart-id="${item.id}">
                    ${imageHtml}
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.product ? item.product.name : 'Produkt usunięty'}</div>
                        <div class="cart-item-meta">SKU: ${item.product?.sku || 'Brak'}</div>
                        <div class="cart-item-controls">
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="cart.updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="quantity-btn" onclick="cart.updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                            </div>
                            <button class="remove-btn" onclick="cart.removeItem(${item.id})">🗑️ Usuń</button>
                        </div>
                    </div>
                    <div class="cart-item-price">
                        <div class="cart-item-unit-price">${unitPrice}/szt.</div>
                        <div class="cart-item-total">${totalPrice}</div>
                    </div>
                </div>
            `;
        }).join('');

        bodyElement.innerHTML = itemsHtml;

        // Pokaż footer z sumą i przyciskami
        if (footerElement) {
            if (subtotalElement) subtotalElement.textContent = total;
            if (totalElement) totalElement.textContent = total;
            footerElement.style.display = 'block';
        }
    }

    async updateQuantity(cartId, quantity) {
    try {
        if (quantity < 1) {
            this.removeItem(cartId);
            return;
        }

        // 🔥 POPRAWKA - Użyj PATCH zamiast PUT
        const response = await fetch(`/cart/${cartId}`, {
            method: 'PATCH', // Zmienione z PUT na PATCH
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        });

        const data = await response.json();

        if (data.success) {
            this.updateCartCounter(data.cart_count);
            await this.loadCartContent();
            this.showNotification(data.message, 'success');
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Błąd aktualizacji ilości:', error);
        this.showNotification(error.message || 'Błąd podczas aktualizacji ilości', 'error');
        await this.loadCartContent();
    }
}

    async removeItem(cartId) {
        try {
            if (!confirm('Czy na pewno chcesz usunąć ten produkt z koszyka?')) {
                return;
            }

            const response = await fetch(`/cart/${cartId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartCounter(data.cart_count);
                await this.loadCartContent();
                this.showNotification(data.message, 'success');
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Błąd usuwania produktu:', error);
            this.showNotification(error.message || 'Błąd podczas usuwania produktu', 'error');
        }
    }

    async updateCartCount() {
        try {
            const response = await fetch('/cart/count', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            this.updateCartCounter(data.count);
        } catch (error) {
            console.error('Błąd pobierania licznika koszyka:', error);
        }
    }

    updateCartCounter(count) {
        this.cartCount = count;

        document.querySelectorAll('.cart-count').forEach(counter => {
            counter.textContent = count;
            counter.style.display = count > 0 ? 'flex' : 'none';
        });

        document.querySelectorAll('.cart-trigger').forEach(trigger => {
            const badge = trigger.querySelector('.cart-count');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }
        });
    }

    updateCartDisplay() {
        this.updateCartCount();
    }

    showNotification(message, type = 'info', duration = 4000) {
        // Usuń istniejące powiadomienia
        document.querySelectorAll('.cart-notification').forEach(n => n.remove());

        const notification = document.createElement('div');
        notification.className = `cart-notification cart-notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            font-weight: 500;
            max-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
}

// Globalne funkcje dla kompatybilności wstecznej
function openCartModal() {
    if (window.cart) {
        window.cart.openCartModal();
    }
}

function closeCartModal() {
    if (window.cart) {
        window.cart.closeCartModal();
    }
}

function goToCheckout() {
    if (window.cart) {
        window.cart.goToCheckout();
    }
}

// Inicjalizacja po załadowaniu DOM
document.addEventListener('DOMContentLoaded', function() {
    window.cart = new CartManager();
    window.cart.init();
    console.log('Cart Manager initialized');
});

// Export dla modułów
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartManager;
}
