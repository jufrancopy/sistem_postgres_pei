@extends('layouts.master')
@section('title', 'Hospitalización SP10')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">local_hospital</i> Episodios hospitalarios (SP10)</h4>
        <p class="card-category">Detalle nominativo con período estadístico editable y carga masiva tipo planilla.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form class="form-row align-items-end mb-3">
            <div class="form-group col-md-3 mb-2">
                <label>Establecimiento</label>
                <select class="form-control" name="establecimiento_id">
                    <option value="">Todos</option>
                    @foreach($establecimientos as $establecimiento)
                        <option value="{{ $establecimiento->id }}" @selected(request('establecimiento_id') == $establecimiento->id)>{{ $establecimiento->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Año</label>
                @include('admin.bioestadistica._periodo-anio-select', [
                    'name' => 'periodo_anio',
                    'value' => request('periodo_anio'),
                    'allowEmpty' => true,
                    'emptyLabel' => 'Todos',
                    'required' => false,
                ])
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Mes</label>
                <select class="form-control" name="periodo_mes"><option value="">Todos</option>@foreach($months as $number => $month)<option value="{{ $number }}" @selected(request('periodo_mes') == $number)>{{ $month }}</option>@endforeach</select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Servicio</label>
                <select class="form-control" name="servicio"><option value="">Todos</option>@foreach($servicios as $code => $label)<option value="{{ $code }}" @selected(request('servicio') == $code)>{{ $label }}</option>@endforeach</select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Tipo de alta</label>
                <select class="form-control" name="tipo_alta"><option value="">Todos</option>@foreach($tiposAlta as $code => $label)<option value="{{ $code }}" @selected(request('tipo_alta') == $code)>{{ $label }}</option>@endforeach</select>
            </div>
            <div class="form-group col-md-2 mb-2"><button class="btn btn-info">Filtrar</button></div>
        </form>
        <div class="mb-3">
            @can('bio.hosp.manage')
                <a class="btn btn-success" href="{{ route('bioestadistica.hospitalizacion.spreadsheet', request()->only(['establecimiento_id','periodo_anio','periodo_mes'])) }}">
                    <i class="material-icons">grid_on</i> Iniciar carga
                </a>
                <a class="btn btn-success" href="{{ route('bioestadistica.hospitalizacion.create') }}">Nuevo episodio</a>
                <a class="btn btn-outline-info" href="{{ route('bioestadistica.hospitalizacion.import') }}">Importar Excel</a>
            @endcan
            <a class="btn btn-outline-primary" href="{{ route('bioestadistica.hospitalizacion.panel', request()->only(['establecimiento_id','periodo_anio','periodo_mes'])) }}">Panel</a>
            @can('bio.hosp.export')
                <a class="btn btn-outline-secondary" href="{{ route('bioestadistica.hospitalizacion.export', request()->query()) }}">Exportar CSV</a>
            @endcan
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Establecimiento</th>
                        <th>Período</th>
                        <th>Cédula</th>
                        <th>Sexo</th>
                        <th>Ingreso</th>
                        <th>Egreso</th>
                        <th>Servicio</th>
                        <th>Alta</th>
                        <th>CIE-10</th>
                        <th>Estancia</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($episodios as $episodio)
                    <tr>
                        <td>{{ $episodio->establecimiento->nombre }}</td>
                        <td>{{ sprintf('%02d/%d', $episodio->periodo_mes, $episodio->periodo_anio) }}</td>
                        <td>{{ $episodio->cedula_visible ?? '—' }}</td>
                        <td>{{ $episodio->sexo ?? '—' }}</td>
                        <td>{{ $episodio->fecha_ingreso?->format('d/m/Y') }}</td>
                        <td>{{ $episodio->fecha_egreso?->format('d/m/Y') ?? 'Internado' }}</td>
                        <td>{{ $servicios[$episodio->servicio] ?? $episodio->servicio }}</td>
                        <td>{{ $tiposAlta[$episodio->tipo_alta] ?? $episodio->tipo_alta }}</td>
                        <td>{{ $episodio->cie10 }}</td>
                        <td>{{ $episodio->stayDays() ?? '—' }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('bioestadistica.hospitalizacion.edit', $episodio) }}">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-muted text-center">No hay episodios para los filtros seleccionados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $episodios->links() }}
    </div>
</div>
@endsection
