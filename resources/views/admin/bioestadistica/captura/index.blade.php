@extends('layouts.master')
@section('title', 'Bioestadística — Captura')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">edit_note</i> Captura de planillas</h4>
        <p class="card-category">Período estadístico independiente de la fecha de digitación</p>
    </div>
    <div class="card-body">
        @foreach(['success', 'warning'] as $message)
            @if(session($message)) <div class="alert alert-{{ $message === 'success' ? 'success' : 'warning' }}">{{ session($message) }}</div> @endif
        @endforeach
        <div class="d-flex justify-content-between mb-3">
            <div>
                @can('bio.record.create')<a href="{{ route('bioestadistica.captura.create') }}" class="btn btn-info"><i class="material-icons">add</i> Nueva carga</a>@endcan
                <a href="{{ route('bioestadistica.captura.pending') }}" class="btn btn-warning">Períodos pendientes</a>
            </div>
            @if(auth()->user()->hasAnyRole(['Administrador', 'Analista de Bioestadística']))
                <a href="{{ route('bioestadistica.captura.assignments') }}" class="btn btn-outline-info">Asignar digitadores</a>
            @endif
        </div>
        <form class="form-row mb-3">
            <div class="col-md-3"><select class="form-control" name="formulario_id"><option value="">Todos los formularios</option>@foreach($formularios as $formulario)<option value="{{ $formulario->id }}" @selected(request('formulario_id') == $formulario->id)>{{ $formulario->codigo }} — {{ $formulario->nombre }}</option>@endforeach</select></div>
            <div class="col-md-2"><input class="form-control" name="periodo_anio" type="number" value="{{ request('periodo_anio') }}" placeholder="Año"></div>
            <div class="col-md-2"><select class="form-control" name="periodo_mes"><option value="">Todos los meses</option>@foreach($months as $number => $month)<option value="{{ $number }}" @selected(request('periodo_mes') == $number)>{{ $month }}</option>@endforeach</select></div>
            <div class="col-md-2"><select class="form-control" name="estado"><option value="">Todos los estados</option>@foreach(['borrador','enviado','aprobado','objetado'] as $state)<option value="{{ $state }}" @selected(request('estado') === $state)>{{ ucfirst($state) }}</option>@endforeach</select></div>
            <div class="col-md-2"><button class="btn btn-primary">Filtrar</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Formulario</th><th>Establecimiento</th><th>Período del dato</th><th>Estado</th><th>Actualizado</th><th></th></tr></thead>
                <tbody>@forelse($records as $record)
                    <tr>
                        <td>{{ $record->formulario->codigo }} — {{ $record->formulario->nombre }}</td>
                        <td>{{ $record->establecimiento->nombre }}</td>
                        <td>{{ $months[$record->periodo_mes] }}/{{ $record->periodo_anio }}</td>
                        <td><span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">{{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}</span></td>
                        <td>{{ $record->updated_at?->format('d/m/Y H:i') }}</td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.captura.edit', $record) }}">{{ $record->isEditable() ? 'Editar' : 'Ver' }}</a></td>
                    </tr>
                @empty<tr><td colspan="6" class="text-center text-muted">No hay registros para los filtros seleccionados.</td></tr>@endforelse</tbody>
            </table>
        </div>
        {{ $records->links() }}
    </div>
</div>
@endsection
