<!-- Modal galerii obrazków z ulepszoną nawigacją -->
<div id="imageModal" class="image-modal" style="display: none;">
    <!-- Tło z efektem blur -->
    <div class="modal-backdrop"></div>

    <!-- NOWE - Nawigacja POZA modal-content aby była zawsze widoczna -->
    <div class="modal-navigation-container">
        <!-- Lewa strzałka -->
        <button class="modal-nav-btn modal-nav-prev" onclick="modalPreviousImage(event)">
            <div class="nav-arrow-container">
                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
                <div class="nav-ripple"></div>
            </div>
            <span class="nav-tooltip">Poprzedni</span>
        </button>

        <!-- Prawa strzałka -->
        <button class="modal-nav-btn modal-nav-next" onclick="modalNextImage(event)">
            <div class="nav-arrow-container">
                <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
                <div class="nav-ripple"></div>
            </div>
            <span class="nav-tooltip">Następny</span>
        </button>
    </div>

    <!-- Główna zawartość modala -->
    <div class="modal-content">
        <!-- Przycisk zamknięcia -->
        <button class="modal-close-btn" onclick="closeImageModal()">
            <span class="close-icon">✕</span>
        </button>

        <!-- Główny obrazek -->
        <div class="modal-image-container">
            <img id="modalImage" src="" alt="" class="modal-main-image">

            <!-- Loader -->
            <div class="modal-loader" id="modalLoader">
                <div class="loader-spinner"></div>
                <span>Ładowanie...</span>
            </div>
        </div>

        <!-- Licznik obrazków -->
        <div class="modal-counter" id="modalCounter">1 / 1</div>

        <!-- Miniaturki (dla produktów z wieloma zdjęciami) -->
        <div class="modal-thumbnails" id="modalThumbnails" style="display: none;">
            <!-- Generowane dynamicznie przez JS -->
        </div>

        <!-- Informacje o produkcie -->
        <div class="modal-product-info" id="modalProductInfo" style="display: none;">
            <h3 class="modal-product-name" id="modalProductName"></h3>
            <p class="modal-product-price" id="modalProductPrice"></p>
            <div class="modal-product-actions">
                <a href="#" class="modal-btn modal-btn-primary" id="modalProductLink">
                    👁️ Zobacz produkt
                </a>
                <button class="modal-btn modal-btn-secondary" onclick="closeImageModal()">
                    ✕ Zamknij
                </button>
            </div>
        </div>
    </div>

    <!-- Efekty parallax w tle -->
    <div class="modal-parallax-bg">
        <div class="parallax-layer parallax-layer-1"></div>
        <div class="parallax-layer parallax-layer-2"></div>
        <div class="parallax-layer parallax-layer-3"></div>
    </div>
</div>
