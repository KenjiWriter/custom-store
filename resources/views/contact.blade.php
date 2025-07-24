@extends('layouts.app')

@section('title', 'Kontakt - Nasz Sklep')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="contact-hero">
        <div class="hero-content">
            <h1>📞 Skontaktuj się z nami</h1>
            <p class="hero-subtitle">Jesteśmy tu, aby Ci pomóc</p>
            <div class="hero-features">
                <div class="feature-item">
                    <span class="feature-icon">⚡</span>
                    <span>Odpowiedź w 2h</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🎯</span>
                    <span>Eksperckie doradztwo</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">💝</span>
                    <span>Indywidualne podejście</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">✅</div>
            <div class="alert-content">
                <h4>Sukces!</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <div class="alert-icon">❌</div>
            <div class="alert-content">
                <h4>Błąd!</h4>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content Grid - POPRAWIONY UKŁAD -->
    <div class="contact-content-grid">
        <!-- Contact Form - Lewa strona -->
        <div class="contact-form-section">
            <div class="form-header">
                <h2>✉️ Napisz do nas</h2>
                <p class="form-description">Wypełnij formularz poniżej, a odpowiemy w ciągu 24 godzin</p>
            </div>

            <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            <span class="label-icon">👤</span>
                            Imię i nazwisko *
                        </label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               placeholder="np. Jan Kowalski">
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <span class="label-icon">📧</span>
                            Email *
                        </label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                               placeholder="np. jan@example.com">
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject">
                        <span class="label-icon">📋</span>
                        Temat wiadomości *
                    </label>
                    <select name="subject" id="subject" required>
                        <option value="">-- Wybierz temat wiadomości --</option>
                        <option value="Pytanie o produkt" {{ old('subject') == 'Pytanie o produkt' ? 'selected' : '' }}>🛍️ Pytanie o produkt</option>
                        <option value="Problem z zamówieniem" {{ old('subject') == 'Problem z zamówieniem' ? 'selected' : '' }}>📦 Problem z zamówieniem</option>
                        <option value="Reklamacja" {{ old('subject') == 'Reklamacja' ? 'selected' : '' }}>⚠️ Reklamacja</option>
                        <option value="Pytanie o dostawę" {{ old('subject') == 'Pytanie o dostawę' ? 'selected' : '' }}>🚚 Pytanie o dostawę</option>
                        <option value="Współpraca biznesowa" {{ old('subject') == 'Współpraca biznesowa' ? 'selected' : '' }}>🤝 Współpraca biznesowa</option>
                        <option value="Wsparcie techniczne" {{ old('subject') == 'Wsparcie techniczne' ? 'selected' : '' }}>🔧 Wsparcie techniczne</option>
                        <option value="Inne" {{ old('subject') == 'Inne' ? 'selected' : '' }}>💬 Inne</option>
                    </select>
                    @error('subject')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">
                        <span class="label-icon">💬</span>
                        Twoja wiadomość *
                    </label>
                    <textarea name="message" id="message" rows="6" required
                              placeholder="Opisz swoją sprawę szczegółowo...">{{ old('message') }}</textarea>
                    <div class="char-count">
                        <span id="charCount">0</span> / 2000 znaków
                    </div>
                    @error('message')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-send">
                        <span class="btn-icon">📤</span>
                        Wyślij wiadomość
                    </button>
                    <p class="form-note">
                        * Pola wymagane. Odpowiemy w ciągu 24 godzin.
                    </p>
                </div>
            </form>
        </div>

        <!-- Contact Info - Prawa strona -->
        <div class="contact-info-section">
            <h2>📍 Dane kontaktowe</h2>

            <div class="contact-info-grid">
                <div class="contact-item">
                    <div class="contact-icon">📧</div>
                    <div class="contact-details">
                        <h3>Email</h3>
                        <p><a href="mailto:kontakt@naszskep.pl">kontakt@naszskep.pl</a></p>
                        <p><a href="mailto:zamowienia@naszskep.pl">zamowienia@naszskep.pl</a></p>
                        <span class="contact-time">Odpowiedź w ciągu 2 godzin</span>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <div class="contact-details">
                        <h3>Telefon</h3>
                        <p><a href="tel:+48123456789">+48 123 456 789</a></p>
                        <p><a href="tel:+48987654321">+48 987 654 321</a></p>
                        <span class="contact-time">Pn-Pt: 8:00-18:00, Sb: 9:00-15:00</span>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">🏢</div>
                    <div class="contact-details">
                        <h3>Adres sklepu</h3>
                        <p>ul. Nowy Świat 22/28<br>00-373 Warszawa<br>Polska</p>
                        <span class="contact-time">Pn-Pt: 9:00-19:00, Sb: 10:00-16:00</span>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">💬</div>
                    <div class="contact-details">
                        <h3>Chat na żywo</h3>
                        <p>Dostępny na naszej stronie</p>
                        <span class="contact-time">24/7 wsparcie online</span>
                        <button class="btn btn-chat" onclick="startLiveChat()">💬 Rozpocznij chat</button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="stat-item">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-info">
                        <span class="stat-number">< 2h</span>
                        <span class="stat-label">Średni czas odpowiedzi</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">😊</div>
                    <div class="stat-info">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Zadowolonych klientów</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <h2 class="section-title">📍 Znajdź nas na mapie</h2>
        <p class="section-description">Odwiedź nasz sklep stacjonarny w centrum Warszawy</p>

        <div class="map-container">
            <div class="map-info">
                <div class="location-card">
                    <h3>🏢 Nasz sklep</h3>
                    <div class="location-details">
                        <div class="location-item">
                            <span class="detail-icon">📍</span>
                            <span>ul. Nowy Świat 22/28, 00-373 Warszawa</span>
                        </div>
                        <div class="location-item">
                            <span class="detail-icon">🚇</span>
                            <span>Metro: Nowy Świat-Uniwersytet (2 min pieszo)</span>
                        </div>
                        <div class="location-item">
                            <span class="detail-icon">🚌</span>
                            <span>Autobus: 111, 116, 180, 222</span>
                        </div>
                        <div class="location-item">
                            <span class="detail-icon">🚊</span>
                            <span>Tramwaj: 7, 9, 24, 25</span>
                        </div>
                        <div class="location-item">
                            <span class="detail-icon">🅿️</span>
                            <span>Parking płatny - strefa A</span>
                        </div>
                        <div class="location-item">
                            <span class="detail-icon">♿</span>
                            <span>Dostęp dla osób niepełnosprawnych</span>
                        </div>
                    </div>

                    <div class="location-actions">
                        <a href="https://maps.google.com/?q=Nowy+Świat+22/28,+Warszawa" target="_blank" class="btn btn-map">
                            🗺️ Otwórz w Google Maps
                        </a>
                        <a href="https://jakdojade.pl/?fn=Nowy%20%C5%9Awiat%2022%2F28%2C%20Warszawa&ft=&td=26.07.2024&tt=21%3A37&ua=1" target="_blank" class="btn btn-directions">
                            🚌 Jak dojechać
                        </a>
                    </div>
                </div>
            </div>

            <div class="map-embed">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2442.7876659771754!2d21.015372716132567!3d52.23425457976412!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ecc60f8b3b433%3A0x72f8b88ccb2e9b3a!2sNowy%20%C5%9Awiat%2022%2F28%2C%2000-373%20Warszawa!5e0!3m2!1spl!2spl!4v1642678901234!5m2!1spl!2spl"
                    width="100%"
                    height="450"
                    style="border:0; border-radius: 16px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h2 class="section-title">❓ Często zadawane pytania</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <h3>🚚 Ile kosztuje dostawa?</h3>
                <p>Dostawa jest bezpłatna przy zamówieniach powyżej 99 zł. Poniżej tej kwoty koszt dostawy to 9,99 zł. Oferujemy również odbiór osobisty w naszym sklepie.</p>
            </div>
            <div class="faq-item">
                <h3>⏰ Ile trwa realizacja zamówienia?</h3>
                <p>Standardowa dostawa trwa 1-2 dni roboczych. Oferujemy także dostawę ekspresową w ciągu 24h za dodatkową opłatą 19,99 zł.</p>
            </div>
            <div class="faq-item">
                <h3>🔄 Czy mogę zwrócić produkt?</h3>
                <p>Tak, masz 30 dni na zwrot produktu bez podania przyczyny. Zwrot jest bezpłatny, a pieniądze otrzymasz w ciągu 7 dni roboczych.</p>
            </div>
            <div class="faq-item">
                <h3>💳 Jakie formy płatności akceptujecie?</h3>
                <p>Akceptujemy płatności kartą, BLIK, przelewy bankowe, PayPal oraz płatność za pobraniem. Wszystkie płatności są zabezpieczone certyfikatem SSL.</p>
            </div>
            <div class="faq-item">
                <h3>🛡️ Czy produkty mają gwarancję?</h3>
                <p>Wszystkie nasze produkty objęte są pełną gwarancją producenta. Dodatkowo świadczymy bezpłatny serwis gwarancyjny przez cały okres gwarancji.</p>
            </div>
            <div class="faq-item">
                <h3>🏪 Czy mają Państwo sklep stacjonarny?</h3>
                <p>Tak, nasz sklep znajdziesz w centrum Warszawy przy ul. Nowy Świat 22/28. Zapraszamy od poniedziałku do piątku 9:00-19:00, w soboty 10:00-16:00.</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Contact Page Enhanced Styles - POPRAWIONY SYMETRYCZNY UKŁAD */
