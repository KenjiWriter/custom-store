<div id="authModal" class="auth-modal" style="display: none;">
    <div class="auth-modal-loading" id="authModalLoading">
        <div class="auth-modal-spinner"></div>
    </div>

    <div class="auth-modal-content">
        <div class="auth-modal-header">
            <h2 id="authModalTitle">Wymagane logowanie</h2>
            <button class="auth-close-btn" onclick="closeAuthModal()" aria-label="Zamknij modal">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="auth-modal-body">
            <div class="auth-modal-message">
                <div class="auth-icon">🔐</div>
                <p id="authModalMessage">Aby wykonać tę akcję, musisz się zalogować.</p>
            </div>

            <div class="auth-modal-actions">
                <a href="{{ route('login') }}" class="btn btn-primary auth-login-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-5-4l5-5-5-5m5 5H3"/>
                    </svg>
                    Zaloguj się
                </a>
                <a href="{{ route('register') }}" class="btn btn-secondary auth-register-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Zarejestruj się
                </a>
                <button class="btn btn-outline auth-cancel-btn" onclick="closeAuthModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Anuluj
                </button>
            </div>

            <div class="auth-modal-benefits">
                <h4>🎁 Korzyści z posiadania konta:</h4>
                <ul>
                    <li>Szybsze i wygodniejsze zakupy</li>
                    <li>Personalizowana lista ulubionych</li>
                    <li>Śledzenie statusu zamówień</li>
                    <li>Ekskluzywne oferty i rabaty</li>
                    <li>Powiadomienia o dostępności produktów</li>
                    <li>Historia zakupów i faktury</li>
                </ul>
            </div>
        </div>
    </div>
</div>
