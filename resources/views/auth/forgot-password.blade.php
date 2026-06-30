<x-guest-layout>
    <h2>Recuperar Contraseña</h2>
    <p class="subtitle">Ingresá tu correo y te enviamos un enlace para restablecer tu contraseña</p>

    @if(session('status'))
    <div class="alert alert-success py-2 mb-3 small">
        <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger py-2 mb-3 small"><i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label" for="email"><i class="bi bi-envelope me-1"></i>Correo electrónico</label>
            <input id="email" type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                value="{{ old('email') }}" required autofocus placeholder="tu@correo.com">
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-send me-2"></i>Enviar Enlace de Restablecimiento
        </button>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="link-subtle"><i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión</a>
        </div>
    </form>
</x-guest-layout>
