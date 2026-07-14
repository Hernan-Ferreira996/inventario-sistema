@props(['campos', 'valores' => []])
@if($campos->isNotEmpty())
<div class="col-12"><hr class="my-2"><label class="form-label fw-semibold">Campos Adicionales</label></div>
@foreach($campos as $campo)
@php $valor = old('campos_personalizados.' . $campo->nombre, $valores[$campo->nombre] ?? ''); @endphp
<div class="col-md-6">
    <label class="form-label fw-semibold">{{ $campo->etiqueta }}@if($campo->requerido) *@endif</label>
    @if($campo->tipo === 'texto')
        <input type="text" name="campos_personalizados[{{ $campo->nombre }}]" class="form-control" value="{{ $valor }}" {{ $campo->requerido ? 'required' : '' }}>
    @elseif($campo->tipo === 'numero')
        <input type="number" step="0.01" name="campos_personalizados[{{ $campo->nombre }}]" class="form-control" value="{{ $valor }}" {{ $campo->requerido ? 'required' : '' }}>
    @elseif($campo->tipo === 'fecha')
        <input type="date" name="campos_personalizados[{{ $campo->nombre }}]" class="form-control" value="{{ $valor }}" {{ $campo->requerido ? 'required' : '' }}>
    @elseif($campo->tipo === 'booleano')
        <div class="form-check mt-2">
            <input type="hidden" name="campos_personalizados[{{ $campo->nombre }}]" value="0">
            <input type="checkbox" name="campos_personalizados[{{ $campo->nombre }}]" value="1" class="form-check-input" {{ $valor ? 'checked' : '' }}>
        </div>
    @elseif($campo->tipo === 'select')
        <select name="campos_personalizados[{{ $campo->nombre }}]" class="form-select" {{ $campo->requerido ? 'required' : '' }}>
            <option value="">-- Seleccionar --</option>
            @foreach(($campo->opciones ?? []) as $opcion)
            <option value="{{ $opcion }}" {{ (string)$valor === (string)$opcion ? 'selected' : '' }}>{{ $opcion }}</option>
            @endforeach
        </select>
    @endif
</div>
@endforeach
@endif
