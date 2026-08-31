@extends('layouts.master')
@section('title', 'Bioestadística — Variables')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Variables']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">account_tree</i> Variables</h4>
        <p class="card-category">Variable → detalle → prestación. Los SP tabulares usan el detalle como tabla y las prestaciones como filas.</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        @can('bio.catalog.create')
        <form method="POST" action="{{ route('bioestadistica.diccionario.variables.store') }}" class="bio-filters mb-3">
            @csrf
            <div class="form-row align-items-end">
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Código</label>
                    <input class="form-control" name="codigo" placeholder="1, 10, x" required>
                </div>
                <div class="col-md-8 mb-2">
                    <label class="small text-muted mb-1">Nombre de la variable</label>
                    <input class="form-control" name="nombre" placeholder="Nombre de la variable" required>
                </div>
                <div class="col-md-2 mb-2">
                    <button class="btn btn-success btn-sm btn-block">Crear variable</button>
                </div>
            </div>
        </form>
        @endcan

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="25" data-order-false="4">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Variable</th>
                        <th>Detalles</th>
                        <th>Prestaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($variables as $variable)
                    <tr>
                        <td>{{ $variable->codigo }}</td>
                        <td>{{ $variable->nombre }}</td>
                        <td>{{ $variable->detalles_count }}</td>
                        <td>{{ $variable->prestaciones_count }}</td>
                        <td>
                            <div class="bio-actions">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.diccionario.show', $variable) }}">Abrir</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
