<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sklep - Strona Główna')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
    @stack('styles')
</head>
<body>
    <!-- Nawigacja -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo">
                🛍️ Nasz Sklep
            </a>
            
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">Strona główna</a>
                <a href="#" class="nav-link">Produkty</a>
                <a href="#" class="nav-link">Kategorie</a>
                <a href="#" class="nav-link">O nas</a>
                <a href="#" class="nav-link">Kontakt</a>
            </div>
            
            <div class="auth-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Panel</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Wyloguj</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Zaloguj</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Zarejestruj</a>
                @endauth
            </div>
            
            <button class="mobile-menu-btn">☰</button>
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
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2443.2879394798897!2d21.01178931574819!3d52.22967797976119!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ecc63b1a56d85%3A0x4e7c0c0c0c0c0c0c!2sWarszawa!5e0!3m2!1spl!2spl!4v1234567890123" 
                        width="100%" 
                        height="150" 
                        style="border:0; border-radius: 8px;" 
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
    
    @stack('scripts')
</body>
</html>