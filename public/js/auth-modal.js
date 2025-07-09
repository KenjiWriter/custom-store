class AuthModal {
    constructor() {
        this.modal = null;
        this.isOpen = false;
        this.actionData = null;
        this.focusTrapped = false;
        this.init();
    }

    init() {
        this.modal = document.getElementById('authModal');
        if (!this.modal) {
            console.warn('Auth modal element not found');
            return;
        }

        this.bindEvents();
        this.initRequiresAuthElements();
        this.preloadModal();
    }

    preloadModal() {
        // Preload modal dla lepszej performance
        const content = this.modal.querySelector('.auth-modal-content');
        if (content) {
            content.style.transform = 'translate(-50%, -50%) scale(0.95)';
            content.style.opacity = '0';
        }
    }

    bindEvents() {
        // Zamknij modal przy kliknięciu w tło
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        // Obsługa klawiatury
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }

            // Trap focus w modalu
            if (this.isOpen && e.key === 'Tab') {
                this.trapFocus(e);
            }
        });

        // Zapobiegaj zamykaniu przy kliknięciu w zawartość
        const modalContent = this.modal.querySelector('.auth-modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Obsługa przycisków
        const loginBtn = this.modal.querySelector('.auth-login-btn');
        const registerBtn = this.modal.querySelector('.auth-register-btn');

        if (loginBtn) {
            loginBtn.addEventListener('click', () => {
                this.trackAction('login_clicked');
                this.showLoading();
            });
        }

        if (registerBtn) {
            registerBtn.addEventListener('click', () => {
                this.trackAction('register_clicked');
                this.showLoading();
            });
        }
    }

    trapFocus(e) {
        const focusableElements = this.modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );

        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        if (e.shiftKey) {
            if (document.activeElement === firstFocusable) {
                lastFocusable.focus();
                e.preventDefault();
            }
        } else {
            if (document.activeElement === lastFocusable) {
                firstFocusable.focus();
                e.preventDefault();
            }
        }
    }

    initRequiresAuthElements() {
        const requiresAuthElements = document.querySelectorAll('.requires-auth');

        requiresAuthElements.forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const action = element.getAttribute('data-action');
                const productId = element.getAttribute('data-product-id');
                const productName = element.getAttribute('data-product-name') || 'tego produktu';

                this.showForAction(action, productId, productName);
            });
        });
    }

    showForAction(action, productId, productName = 'tego produktu') {
        this.actionData = { action, productId, productName };

        const actionConfig = this.getActionConfig(action, productName);
        this.show(actionConfig.title, actionConfig.message, actionConfig.icon);
    }

    getActionConfig(action, productName) {
        const configs = {
            'add-to-cart': {
                title: '🛒 Dodawanie do koszyka',
                message: `Aby dodać "${productName}" do koszyka, zaloguj się i ciesz się szybszymi zakupami.`,
                icon: '🛒'
            },
            'buy-now': {
                title: '⚡ Ekspresowe zakupy',
                message: `Aby kupić "${productName}" natychmiast, zaloguj się dla bezpiecznej transakcji.`,
                icon: '⚡'
            },
            'add-to-favorites': {
                title: '💖 Lista ulubionych',
                message: `Aby dodać "${productName}" do ulubionych, zaloguj się i nigdy nie zgub swoich marzeń.`,
                icon: '💖'
            },
            'notify-availability': {
                title: '🔔 Powiadomienia',
                message: `Aby otrzymywać powiadomienia o dostępności "${productName}", zaloguj się.`,
                icon: '🔔'
            }
        };

        return configs[action] || {
            title: 'Wymagane logowanie',
            message: 'Aby wykonać tę akcję, musisz się zalogować.',
            icon: '🔐'
        };
    }

    show(title = 'Wymagane logowanie', message = 'Aby wykonać tę akcję, musisz się zalogować.', icon = '🔐') {
        if (!this.modal) return;

        // Ustaw zawartość
        this.updateModalContent(title, message, icon);

        // Animacje otwarcia
        this.modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Trigger reflow
        this.modal.offsetHeight;

        this.isOpen = true;
        this.focusTrapped = true;

        // Animuj zawartość
        const content = this.modal.querySelector('.auth-modal-content');
        if (content) {
            setTimeout(() => {
                content.style.transform = 'translate(-50%, -50%) scale(1)';
                content.style.opacity = '1';
            }, 50);
        }

        // Focus na pierwszy przycisk
        setTimeout(() => {
            const firstButton = this.modal.querySelector('.auth-login-btn');
            if (firstButton) firstButton.focus();
        }, 200);

        this.trackModalOpen();
    }

    updateModalContent(title, message, icon) {
        const titleElement = document.getElementById('authModalTitle');
        const messageElement = document.getElementById('authModalMessage');
        const iconElement = this.modal.querySelector('.auth-icon');

        if (titleElement) titleElement.textContent = title;
        if (messageElement) messageElement.textContent = message;
        if (iconElement) iconElement.textContent = icon;
    }

    showLoading() {
        const loading = document.getElementById('authModalLoading');
        if (loading) {
            loading.classList.add('active');
        }
    }

    hideLoading() {
        const loading = document.getElementById('authModalLoading');
        if (loading) {
            loading.classList.remove('active');
        }
    }

    close() {
        if (!this.modal || !this.isOpen) return;

        this.modal.classList.add('closing');
        this.focusTrapped = false;

        setTimeout(() => {
            this.modal.style.display = 'none';
            this.modal.classList.remove('closing');
            document.body.style.overflow = 'auto';
            this.isOpen = false;
            this.actionData = null;
            this.hideLoading();

            // Reset content position
            const content = this.modal.querySelector('.auth-modal-content');
            if (content) {
                content.style.transform = 'translate(-50%, -50%) scale(0.95)';
                content.style.opacity = '0';
            }
        }, 300);

        this.trackModalClose();
    }

    trackModalOpen() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'auth_modal_open', {
                event_category: 'engagement',
                event_label: this.actionData?.action || 'unknown',
                custom_parameter_1: this.actionData?.productId || null
            });
        }

        console.log('Auth modal opened for action:', this.actionData?.action || 'unknown');
    }

    trackModalClose() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'auth_modal_close', {
                event_category: 'engagement'
            });
        }
    }

    trackAction(action) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'auth_modal_action', {
                event_category: 'engagement',
                event_label: action,
                custom_parameter_1: this.actionData?.productId || null
            });
        }
    }

    // Publiczne metody dla kompatybilności
    openForAddToCart(productId, productName) {
        this.showForAction('add-to-cart', productId, productName);
    }

    openForBuyNow(productId, productName) {
        this.showForAction('buy-now', productId, productName);
    }

    openForWishlist(productId, productName) {
        this.showForAction('add-to-favorites', productId, productName);
    }
}

