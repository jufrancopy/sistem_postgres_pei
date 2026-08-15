@extends('layouts.master')
@section('title', 'Bioestadística — Catálogos')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">list_alt</i> Catálogos reutilizables</h4>
        <p class="card-category">Prestaciones, especialidades, vacunas, determinaciones y otros dominios</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <form method="POST" action="{{ route('bioestadistica.catalogos.store') }}" class="form-row bg-light p-3 rounded mb-4">
            @csrf
            <div class="col-md-2"><input class="form-control" name="codigo" placeholder="CODIGO" required></div>
            <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre del catálogo" required></div>
            <div class="col-md-4"><input class="form-control" name="descripcion" placeholder="Descripción"></div>
            <div class="col-md-2"><button class="btn btn-success btn-sm">Crear catálogo</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Código</th><th>Nombre</th><th>Ítems</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                @forelse($catalogos as $catalogo)
                    <tr>
                        <td><code>{{ $catalogo->codigo }}</code></td>
                        <td>{{ $catalogo->nombre }}</td>
                        <td>{{ $catalogo->items_count }}</td>
                        <td><span class="badge badge-{{ $catalogo->activo ? 'success' : 'secondary' }}">{{ $catalogo->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.catalogos.show', $catalogo) }}">Administrar ítems</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Sin catálogos.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $catalogos->links() }}
    </div>
</div>
@endsection
