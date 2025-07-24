@extends('layouts.app')

@section('title', 'Profil użytkownika')

@push('styles')
<style>
/* Profile styles */
.profile-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.profile-header {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    padding: 2rem;
    border-radius: 16px;
    text-align: center;
    margin-bottom: 2rem;
}

.profile-header h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
}

.profile-cards {
    display: grid;
    gap: 2rem;
}

.profile-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px var(--shadow-color);
}

.profile-card h2 {
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    background: var(--bg-secondary);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px var(--glow-color);
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
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

.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(231, 76, 60, 0.5);
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.error {
    color: #e74c3c;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.danger-zone {
    margin-top: 3rem;
    padding: 2rem;
    border: 2px dashed #e74c3c;
    border-radius: 12px;
    background: rgba(231, 76, 60, 0.05);
}

.danger-zone h3 {
    color: #e74c3c;
    margin-bottom: 1rem;
}

.user-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 12px;
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .profile-container {
        padding: 0 0.5rem;
    }

    .profile-card {
        padding: 1.5rem;
    }

    .user-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endpush

@section('content')
<div class="profile-container">
    <!-- Header profilu -->
    <div class="profile-header">
        <h1>🔧 Profil użytkownika</h1>
        <p>Zarządzaj swoimi danymi osobowymi i ustawieniami konta</p>

        @auth
            <div class="user-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ auth()->user()->orders()->count() }}</div>
                    <div class="stat-label">Zamówień</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ auth()->user()->wishlist_count }}</div>
                    <div class="stat-label">Ulubionych</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ auth()->user()->cart_count }}</div>
                    <div class="stat-label">W koszyku</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ auth()->user()->created_at->format('Y') }}</div>
                    <div class="stat-label">Rok dołączenia</div>
                </div>
            </div>
        @endauth
    </div>

    <div class="profile-cards">
        <!-- Edycja danych profilu -->
        <div class="profile-card">
            <h2>📝 Informacje osobiste</h2>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success">
                    ✅ Profil został zaktualizowany pomyślnie!
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="name">Nazwa użytkownika:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Adres email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div style="margin-top: 0.5rem;">
                            <p style="font-size: 0.875rem; color: #f39c12;">
                                ⚠️ Twój adres email nie został zweryfikowany.
                                <button form="send-verification" style="color: #3498db; text-decoration: underline; background: none; border: none; cursor: pointer;">
                                    Kliknij tutaj, aby ponownie wysłać email weryfikacyjny.
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p style="font-size: 0.875rem; color: #27ae60; margin-top: 0.5rem;">
                                    ✅ Nowy link weryfikacyjny został wysłany na Twój adres email.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">
                    💾 Zapisz zmiany
                </button>
            </form>
        </div>

        <!-- Zmiana hasła -->
        <div class="profile-card">
            <h2>🔒 Zmiana hasła</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Aktualne hasło:</label>
                    <input type="password" id="current_password" name="current_password" required>
                    @error('current_password', 'updatePassword')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Nowe hasło:</label>
                    <input type="password" id="password" name="password" required>
                    @error('password', 'updatePassword')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Potwierdź nowe hasło:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                    @error('password_confirmation', 'updatePassword')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    🔐 Zmień hasło
                </button>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success" style="margin-top: 1rem;">
                        ✅ Hasło zostało zmienione pomyślnie!
                    </div>
                @endif
            </form>
        </div>

        <!-- Strefa niebezpieczna -->
        <div class="profile-card">
            <div class="danger-zone">
                <h3>⚠️ Strefa niebezpieczna</h3>
                <p>Usunięcie konta jest nieodwracalne. Wszystkie Twoje dane, zamówienia, ulubione produkty i koszyk zostaną na zawsze usunięte.</p>

                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('⚠️ CZY JESTEŚ PEWIEN?\n\nTo działanie jest nieodwracalne!\nWszystkie Twoje dane zostaną usunięte:\n- Historia zamówień\n- Ulubione produkty\n- Koszyk\n- Dane osobowe\n\nNapisz USUŃ w polu hasła aby potwierdzić.')">
                    @csrf
                    @method('DELETE')

                    <div class="form-group">
                        <label for="password">Potwierdź usunięcie wpisując swoje hasło:</label>
                        <input type="password" id="password" name="password" required placeholder="Wprowadź hasło aby usunąć konto">
                        @error('password', 'userDeletion')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger">
                        🗑️ Usuń konto na zawsze
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>
@endif
@endsection
