@extends('layouts.app')

@section('title', 'O nas - Nasz Sklep')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="about-hero">
        <div class="hero-content">
            <h1>ℹ️ O naszym sklepie</h1>
            <p class="hero-subtitle">Poznaj naszą historię i wartości</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">5+</span>
                    <span class="stat-label">lat doświadczenia</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">10k+</span>
                    <span class="stat-label">zadowolonych klientów</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">99%</span>
                    <span class="stat-label">pozytywnych opinii</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED - Main Content Sections (bez grid, jedna pod drugą) -->
    <!-- Our Story -->
    <div class="content-section story-section">
        <div class="section-icon">📖</div>
        <h2>Nasza historia</h2>
        <div class="story-timeline">
            <div class="timeline-item">
                <div class="timeline-year">2019</div>
                <div class="timeline-content">
                    <h3>🌱 Początki</h3>
                    <p>Rozpoczęliśmy działalność jako mały sklep internetowy z pasją do technologii i jakości obsługi klientów.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2021</div>
                <div class="timeline-content">
                    <h3>🚀 Rozwój</h3>
                    <p>Rozszerzyliśmy asortyment i wprowadziliśmy innowacyjne rozwiązania logistyczne. Osiągnęliśmy 1000 zadowolonych klientów.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2023</div>
                <div class="timeline-content">
                    <h3>🏆 Liderzy</h3>
                    <p>Staliśmy się jednym z najpopularniejszych sklepów online w Polsce. Wprowadziliśmy autorskie rozwiązania technologiczne.</p>
                </div>
            </div>
            <div class="timeline-item current">
                <div class="timeline-year">2025</div>
                <div class="timeline-content">
                    <h3>✨ Teraźniejszość</h3>
                    <p>Dziś jesteśmy tu dla Ciebie - z najlepszymi produktami, najszybszą dostawą i najlepszą obsługą klienta.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission & Values -->
    <div class="content-section values-section">
        <div class="section-icon">🎯</div>
        <h2>Nasza misja i wartości</h2>

        <div class="mission-statement">
            <blockquote>
                "Naszą misją jest dostarczanie najwyższej jakości produktów w połączeniu z wyjątkową obsługą klienta. Wierzymy, że każdy zakup powinien być przyjemnym doświadczeniem."
            </blockquote>
            <cite>— Zespół Nasz Sklep</cite>
        </div>

        <div class="values-grid">
            <div class="value-item">
                <div class="value-icon">🔍</div>
                <h3>Jakość</h3>
                <p>Oferujemy tylko sprawdzone produkty od renomowanych marek. Każdy artykuł przechodzi kontrolę jakości.</p>
            </div>
            <div class="value-item">
                <div class="value-icon">⚡</div>
                <h3>Szybkość</h3>
                <p>Ekspresowa realizacja zamówień i błyskawiczna dostawa. Większość produktów wysyłamy w ciągu 24 godzin.</p>
            </div>
            <div class="value-item">
                <div class="value-icon">💙</div>
                <h3>Zaufanie</h3>
                <p>Budujemy długotrwałe relacje z klientami oparte na transparentności i uczciwości w każdej transakcji.</p>
            </div>
            <div class="value-item">
                <div class="value-icon">🌟</div>
                <h3>Innowacyjność</h3>
                <p>Nieustannie wprowadzamy nowe rozwiązania technologiczne dla lepszego doświadczenia zakupowego.</p>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="team-section">
        <h2 class="section-title">🏢 Nasz zespół</h2>
        <p class="section-description">Poznaj ludzi, którzy codziennie pracują dla Twojej satysfakcji</p>

        <div class="team-grid">
            <div class="team-member">
                <div class="member-avatar">👨‍💼</div>
                <h3>Jan Kowalski</h3>
                <p class="member-role">CEO & Założyciel</p>
                <p class="member-description">Pasjonat technologii z 10-letnim doświadczeniem w e-commerce. Wizjoner, który zapoczątkował naszą przygodę.</p>
            </div>

            <div class="team-member">
                <div class="member-avatar">👩‍💻</div>
                <h3>Anna Nowak</h3>
                <p class="member-role">Dyrektor ds. Technologii</p>
                <p class="member-description">Architekt naszych rozwiązań technicznych. Odpowiada za bezpieczeństwo i wydajność platformy.</p>
            </div>

            <div class="team-member">
                <div class="member-avatar">👨‍🔧</div>
                <h3>Michał Wiśniewski</h3>
                <p class="member-role">Kierownik Obsługi Klienta</p>
                <p class="member-description">Ekspert w rozwiązywaniu problemów klientów. Gwarantuje najwyższą jakość obsługi każdego dnia.</p>
            </div>

            <div class="team-member">
                <div class="member-avatar">👩‍📦</div>
                <h3>Katarzyna Zielińska</h3>
                <p class="member-role">Kierownik Logistyki</p>
                <p class="member-description">Czuwa nad tym, aby każda przesyłka dotarła do Ciebie szybko i bezpiecznie. Mistrzyni organizacji.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="cta-section">
        <div class="cta-content">
            <h2>Gotowy na zakupy?</h2>
            <p>Dołącz do tysięcy zadowolonych klientów i przekonaj się, dlaczego jesteśmy numerem jeden!</p>
            <div class="cta-buttons">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    🛍️ Przeglądaj produkty
                </a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">
                    💬 Porozmawiaj z nami
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* About Page Styles - FIXED LAYOUT */
.about-hero {
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

.about-hero::before {
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
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--accent-primary);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
    font-weight: 600;
}