// Globalne zmienne i funkcje
let authModal;

function initAuthModal() {
    if (!authModal) {
        authModal = new AuthModal();
        console.log('Auth Modal initialized with enhancements');
    }
}

function showAuthModal(title, message, icon) {
    if (!authModal) initAuthModal();
    authModal.show(title, message, icon);
}

function closeAuthModal() {
    if (authModal) authModal.close();
}

function showAuthModalForAction(action, productId, productName) {
    if (!authModal) initAuthModal();
    authModal.showForAction(action, productId, productName);
}

// Funkcje pomocnicze
function requireAuth(action, productId = null, productName = 'produkt') {
    if (!authModal) initAuthModal();
    authModal.showForAction(action, productId, productName);
}

function requireAuthForCart(productId, productName) {
    requireAuth('add-to-cart', productId, productName);
}

function requireAuthForPurchase(productId, productName) {
    requireAuth('buy-now', productId, productName);
}

function requireAuthForWishlist(productId, productName) {
    requireAuth('add-to-favorites', productId, productName);
}

// Auto-inicjalizacja
document.addEventListener('DOMContentLoaded', function () {
    const isAuthenticated = document.querySelector('meta[name="user-authenticated"]');
    const hasAuthModal = document.getElementById('authModal');

    if (hasAuthModal && (!isAuthenticated || isAuthenticated.content !== 'true')) {
        initAuthModal();
    }
});

// Export dla modułów
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        AuthModal,
        initAuthModal,
        showAuthModal,
        closeAuthModal,
        requireAuth
    };
}
