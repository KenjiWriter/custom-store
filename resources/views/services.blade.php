@extends('layouts.app')

@section('title', 'Nasze Usługi - Nasz Sklep')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="services-hero">
        <div class="hero-content">
            <h1>🔧 Nasze Usługi</h1>
            <p class="hero-subtitle">Kompleksowa obsługa w każdym zakupie</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Wsparcie</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Zadowolonych klientów</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Satysfakcji</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="services-section">
        <h2 class="section-title">Co dla Ciebie robimy?</h2>
        <div class="services-grid">
            <!-- Dostawa -->
            <div class="service-card featured">
                <div class="service-badge">Najpopularniejsze</div>
                <div class="service-icon">🚚</div>
                <h3>Ekspresowa Dostawa</h3>
                <p>Szybka i bezpieczna dostawa na terenie całego kraju. Śledzenie przesyłki w czasie rzeczywistym oraz możliwość odbioru osobistego.</p>
                <ul class="service-features">
                    <li>✅ Dostawa w 24h na terenie Polski</li>
                    <li>✅ Bezpłatna dostawa od 99 zł</li>
                    <li>✅ Śledzenie przesyłki online</li>
                    <li>✅ Odbiór osobisty w sklepie</li>
                    <li>✅ Dostawa w weekendy</li>
                </ul>
            </div>

            <!-- Wsparcie -->
            <div class="service-card">
                <div class="service-icon">💬</div>
                <h3>Obsługa Klienta 24/7</h3>
                <p>Profesjonalne wsparcie techniczne dostępne przez całą dobę. Pomoc przez telefon, chat na żywo oraz email.</p>
                <ul class="service-features">
                    <li>✅ Chat na żywo na stronie</li>
                    <li>✅ Wsparcie telefoniczne</li>
                    <li>✅ Odpowiedź na email w 2h</li>
                    <li>✅ Baza wiedzy i FAQ</li>
                    <li>✅ Zdalne wsparcie techniczne</li>
                </ul>
            </div>

            <!-- Gwarancja -->
            <div class="service-card">
                <div class="service-icon">🛡️</div>
                <h3>Gwarancja i Serwis</h3>
                <p>Pełna gwarancja producenta na wszystkie produkty oraz szybki i profesjonalny serwis pogwarancyjny.</p>
                <ul class="service-features">
                    <li>✅ Gwarancja producenta</li>
                    <li>✅ Bezpłatny serwis gwarancyjny</li>
                    <li>✅ Szybka wymiana wadliwego towaru</li>
                    <li>✅ Autoryzowany punkt serwisowy</li>
                    <li>✅ Oryginalne części zamienne</li>
                </ul>
            </div>

            <!-- Montaż -->
            <div class="service-card">
                <div class="service-icon">🔧</div>
                <h3>Montaż i Konfiguracja</h3>
                <p>Profesjonalny montaż i konfiguracja zakupionych urządzeń przez naszych wykwalifikowanych specjalistów.</p>
                <ul class="service-features">
                    <li>✅ Montaż w domu klienta</li>
                    <li>✅ Konfiguracja urządzeń</li>
                    <li>✅ Instruktaż obsługi</li>
                    <li>✅ Test działania systemu</li>
                    <li>✅ Wsparcie posprzedażowe</li>
                </ul>
            </div>

            <!-- Zwroty -->
            <div class="service-card">
                <div class="service-icon">🔄</div>
                <h3>Łatwe Zwroty</h3>
                <p>30 dni na zwrot towaru bez podania przyczyny. Prosty proces zwrotu i szybki zwrot pieniędzy.</p>
                <ul class="service-features">
                    <li>✅ 30 dni na zwrot</li>
                    <li>✅ Bez podania przyczyny</li>
                    <li>✅ Bezpłatne zwroty</li>
                    <li>✅ Zwrot pieniędzy w 7 dni</li>
                    <li>✅ Prosty formularz online</li>
                </ul>
            </div>

            <!-- Doradztwo -->
            <div class="service-card">
                <div class="service-icon">💡</div>
                <h3>Doradztwo Techniczne</h3>
                <p>Bezpłatne doradztwo przy wyborze produktów. Nasi eksperci pomogą Ci wybrać najlepsze rozwiązanie.</p>
                <ul class="service-features">
                    <li>✅ Bezpłatne konsultacje</li>
                    <li>✅ Dobór produktów do potrzeb</li>
                    <li>✅ Porównanie specyfikacji</li>
                    <li>✅ Rekomendacje ekspertów</li>
                    <li>✅ Planowanie budżetu</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Process Section -->
    <div class="process-section">
        <h2 class="section-title">Jak to działa?</h2>
        <div class="process-steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>📞 Skontaktuj się</h3>
                    <p>Zadzwoń, napisz lub odwiedź nas w sklepie. Odpowiemy na wszystkie pytania.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>🛍️ Wybierz produkty</h3>
                    <p>Pomożemy Ci wybrać najlepsze produkty dopasowane do Twoich potrzeb.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>🚚 Otrzymaj zamówienie</h3>
                    <p>Szybka dostawa lub odbiór osobisty. Sprawdzisz produkt przed płatnością.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>😊 Ciesz się zakupem</h3>
                    <p>Pełne wsparcie po zakupie. Jesteśmy zawsze do Twojej dyspozycji.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="services-cta">
        <div class="cta-content">
            <h2>Masz pytania o nasze usługi?</h2>
            <p>Skontaktuj się z nami już dziś i przekonaj się o jakości naszej obsługi!</p>
            <div class="cta-buttons">
                <a href="{{ route('contact') }}" class="btn btn-primary cta-btn">
                    📞 Skontaktuj się
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary cta-btn">
                    🛍️ Zobacz produkty
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Services Page Styles */
.services-hero {
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

.services-hero::before {
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

.hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 3rem;
    opacity: 0.9;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    display: block;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.services-section {
    margin-bottom: 4rem;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 3rem;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    border-radius: 2px;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.service-card {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 2.5rem;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 30px var(--shadow-color);
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 0;
}

.service-card:hover::before {
    opacity: 0.05;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px var(--shadow-color);
    border-color: var(--accent-primary);
}

.service-card.featured {
    border-color: var(--accent-primary);
    box-shadow: 0 12px 40px var(--glow-color);
    transform: scale(1.02);
}

.service-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 2;
}

.service-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    display: block;
    filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
    position: relative;
    z-index: 1;
}

.service-card h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.service-card p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    line-height: 1.6;
    position: relative;
    z-index: 1;
}

