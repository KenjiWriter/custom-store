/* filepath: c:\xampp\htdocs\custom-store\public\js\cart.js */
class CartManager {
    constructor() {
        this.isOpen = false;
        this.cartItems = [];
        this.cartTotal = 0;
        this.cartCount = 0;
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateCartDisplay();

        // Auto-update cart count co 30 sekund
        setInterval(() => {
            this.updateCartCount();
        }, 30000);
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

            // POPRAWIONE - Otwórz koszyk (obsługuje wszystkie cart-trigger)
            if (e.target.matches('.cart-trigger, .cart-trigger *')) {
                e.preventDefault();
                this.openCartModal();
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
        // Sprawdź czy jest input quantity w pobliżu
        const quantityInput = button.closest('.product-actions')?.querySelector('input[name="quantity"]') ||
                             button.closest('.product-card')?.querySelector('input[name="quantity"]') ||
                             button.closest('form')?.querySelector('input[name="quantity"]');

        return quantityInput ? parseInt(quantityInput.value) || 1 : 1;
    }

    async addToCart(productId, quantity = 1, button = null) {
        try {
            if (button) {
                const originalText = button.innerHTML;
                button.innerHTML = '⏳ Dodawanie...';
                button.disabled = true;
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
                // Sukces
                if (button) {
                    button.innerHTML = '✅ Dodano!';
                    button.style.background = 'linear-gradient(135deg, #10b981, #059669)';

                    setTimeout(() => {
                        button.innerHTML = button.dataset.originalText || '🛒 Dodaj do koszyka';
                        button.style.background = '';
                        button.disabled = false;
                    }, 2000);
                }

                // Aktualizuj licznik
                this.updateCartCounter(data.cart_count);

                // Pokaż powiadomienie
                this.showNotification(data.message, 'success');

                // Opcjonalnie otwórz koszyk
                setTimeout(() => {
                    this.openCartModal();
                }, 1000);

            } else {
                throw new Error(data.message);
            }

        } catch (error) {
            console.error('Błąd dodawania do koszyka:', error);

            if (button) {
                button.innerHTML = '❌ Błąd';
                button.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';

                setTimeout(() => {
                    button.innerHTML = button.dataset.originalText || '🛒 Dodaj do koszyka';
                    button.style.background = '';
                    button.disabled = false;
                }, 2000);
            }

            this.showNotification(error.message || 'Błąd podczas dodawania do koszyka', 'error');
        }
    }

    async buyNow(productId, quantity = 1) {
        try {
            // Sprawdź dostępność przed przekierowaniem
            const checkResponse = await fetch(`/products/${productId}/check-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: quantity })
            });

            const checkData = await checkResponse.json();

            if (checkData.available) {
                // Przekieruj do checkout z parametrami
                const url = new URL('/checkout/buy-now', window.location.origin);
                url.searchParams.set('product_id', productId);
                url.searchParams.set('quantity', quantity);

                window.location.href = url.toString();
            } else {
                this.showNotification(checkData.message || 'Produkt niedostępny w wymaganej ilości', 'error');
            }

        } catch (error) {
            console.error('Błąd Kup Teraz:', error);
            this.showNotification('Błąd podczas procesowania zamówienia', 'error');
        }
    }

    async openCartModal() {
        try {
            const modal = document.getElementById('cartModal');
            if (!modal) {
                console.error('Cart modal not found');
                return;
            }

            // Pokaż modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            this.isOpen = true;

            // Załaduj zawartość koszyka
            await this.loadCartContent();

        } catch (error) {
            console.error('Błąd otwierania koszyka:', error);
            this.showNotification('Błąd podczas ładowania koszyka', 'error');
        }
    }

    closeCartModal() {
        const modal = document.getElementById('cartModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            this.isOpen = false;
        }
    }

    async loadCartContent() {
        try {
            const bodyElement = document.getElementById('cartModalBody');
            const footerElement = document.getElementById('cartModalFooter');

            if (!bodyElement) return;

            // Pokaż loading
            bodyElement.innerHTML = `
                <div class="cart-loading">
                    <div class="cart-spinner"></div>
                    <p>Ładowanie koszyka...</p>
                </div>
            `;

            // Pobierz zawartość koszyka
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

            // Wyrenderuj zawartość
            this.renderCartContent(data.items || [], data.total || '0,00 zł');

        } catch (error) {
            console.error('Błąd ładowania koszyka:', error);
            const bodyElement = document.getElementById('cartModalBody');
            if (bodyElement) {
                bodyElement.innerHTML = `
                    <div class="cart-empty">
                        <h3>❌ Błąd ładowania</h3>
                        <p>Nie udało się załadować zawartości koszyka.</p>
                        <button class="btn btn-primary" onclick="cart.loadCartContent()">Spróbuj ponownie</button>
                    </div>
                `;
            }
        }
    }

    renderCartContent(items, total) {
        const bodyElement = document.getElementById('cartModalBody');
        const footerElement = document.getElementById('cartModalFooter');

        if (!bodyElement) return;

        if (items.length === 0) {
            // Pusty koszyk
            bodyElement.innerHTML = `
                <div class="cart-empty">
                    <h3>🛒 Koszyk jest pusty</h3>
                    <p>Dodaj produkty do koszyka, aby je tutaj zobaczyć.</p>
                </div>
            `;

            if (footerElement) {
                footerElement.style.display = 'none';
            }
            return;
        }

        // Renderuj produkty
        const itemsHtml = items.map(item => this.renderCartItem(item)).join('');

        bodyElement.innerHTML = `
            <div class="cart-items">
                ${itemsHtml}
            </div>
        `;

        // Pokaż footer z total i przyciskami
        if (footerElement) {
            footerElement.style.display = 'block';
            const totalElement = document.getElementById('cartModalTotal');
            if (totalElement) {
                totalElement.textContent = total;
            }
        }

        // Binduj eventy dla kontrolek
        this.bindCartItemEvents();
    }

    renderCartItem(item) {
        const imageHtml = item.product.images && item.product.images.length > 0
            ? `<img src="/storage/${item.product.images[0].image_path}" alt="${item.product.name}">`
            : `<div class="cart-item-no-image">📦</div>`;

        return `
            <div class="cart-item" data-cart-id="${item.id}">
                <div class="cart-item-image">
                    ${imageHtml}
                </div>
                <div class="cart-item-info">
                    <h4 class="cart-item-name">${item.product.name}</h4>
                    <div class="cart-item-price">${item.formatted_total_price}</div>
                    <div class="cart-item-controls">
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="cart.updateQuantity(${item.id}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="100" onchange="cart.updateQuantity(${item.id}, this.value)">
                            <button class="quantity-btn" onclick="cart.updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                        </div>
                        <button class="remove-btn" onclick="cart.removeItem(${item.id})" title="Usuń z koszyka">🗑️</button>
                    </div>
                </div>
            </div>
        `;
    }

    bindCartItemEvents() {
        // Event listener dla quantity inputs
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('blur', (e) => {
                const cartId = e.target.closest('.cart-item').dataset.cartId;
                const quantity = parseInt(e.target.value) || 1;
                this.updateQuantity(cartId, quantity);
            });
        });
    }

    async updateQuantity(cartId, quantity) {
        try {
            if (quantity < 1) return;

            const response = await fetch(`/cart/${cartId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: quantity })
            });

            const data = await response.json();

            if (data.success) {
                // Aktualizuj licznik
                this.updateCartCounter(data.cart_count);

                // Przeładuj zawartość koszyka
                await this.loadCartContent();

                this.showNotification(data.message, 'success');
            } else {
                throw new Error(data.message);
            }

        } catch (error) {
            console.error('Błąd aktualizacji ilości:', error);
            this.showNotification(error.message || 'Błąd podczas aktualizacji ilości', 'error');

            // Przeładuj koszyk
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
                // Aktualizuj licznik
                this.updateCartCounter(data.cart_count);

                // Przeładuj zawartość koszyka
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

        // Aktualizuj wszystkie liczniki na stronie
        document.querySelectorAll('.cart-count').forEach(counter => {
            counter.textContent = count;
            counter.style.display = count > 0 ? 'flex' : 'none';
        });

        // Aktualizuj przyciski koszyka
        document.querySelectorAll('.cart-trigger').forEach(trigger => {
            const badge = trigger.querySelector('.cart-count');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }
        });
    }

    updateCartDisplay() {
        // Inicjalna aktualizacja wyświetlania
        this.updateCartCount();
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${this.getNotificationColor(type)};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            z-index: 10000;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: slideInRight 0.3s ease;
            max-width: 300px;
            word-wrap: break-word;
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    getNotificationColor(type) {
        switch (type) {
            case 'success': return '#10b981';
            case 'error': return '#ef4444';
            case 'warning': return '#f59e0b';
            default: return '#3b82f6';
        }
    }
}

// Dodaj style animacji dla powiadomień
const notificationStyles = document.createElement('style');
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

// Globalne funkcje
let cart;

function initCart() {
    if (!cart) {
        cart = new CartManager();
    }
    return cart;
}

function openCartModal() {
    if (!cart) initCart();
    cart.openCartModal();
}

function closeCartModal() {
    if (cart) cart.closeCartModal();
}

// Auto-inicjalizacja
document.addEventListener('DOMContentLoaded', function() {
    // Inicjalizuj tylko dla zalogowanych użytkowników
    const isAuthenticated = document.querySelector('meta[name="user-authenticated"]');
    if (isAuthenticated && isAuthenticated.content === 'true') {
        initCart();
    }
});

// Export dla modułów
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { CartManager, initCart };
}