/* FIXED - Każda sekcja jako osobny blok */
.content-section {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 40px var(--shadow-color);
    transition: all 0.3s ease;
    margin-bottom: 3rem; /* DODANE - margines między sekcjami */
}

.content-section:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px var(--shadow-color);
    border-color: var(--accent-primary);
}

.section-icon {
    font-size: 3rem;
    text-align: center;
    margin-bottom: 1.5rem;
}

.content-section h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 2rem;
    text-align: center;
}

.story-timeline {
    position: relative;
}

.story-timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, var(--accent-primary), var(--accent-secondary));
    border-radius: 3px;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    margin-bottom: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 8px;
    width: 24px;
    height: 24px;
    background: var(--bg-card);
    border: 4px solid var(--accent-primary);
    border-radius: 50%;
    box-shadow: 0 4px 12px var(--glow-color);
}

.timeline-item.current::before {
    background: var(--accent-primary);
    animation: pulse 2s ease-in-out infinite;
}

.timeline-year {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--accent-primary);
    margin-bottom: 0.5rem;
}

.timeline-content h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.timeline-content p {
    color: var(--text-secondary);
    line-height: 1.6;
}

.mission-statement {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
}

.mission-statement blockquote {
    font-size: 1.2rem;
    line-height: 1.6;
    color: var(--text-primary);
    font-weight: 500;
    margin: 0 0 1rem 0;
    font-style: italic;
}

.mission-statement cite {
    color: var(--accent-primary);
    font-weight: 700;
    font-style: normal;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.value-item {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.value-item:hover {
    transform: translateY(-3px);
    border-color: var(--accent-primary);
    box-shadow: 0 8px 25px var(--shadow-color);
}

.value-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.value-item h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.value-item p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.5;
}

.team-section {
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

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.team-member {
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.team-member:hover {
    transform: translateY(-5px);
    border-color: var(--accent-primary);
    box-shadow: 0 12px 30px var(--shadow-color);
}

.member-avatar {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.team-member h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.member-role {
    color: var(--accent-primary);
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.member-description {
    color: var(--text-secondary);
    line-height: 1.5;
    font-size: 0.95rem;
}

.cta-section {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    border-radius: 24px;
    padding: 4rem 2rem;
    text-align: center;
    color: white;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
}

.cta-content {
    position: relative;
    z-index: 2;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
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
}

.btn-primary {
    background: white;
    color: var(--accent-primary);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-hero {
        padding: 3rem 1.5rem;
    }

    .hero-content h1 {
        font-size: 2.5rem;
    }

    .hero-stats {
        gap: 2rem;
    }

    .content-section {
        padding: 2rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .cta-content h2 {
        font-size: 2rem;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .values-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .hero-stats {
        flex-direction: column;
        gap: 1.5rem;
    }

    .timeline-item {
        padding-left: 40px;
    }

    .content-section {
        padding: 1.5rem;
    }
}
</style>
@endsection
