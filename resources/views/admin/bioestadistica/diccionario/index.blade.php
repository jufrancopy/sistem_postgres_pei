@extends('layouts.master')
@section('title', 'Bioestadística — Diccionario')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">account_tree</i> Diccionario de variables</h4>
        <p class="card-category">Variable → detalle → prestación. Los SP tabulares usan el detalle como tabla y las prestaciones como filas.</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        @can('bio.catalog.create')
        <form method="POST" action="{{ route('bioestadistica.diccionario.variables.store') }}" class="form-row bg-light p-3 rounded mb-4">
            @csrf
            <div class="col-md-2"><input class="form-control" name="codigo" placeholder="Código (1, 10, x)" required></div>
            <div class="col-md-8"><input class="form-control" name="nombre" placeholder="Nombre de la variable" required></div>
            <div class="col-md-2"><button class="btn btn-success btn-sm">Crear variable</button></div>
        </form>
        @endcan

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Variable</th>
                        <th>Detalles</th>
                        <th>Prestaciones</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($variables as $variable)
                    <tr>
                        <td>{{ $variable->codigo }}</td>
                        <td>{{ $variable->nombre }}</td>
                        <td>{{ $variable->detalles_count }}</td>
                        <td>{{ $variable->prestaciones_count }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.diccionario.show', $variable) }}">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">Sin variables. Importe variables salud.xls o créelas aquí.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
