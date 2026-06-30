@extends('layouts.app')
@section('titulo','Actualizar Envío')
@section('contenido')
<div class="row justify-content-center"><div class="col-md-6">
<div class="card">
    <div class="card-header fw-semibold">Actualizar Envío: {{ $envio->numero_envio }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('envios.update',$envio) }}">
            @csrf @method('PATCH')
            <div class="mb-3">
                <label class="form-label fw-semibold">Fecha de Empaque *</label>
                <input type="date" name="fecha_empaque" class="form-control" value="{{ $envio->fecha_empaque->format('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Fecha de Entrega</label>
                <input type="date" name="fecha_entrega" class="form-control" value="{{ $envio->fecha_entrega?->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Estado *</label>
                <select name="estado" class="form-select" required>
                    @foreach(['preparando','enviado','entregado','devuelto'] as $e)
                    <option value="{{ $e }}" {{ $envio->estado === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Comentarios</label>
                <textarea name="comentarios" class="form-control" rows="2">{{ $envio->comentarios }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('envios.show',$envio) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
