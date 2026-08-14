@extends('layouts.master')
@section('title', "Catálogo {$catalogo->nombre}")

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $catalogo->nombre }}</h4>
        <p class="card-category"><code>{{ $catalogo->codigo }}</code> · {{ $catalogo->descripcion }}</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <form method="POST" action="{{ route('bioestadistica.catalogos.items.store', $catalogo) }}" class="form-row bg-light p-3 rounded mb-4">
            @csrf
            <div class="col-md-2"><input class="form-control" name="codigo" placeholder="Código"></div>
            <div class="col-md-5"><input class="form-control" name="label" placeholder="Etiqueta" required></div>
            <div class="col-md-2"><input class="form-control" name="domain_code" placeholder="Dominio"></div>
            <div class="col-md-1"><input class="form-control" type="number" name="orden" value="{{ $catalogo->items->count() + 1 }}"></div>
            <div class="col-md-2"><button class="btn btn-success btn-sm">Agregar ítem</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead><tr><th>Orden</th><th>Código</th><th>Etiqueta</th><th>Dominio</th><th>Tipo de registro</th><th></th></tr></thead>
                <tbody>
                @forelse($catalogo->items as $item)
                    <tr>
                        <td>{{ $item->orden }}</td>
                        <td><code>{{ $item->codigo }}</code></td>
                        <td>{{ $item->label }}</td>
                        <td>{{ $item->domain_code }}</td>
                        <td>{{ $item->tipo_registro }}</td>
                        <td>
                            <form method="POST" action="{{ route('bioestadistica.catalog-items.destroy', $item) }}" onsubmit="return confirm('¿Eliminar ítem?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Catálogo vacío.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
