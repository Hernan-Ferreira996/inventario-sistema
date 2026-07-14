@props(['grupo', 'valor'])
@php
    $colores = \App\Models\CatalogoValor::colores($grupo, $valor);
    $etiqueta = \App\Models\CatalogoValor::etiqueta($grupo, $valor);
@endphp
<span class="badge" style="background:{{ $colores['color'] }};color:{{ $colores['color_texto'] }}">{{ $etiqueta }}</span>
