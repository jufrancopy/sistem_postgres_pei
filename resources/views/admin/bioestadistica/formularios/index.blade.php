@extends('layouts.master')
@section('title', 'Bioestadística — Formularios')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">dynamic_form</i> Constructor de formularios</h4>
        <p class="card-category">SP1–SPx como configuración, sin tablas específicas</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <button class="btn btn-info btn-sm mb-3" data-toggle="collapse" data-target="#nuevoFormulario">
            <i class="material-icons">add</i> Nuevo formulario
        </button>
        <div class="collapse mb-4" id="nuevoFormulario">
            <form method="POST" action="{{ route('bioestadistica.formularios.store') }}" class="card card-body bg-light">
                @csrf
                <div class="form-row">
                    <div class="col-md-2"><input class="form-control" name="codigo" placeholder="SP15" required></div>
                    <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
                    <div class="col-md-3">
                        <select class="form-control" name="periodicidad" required>
                            @foreach(['diaria','semanal','mensual','trimestral','anual','ad_hoc'] as $value)
                                <option value="{{ $value }}" @selected($value === 'mensual')>{{ ucfirst(str_replace('_', ' ', $value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="layout_type" required>
                            <option value="tabular">Tabular</option>
                            <option value="nominativo">Nominativo</option>
                            <option value="matriz">Matriz</option>
                        </select>
                    </div>
                </div>
                <textarea class="form-control mt-2" name="descripcion" placeholder="Descripción"></textarea>
                <div><button class="btn btn-success btn-sm mt-2">Crear y diseñar</button></div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Código</th><th>Nombre</th><th>Diseño</th><th>Periodicidad</th><th>Estado</th><th>Versión</th><th></th></tr></thead>
                <tbody>
                @forelse($formularios as $formulario)
                    <tr>
                        <td><strong>{{ $formulario->codigo }}</strong></td>
                        <td>{{ $formulario->nombre }} <small class="text-muted">({{ $formulario->secciones_count }} secciones)</small></td>
                        <td>{{ ucfirst($formulario->layout_type) }}</td>
                        <td>{{ ucfirst($formulario->periodicidad) }}</td>
                        <td><span class="badge badge-{{ $formulario->estado === 'activo' ? 'success' : 'secondary' }}">{{ $formulario->estado }}</span></td>
                        <td>{{ $formulario->version }}</td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.formularios.edit', $formulario) }}">Diseñar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Sin formularios configurados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $formularios->links() }}
    </div>
</div>
@endsection
