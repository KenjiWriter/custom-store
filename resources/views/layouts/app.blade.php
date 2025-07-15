<!DOCTYPE html>
<html lang="pl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">
    <title>@yield('title', 'Sklep - Strona Główna')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Auth Modal CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth-modal.css') }}">
    <!-- DODANE - Cart Modal CSS -->
    <link rel="stylesheet" href="{{ asset('css/cart-modal.css') }}">

    @stack('styles')

    @auth
        <script src="{{ asset('js/cart.js') }}"></script>
    @endauth
</head>
<body>
    <!-- Nawigacja -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo">
                🛍️ Nasz Sklep
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">🏠 Strona główna</a>
                <a href="#" class="nav-link">📦 Produkty</a>
                <a href="#" class="nav-link">🏷️ Kategorie</a>
                @auth
                    <a href="{{ route('wishlist.index') }}" class="nav-link">
                        💖 Ulubione
                        <span id="wishlistCounter" class="wishlist-count" style="display: {{ auth()->user()->wishlist_count > 0 ? 'flex' : 'none' }};">
                            {{ auth()->user()->wishlist_count }}
                        </span>
                    </a>
                    <!-- POPRAWIONY KOSZYK - używa class zamiast onclick -->
                    <button class="nav-link cart-trigger">
                        🛒 Koszyk
                        <span class="cart-count" style="display: {{ auth()->user()->cart_count > 0 ? 'flex' : 'none' }};">
                            {{ auth()->user()->cart_count }}
                        </span>
                    </button>
                @endauth
                <a href="#" class="nav-link">ℹ️ O nas</a>
            </div>

            <div class="auth-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Panel</a>
                    <!-- DODANY PRZYCISK ZAMÓWIENIA -->
                    <a href="{{ route('checkout.orders') }}" class="btn btn-outline">📋 Zamówienia</a>

                    <!-- Logout form - NAPRAWIONY -->
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick="this.innerHTML='⏳ Wylogowywanie...'; this.disabled=true;">
                            Wyloguj
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Zaloguj</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Zarejestruj</a>
                @endauth

                <!-- ENHANCED THEME TOGGLE BUTTON -->
                <div class="theme-toggle" onclick="toggleTheme()">
                    <div class="theme-toggle-slider">
                        <span class="theme-icon">☀️</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Główna zawartość -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Informacje o sklepie -->
                <div class="footer-section">
                    <h3>O naszym sklepie</h3>
                    <p>Jesteśmy nowoczesnym sklepem internetowym oferującym wysokiej jakości produkty w najlepszych cenach. Nasza misja to zadowolenie każdego klienta.</p>
                    <p>Dołącz do tysięcy zadowolonych klientów i odkryj wyjątkowe produkty w naszym sklepie.</p>
                </div>

                <!-- Szybkie linki -->
                <div class="footer-section">
                    <h3>Szybkie linki</h3>
                    <a href="{{ route('home') }}">🏠 Strona główna</a>
                    <a href="#">📦 Wszystkie produkty</a>
                    <a href="#">🏷️ Kategorie</a>
                    @auth
                        <a href="{{ route('wishlist.index') }}">💖 Moje ulubione</a>
                        <a href="{{ route('checkout.orders') }}">📋 Moje zamówienia</a>
                    @endauth
                    <a href="#">ℹ️ O nas</a>
                    <a href="#">📞 Kontakt</a>
                    <a href="#">📋 Regulamin</a>
                    <a href="#">🔒 Polityka prywatności</a>
                </div>

                <!-- Kontakt -->
                <div class="footer-section">
                    <h3>Kontakt</h3>
                    <p>📧 kontakt@naszsklet.pl</p>
                    <p>📞 +48 123 456 789</p>
                    <p>📱 +48 987 654 321</p>
                    <p>⏰ Pn-Pt: 8:00-18:00, Sb: 9:00-15:00</p>
                </div>

                <!-- Lokalizacja -->
                <div class="footer-section">
                    <h3>Nasza lokalizacja</h3>
                    <p>📍 ul. Przykładowa 123<br>
                    00-001 Warszawa<br>
                    Polska</p>
                    <a href="https://maps.google.com/?q=Warszawa,+ul.+Przykładowa+123" target="_blank">
                        🗺️ Zobacz na mapie
                    </a>
                </div>

                <!-- Mapa Google -->
                <div class="footer-section">
                    <h3>Znajdź nas</h3>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2443.8583442853834!2d21.012229!3d52.229676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ecc669a869f01%3A0x72f0be2a9ce57a32!2sPałac%20Kultury%20i%20Nauki!5e0!3m2!1spl!2spl!4v1635432167891!5m2!1spl!2spl"
                        width="100%"
                        height="200"
                        style="border: 0; border-radius: 12px; filter: grayscale(20%);"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Nasz Sklep. Wszystkie prawa zastrzeżone.</p>
            </div>
        </div>
    </footer>

    <!-- Cart Modal - tylko dla zalogowanych -->
    @auth
        <x-cart-modal />
    @endauth

    <!-- Auth Modal - tylko dla niezalogowanych -->
    @guest
        <x-auth-modal />
    @endguest

    <!-- Image Gallery Modal -->
    <x-image-gallery-modal />

    <!-- Enhanced Theme Toggle Script -->
    <script>
        // Theme management with enhanced animations
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // Smooth transition with enhanced animation
            document.body.style.transition = 'all 0.5s cubic-bezier(0.16, 1, 0.3, 1)';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Enhanced theme icon animation
            updateThemeIcon(newTheme);

            // Add magical sparkle effect
            createEnhancedSparkles();

            // Remove transition after animation
            setTimeout(() => {
                document.body.style.transition = '';
            }, 500);
        }

        function updateThemeIcon(newTheme) {
            const themeIcon = document.querySelector('.theme-icon');
            const slider = document.querySelector('.theme-toggle-slider');

            if (themeIcon && slider) {
                // Scale down with rotation
                slider.style.transform = 'scale(0) rotate(180deg)';
                slider.style.transition = 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)';

                setTimeout(() => {
                    themeIcon.textContent = newTheme === 'dark' ? '🌙' : '☀️';
                    // Scale up with counter-rotation
                    slider.style.transform = newTheme === 'dark'
                        ? 'scale(1) translateX(33px) rotate(0deg)'
                        : 'scale(1) translateX(0px) rotate(0deg)';
                }, 150);
            }
        }

        function createEnhancedSparkles() {
            const toggle = document.querySelector('.theme-toggle');
            const sparkleCount = 8;

            for (let i = 0; i < sparkleCount; i++) {
                const sparkle = document.createElement('div');
                sparkle.innerHTML = ['✨', '⭐', '💫', '🌟'][Math.floor(Math.random() * 4)];
                sparkle.style.position = 'absolute';
                sparkle.style.pointerEvents = 'none';
                sparkle.style.fontSize = Math.random() * 0.5 + 0.5 + 'rem';
                sparkle.style.zIndex = '9999';
                sparkle.style.color = `hsl(${Math.random() * 60 + 200}, 70%, 60%)`;

                const rect = toggle.getBoundingClientRect();
                sparkle.style.left = (rect.left + Math.random() * rect.width) + 'px';
                sparkle.style.top = (rect.top + Math.random() * rect.height) + 'px';

                document.body.appendChild(sparkle);

                // Enhanced sparkle animation
                sparkle.animate([
                    {
                        transform: 'translateY(0) scale(0) rotate(0deg)',
                        opacity: 1
                    },
                    {
                        transform: `translateY(-40px) scale(1.5) rotate(${Math.random() * 360}deg)`,
                        opacity: 1,
                        offset: 0.5
                    },
                    {
                        transform: `translateY(-80px) scale(0) rotate(${Math.random() * 720}deg)`,
                        opacity: 0
                    }
                ], {
                    duration: 1200 + Math.random() * 800,
                    easing: 'cubic-bezier(0.16, 1, 0.3, 1)'
                }).onfinish = () => sparkle.remove();
            }
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;

            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            // Add initial animation
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });

        // Enhanced theme toggle on system preference change
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('theme')) {
                const newTheme = e.matches ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', newTheme);
                updateThemeIcon(newTheme);
            }
        });

        // Add theme change listener for other tabs
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                document.documentElement.setAttribute('data-theme', e.newValue);
                updateThemeIcon(e.newValue);
            }
        });

        // GLOBAL WISHLIST FUNCTIONS
        @auth
        // Globalna funkcja toggle wishlist
        async function toggleWishlist(productId, button) {
            try {
                button.disabled = true;
                const originalContent = button.innerHTML;
                button.innerHTML = '⏳ Ładowanie...';

                const response = await fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                const data = await response.json();

                if (data.success) {
                    // Aktualizuj przycisk
                    const isInWishlist = data.is_in_wishlist;
                    button.innerHTML = isInWishlist ? '💖 W ulubionych' : '❤️ Dodaj do ulubionych';
                    button.setAttribute('data-in-wishlist', isInWishlist);

                    if (isInWishlist) {
                        button.classList.add('in-wishlist');
                    } else {
                        button.classList.remove('in-wishlist');
                    }

                    // Aktualizuj licznik w navbar
                    const counter = document.getElementById('wishlistCounter');
                    if (counter) {
                        counter.textContent = data.wishlist_count;
                        if (data.wishlist_count > 0) {
                            counter.style.display = 'flex';
                        } else {
                            counter.style.display = 'none';
                        }
                    }

                    // Pokaż powiadomienie
                    showWishlistNotification(data.message, data.action);
                } else {
                    button.innerHTML = originalContent;
                    showWishlistNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Błąd wishlist:', error);
                button.innerHTML = originalContent;
                showWishlistNotification('Wystąpił błąd podczas operacji', 'error');
            } finally {
                button.disabled = false;
            }
        }

        function showWishlistNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'added' ? '#10b981' : type === 'removed' ? '#f59e0b' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                z-index: 10000;
                font-weight: 600;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                animation: slideInRight 0.3s ease;
                max-width: 300px;
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease forwards';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Style animacji dla powiadomień
        const notificationStyles = document.createElement('style');
        notificationStyles.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(notificationStyles);
        @endauth
    </script>

    <!-- Enhanced Scripts -->
    <script src="{{ asset('js/auth-modal.js') }}"></script>
    <script src="{{ asset('js/image-gallery-modal.js') }}"></script>

    @stack('scripts')
</body>
</html>