.contact-hero {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 24px;
    padding: 4rem 2rem;
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 50px var(--shadow-color);
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 25% 25%, var(--glow-color) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-content h1 {
    font-size: 3.2rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: var(--text-primary);
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2.5rem;
    color: var(--text-secondary);
}

.hero-features {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

.feature-icon {
    font-size: 1.5rem;
}

.alert {
    background: var(--bg-card);
    border: 2px solid;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 8px 25px var(--shadow-color);
}

.alert-success {
    border-color: #10b981;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, transparent 100%);
}

.alert-error {
    border-color: #ef4444;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, transparent 100%);
}

.alert-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.alert-content h4 {
    color: var(--text-primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.alert-content p {
    color: var(--text-secondary);
    margin: 0;
}

/* POPRAWIONY GŁÓWNY GRID - IDEALNIE SYMETRYCZNY */
.contact-content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr; /* RÓWNE KOLUMNY 50/50 */
    gap: 3rem;
    margin-bottom: 4rem;
    align-items: stretch; /* WYRÓWNANIE WYSOKOŚCI */
}

.contact-form-section,
.contact-info-section {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 40px var(--shadow-color);
    height: 100%; /* JEDNAKOWA WYSOKOŚĆ */
    display: flex;
    flex-direction: column;
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
    flex-shrink: 0; /* NIE KURCZY SIĘ */
}

.form-header h2,
.contact-info-section h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.form-description {
    color: var(--text-secondary);
    font-size: 1rem;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    flex: 1; /* ZAJMUJE DOSTĘPNĄ PRZESTRZEŃ */
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.label-icon {
    font-size: 1.1rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px var(--glow-color);
    transform: translateY(-1px);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.char-count {
    text-align: right;
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-top: 0.5rem;
}

.error {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.error::before {
    content: '⚠️';
}

.form-actions {
    text-align: center;
    margin-top: auto; /* WYPYCHA NA DÓŁ */
    flex-shrink: 0;
}

.btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: inherit;
}

.btn-send {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    box-shadow: 0 4px 15px var(--glow-color);
    margin-bottom: 1rem;
}

.btn-send:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--glow-color);
}

.btn-send:active {
    transform: translateY(0);
}

.form-note {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-style: italic;
    margin: 0;
}

/* WYRÓWNANIE PRAWEJ KOLUMNY */
.contact-info-section h2 {
    text-align: center;
    margin-bottom: 2rem;
    flex-shrink: 0;
}

.contact-info-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    flex: 1;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    transition: all 0.3s ease;
}

.contact-item:hover {
    transform: translateY(-2px);
    border-color: var(--accent-primary);
    box-shadow: 0 8px 25px var(--shadow-color);
}

.contact-icon {
    font-size: 2rem;
    flex-shrink: 0;
    width: 50px;
    text-align: center;
}

.contact-details h3 {
    color: var(--accent-primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.contact-details p {
    color: var(--text-secondary);
    margin-bottom: 0.3rem;
    line-height: 1.4;
}

.contact-details a {
    color: var(--text-primary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-details a:hover {
    color: var(--accent-primary);
}

.contact-time {
    font-size: 0.9rem;
    color: var(--text-secondary);
    font-style: italic;
}

.btn-chat {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.btn-chat:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.quick-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    margin-top: auto; /* WYPYCHA NA DÓŁ */
    flex-shrink: 0;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.stat-icon {
    font-size: 1.5rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-weight: 800;
    color: var(--accent-primary);
    font-size: 1.1rem;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.map-section {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem;
    margin-bottom: 3rem;
    box-shadow: 0 10px 40px var(--shadow-color);
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.section-description {
    text-align: center;
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 3rem;
}

.map-container {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 2rem;
    align-items: start;
}

.location-card {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
}

.location-card h3 {
    color: var(--accent-primary);
    font-weight: 800;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
}

.location-details {
    margin-bottom: 2rem;
}

.location-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
}

.detail-icon {
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
    flex-shrink: 0;
}

.location-item span:last-child {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.4;
}

.location-actions {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.btn-map,
.btn-directions {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    text-align: center;
    padding: 0.8rem 1.5rem;
    font-size: 0.95rem;
}

.btn-directions {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.btn-map:hover,
.btn-directions:hover {
    transform: translateY(-2px);
}

.map-embed {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 25px var(--shadow-color);
}

.faq-section {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 40px var(--shadow-color);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.faq-item {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-3px);
    border-color: var(--accent-primary);
    box-shadow: 0 8px 25px var(--shadow-color);
}

.faq-item h3 {
    color: var(--accent-primary);
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.faq-item p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}

/* RESPONSIVE DESIGN - ZACHOWUJE SYMETRIĘ */
@media (max-width: 1200px) {
    .contact-content-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .map-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .contact-hero {
        padding: 3rem 1.5rem;
    }

    .hero-content h1 {
        font-size: 2.5rem;
    }

    .hero-features {
        flex-direction: column;
        gap: 1.5rem;
    }

    .contact-form-section,
    .contact-info-section,
    .map-section {
        padding: 2rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .section-title {
        font-size: 2rem;
    }

    .faq-grid {
        grid-template-columns: 1fr;
    }

    .quick-stats {
        grid-template-columns: 1fr;
    }

    .location-actions {
        flex-direction: row;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .hero-features {
        align-items: center;
    }

    .contact-form-section,
    .contact-info-section,
    .map-section {
        padding: 1.5rem;
    }

    .location-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for message textarea
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    if (messageTextarea && charCount) {
        function updateCharCount() {
            const count = messageTextarea.value.length;
            charCount.textContent = count;

            if (count > 1800) {
                charCount.style.color = '#ef4444';
            } else if (count > 1500) {
                charCount.style.color = '#f59e0b';
            } else {
                charCount.style.color = 'var(--text-secondary)';
            }
        }

        messageTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count
    }

    // Enhanced form submission
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="btn-icon">⏳</span> Wysyłanie...';
                submitBtn.disabled = true;
            }
        });
    }

    // Animate FAQ items on scroll
    const faqItems = document.querySelectorAll('.faq-item');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const faqObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    faqItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `all 0.6s ease ${index * 0.1}s`;
        faqObserver.observe(item);
    });

    // Enhanced contact item animations
    const contactItems = document.querySelectorAll('.contact-item');
    contactItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = `all 0.5s ease ${index * 0.1}s`;

        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, 100 + index * 100);
    });
});

// Live chat function
function startLiveChat() {
    // Animate button
    const chatBtn = event.target;
    chatBtn.innerHTML = '⏳ Łączenie...';
    chatBtn.disabled = true;

    setTimeout(() => {
        alert('Funkcja chatu zostanie wkrótce uruchomiona!\n\nW międzyczasie możesz:\n• Napisać do nas przez formularz\n• Zadzwonić: +48 123 456 789\n• Wysłać email: kontakt@naszskep.pl');
        chatBtn.innerHTML = '💬 Rozpocznij chat';
        chatBtn.disabled = false;
    }, 1500);
}
</script>
@endsection
