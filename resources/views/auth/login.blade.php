<x-guest-layout>
    <h2>Bienvenido</h2>
    <p class="subtitle">Iniciá sesión para acceder al sistema</p>

    @if(session('status'))
    <div class="alert alert-success alert-sm py-2 mb-3 small"><i class="bi bi-check-circle me-1"></i>{{ session('status') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger py-2 mb-3 small"><i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email"><i class="bi bi-envelope me-1"></i>Correo electrónico</label>
            <input id="email" type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="tu@correo.com">
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0" for="password"><i class="bi bi-lock me-1"></i>Contraseña</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-subtle">¿Olvidaste tu contraseña?</a>
                @endif
            </div>
            <div class="input-group">
                <input id="password" type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    required autocomplete="current-password" placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePass()" tabindex="-1">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label small text-muted" for="remember_me">Mantenerme conectado</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al Sistema
        </button>
    </form>
</x-guest-layout>

<script>
function togglePass() {
    const p = document.getElementById('password');
    const i = document.getElementById('eyeIcon');
    if (p.type === 'password') { p.type = 'text'; i.className = 'bi bi-eye-slash'; }
    else { p.type = 'password'; i.className = 'bi bi-eye'; }
}
</script>
