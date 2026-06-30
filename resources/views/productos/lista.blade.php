@extends('layouts.app')
@section('titulo', 'Productos')
@section('subtitulo', 'Gestión de inventario')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="me-3 text-muted"><i class="bi bi-box-seam me-1"></i>{{ $totales['cantidad_productos'] }} productos</span>
        <span class="text-muted"><i class="bi bi-stack me-1"></i>{{ number_format($totales['stock_total']) }} unidades en stock</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Producto
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Lista de Productos</span>
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Buscar por nombre o código..." value="{{ request('q') }}" style="width:220px">
            <select name="categoria_id" class="form-select form-select-sm" style="width:160px">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
            <div class="form-check form-check-sm d-flex align-items-center">
                <input type="checkbox" name="stock_bajo" value="1" class="form-check-input me-1" id="chkStockBajo" {{ request('stock_bajo') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label small text-nowrap" for="chkStockBajo">Stock bajo</label>
            </div>
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            @if(request()->hasAny(['q','categoria_id','stock_bajo']))
            <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-danger" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th class="text-end">Stock</th>
                    <th class="text-end">P. Compra</th>
                    <th class="text-end">P. Venta</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr>
                    <td><code class="text-primary">{{ $producto->codigo }}</code></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($producto->imagen)
                                <img src="{{ Storage::url($producto->imagen) }}" width="32" height="32" class="rounded" style="object-fit:cover">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:32px;height:32px">
                                    <i class="bi bi-image text-secondary" style="font-size:12px"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold">{{ $producto->nombre }}</div>
                                @if($producto->descripcion)
                                    <small class="text-muted">{{ Str::limit($producto->descripcion, 40) }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $producto->categoria?->nombre ?? '—' }}</td>
                    <td>{{ $producto->unidad?->abreviatura ?? $producto->unidad?->nombre ?? '—' }}</td>
                    <td class="text-end">
                        @php $stock = $producto->movimientos_sum_cantidad ?? 0; @endphp
                        <span class="fw-semibold {{ $stock <= 0 ? 'text-danger' : ($stock <= 5 ? 'text-warning' : 'text-success') }}">
                            {{ number_format($stock, 2) }}
                        </span>
                    </td>
                    <td class="text-end">{{ number_format($producto->precio_compra, 2) }}</td>
                    <td class="text-end">{{ number_format($producto->precio_venta_minorista, 2) }}</td>
                    <td>
                        @if($producto->activo)
                            <span class="badge bg-success-subtle text-success">Activo</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('productos.show', $producto) }}" class="btn btn-sm btn-outline-info" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('productos.destroy', $producto) }}" onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-box-seam d-block mb-2" style="font-size:2rem"></i>
                        No hay productos registrados.
                        <a href="{{ route('productos.create') }}">Crear el primero</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($productos->hasPages())
    <div class="card-footer py-2">
        {{ $productos->links() }}
    </div>
    @endif
</div>

@endsection
