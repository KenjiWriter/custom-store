class AuthModal {
    constructor() {
        this.modal = null;
        this.isOpen = false;
        this.actionData = null;
        this.init();
    }

    init() {
        this.modal = document.getElementById('authModal');

        if (!this.modal) {
            console.warn('Auth modal element not found');
            return;
        }

        // Event listeners
        this.bindEvents();

        // Automatyczne inicjowanie dla requires-auth elementów
        this.initRequiresAuthElements();
    }

    bindEvents() {
        // Zamknij modal przy kliknięciu w tło
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        // Obsługa klawisza Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });

        // Zapobiegaj zamykaniu przy kliknięciu w zawartość
        const modalContent = this.modal.querySelector('.auth-modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
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

        let title = 'Wymagane logowanie';
        let message = 'Aby wykonać tę akcję, musisz się zalogować.';

        switch (action) {
            case 'add-to-cart':
                title = '🛒 Dodawanie do koszyka';
                message = `Aby dodać "${productName}" do koszyka, musisz się zalogować.`;
                break;
            case 'buy-now':
                title = '⚡ Kup teraz';
                message = `Aby kupić "${productName}", musisz się zalogować.`;
                break;
            case 'add-to-favorites':
                title = '💖 Dodaj do ulubionych';
                message = `Aby dodać "${productName}" do ulubionych, musisz się zalogować.`;
                break;
            case 'notify-availability':
                title = '🔔 Powiadomienia';
                message = `Aby otrzymywać powiadomienia o dostępności "${productName}", musisz się zalogować.`;
                break;
            default:
                title = 'Wymagane logowanie';
                message = 'Aby wykonać tę akcję, musisz się zalogować.';
        }

        this.show(title, message);
    }

    show(title = 'Wymagane logowanie', message = 'Aby wykonać tę akcję, musisz się zalogować.') {
        if (!this.modal) return;

        // Ustaw tytuł i wiadomość
        const titleElement = document.getElementById('authModalTitle');
        const messageElement = document.getElementById('authModalMessage');

        if (titleElement) titleElement.textContent = title;
        if (messageElement) messageElement.textContent = message;

        // Pokaż modal
        this.modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        this.isOpen = true;

        // Focus na pierwszy przycisk
        setTimeout(() => {
            const firstButton = this.modal.querySelector('.auth-login-btn');
            if (firstButton) firstButton.focus();
        }, 100);

        // Analytics/tracking
        this.trackModalOpen();
    }

    close() {
        if (!this.modal || !this.isOpen) return;

        // Dodaj klasę animacji zamykania
        this.modal.classList.add('closing');

        setTimeout(() => {
            this.modal.style.display = 'none';
            this.modal.classList.remove('closing');
            document.body.style.overflow = 'auto';
            this.isOpen = false;
            this.actionData = null;
        }, 300);

        // Analytics/tracking
        this.trackModalClose();
    }

    trackModalOpen() {
        // Tracking dla analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'auth_modal_open', {
                event_category: 'engagement',
                event_label: this.actionData?.action || 'unknown'
            });
        }

        console.log('Auth modal opened for action:', this.actionData?.action || 'unknown');
    }

    trackModalClose() {
        // Tracking dla analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'auth_modal_close', {
                event_category: 'engagement'
            });
        }

        console.log('Auth modal closed');
    }

    // Metody publiczne dla kompatybilności
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
        console.log('Auth Modal initialized');
    }
}

function showAuthModal(title, message) {
    if (!authModal) initAuthModal();
    authModal.show(title, message);
}

function closeAuthModal() {
    if (authModal) authModal.close();
}

function showAuthModalForAction(action, productId, productName) {
    if (!authModal) initAuthModal();
    authModal.showForAction(action, productId, productName);
}

// Funkcje pomocnicze dla specific actions
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

// Auto-inicjalizacja po załadowaniu DOM
document.addEventListener('DOMContentLoaded', function () {
    // Inicjalizuj tylko jeśli użytkownik nie jest zalogowany
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