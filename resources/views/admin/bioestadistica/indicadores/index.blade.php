@extends('layouts.master')
@section('title', 'Bioestadística — Indicadores')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">functions</i> Motor de indicadores</h4>
        <p class="card-category">Indicadores configurables mediante fórmulas seguras</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        @can('bio.indicator.manage')
            <button class="btn btn-info btn-sm mb-3" data-toggle="collapse" data-target="#nuevoIndicador">
                <i class="material-icons">add</i> Nuevo indicador
            </button>
            <div class="collapse mb-4" id="nuevoIndicador">
                <form method="POST" action="{{ route('bioestadistica.indicadores.store') }}" class="card card-body bg-light">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-3"><input class="form-control" name="codigo" placeholder="CODIGO_INDICADOR" required></div>
                        <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
                        <div class="col-md-2"><input class="form-control" name="unidad" placeholder="Unidad"></div>
                        <div class="col-md-2">
                            <select class="form-control" name="ambito">
                                @foreach(['establecimiento','distrito','departamento','microred','pais'] as $value)
                                    <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1"><input class="form-control" type="number" name="decimales" min="0" max="4" value="2" title="Decimales"></div>
                    </div>
                    <textarea class="form-control mt-2" name="descripcion" placeholder="Descripción"></textarea>
                    <input type="hidden" name="activo" value="1">
                    <div><button class="btn btn-success btn-sm mt-2">Crear indicador</button></div>
                </form>
            </div>
        @endcan

        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Código</th><th>Nombre</th><th>Unidad</th><th>Ámbito</th><th>Fórmulas</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                @forelse($indicadores as $indicador)
                    <tr>
                        <td><strong>{{ $indicador->codigo }}</strong></td>
                        <td>{{ $indicador->nombre }}</td>
                        <td>{{ $indicador->unidad ?: '—' }}</td>
                        <td>{{ ucfirst($indicador->ambito) }}</td>
                        <td>{{ $indicador->formulas->count() }}</td>
                        <td><span class="badge badge-{{ $indicador->activo ? 'success' : 'secondary' }}">{{ $indicador->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.indicadores.show', $indicador) }}">Configurar / evaluar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Sin indicadores configurados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $indicadores->links() }}
    </div>
</div>
@endsection
