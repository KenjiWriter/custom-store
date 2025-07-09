<div id="authModal" class="auth-modal" style="display: none;">
    <div class="auth-modal-content">
        <div class="auth-modal-header">
            <h2 id="authModalTitle">Wymagane logowanie</h2>
            <span class="auth-close-btn" onclick="closeAuthModal()">&times;</span>
        </div>
        
        <div class="auth-modal-body">
            <div class="auth-modal-message">
                <div class="auth-icon">🔐</div>
                <p id="authModalMessage">Aby wykonać tę akcję, musisz się zalogować.</p>
            </div>
            
            <div class="auth-modal-actions">
                <a href="{{ route('login') }}" class="btn btn-primary auth-login-btn">
                    🔑 Zaloguj się
                </a>
                <a href="{{ route('register') }}" class="btn btn-secondary auth-register-btn">
                    📝 Zarejestruj się
                </a>
                <button class="btn btn-outline auth-cancel-btn" onclick="closeAuthModal()">
                    ❌ Anuluj
                </button>
            </div>
            
            <div class="auth-modal-benefits">
                <h4>Korzyści z posiadania konta:</h4>
                <ul>
                    <li>✅ Szybsze zakupy</li>
                    <li>💖 Lista ulubionych produktów</li>
                    <li>📦 Śledzenie zamówień</li>
                    <li>🎁 Ekskluzywne oferty</li>
                    <li>📧 Powiadomienia o dostępności</li>
                </ul>
            </div>
        </div>
    </div>
</div>