.service-features {
    list-style: none;
    padding: 0;
    margin-bottom: 2rem;
    text-align: left;
    position: relative;
    z-index: 1;
}

.service-features li {
    padding: 0.5rem 0;
    color: var(--text-primary);
    font-size: 0.9rem;
    border-bottom: 1px solid var(--border-color);
}

.service-features li:last-child {
    border-bottom: none;
}

.process-section {
    background: var(--bg-card);
    padding: 4rem 2rem;
    border-radius: 24px;
    margin-bottom: 4rem;
    border: 2px solid var(--border-color);
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.step {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--bg-primary);
    border-radius: 16px;
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
}

.step:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px var(--shadow-color);
    border-color: var(--accent-primary);
}

.step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 800;
    flex-shrink: 0;
    box-shadow: 0 8px 20px var(--glow-color);
}

.step-content h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.step-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.services-cta {
    background: var(--bg-card);
    border-radius: 24px;
    padding: 4rem 2rem;
    text-align: center;
    border: 2px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.services-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    opacity: 0.05;
    pointer-events: none;
}

.cta-content h2 {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.cta-btn {
    padding: 1rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 16px;
    transition: all 0.3s ease;
    text-decoration: none;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    box-shadow: 0 4px 15px var(--glow-color);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--glow-color);
}

.btn-secondary {
    background: var(--bg-primary);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--bg-secondary);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-color);
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }

    .hero-stats {
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .services-hero {
        padding: 3rem 1.5rem;
    }

    .hero-content h1 {
        font-size: 2rem;
    }

    .services-grid {
        grid-template-columns: 1fr;
    }

    .process-steps {
        grid-template-columns: 1fr;
    }

    .step {
        flex-direction: column;
        text-align: center;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endsection
