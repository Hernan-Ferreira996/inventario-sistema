<x-guest-layout>
    <h2>Nueva Contraseña</h2>
    <p class="subtitle">Ingresá y confirmá tu nueva contraseña</p>

    @if($errors->any())
    <div class="alert alert-danger py-2 mb-3 small"><i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3">
            <label class="form-label" for="email"><i class="bi bi-envelope me-1"></i>Correo electrónico</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required autocomplete="username">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password"><i class="bi bi-lock me-1"></i>Nueva Contraseña</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
        </div>
        <div class="mb-4">
            <label class="form-label" for="password_confirmation"><i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="Repetí la contraseña">
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-shield-check me-2"></i>Restablecer Contraseña
        </button>
    </form>
</x-guest-layout>
