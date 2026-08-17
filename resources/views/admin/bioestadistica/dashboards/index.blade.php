@extends('layouts.master')
@section('title', 'Bioestadística — Dashboards')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">dashboard</i> Dashboards</h4>
        <p class="card-category">Plantillas institucionales y copias personales</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @can('bio.dashboard.manage')
            <a class="btn btn-info btn-sm mb-3" href="{{ route('bioestadistica.dashboards.create') }}">Nueva plantilla institucional</a>
        @endcan

        <h5>Plantillas institucionales</h5>
        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead><tr><th>Código</th><th>Nombre</th><th>Widgets</th><th></th></tr></thead>
                <tbody>
                @forelse($institucionales as $item)
                    <tr>
                        <td>{{ $item->codigo }} @if($item->es_default)<span class="badge badge-info">Predeterminado</span>@endif</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->widgets_count }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.dashboards.show', $item) }}">Abrir</a>
                            @can('bio.dashboard.personalize')
                                <form class="d-inline" method="POST" action="{{ route('bioestadistica.dashboards.clone', $item) }}">
                                    @csrf
                                    <button class="btn btn-outline-secondary btn-sm">Copiar a mi tablero</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No hay plantillas institucionales.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <h5>Mis tableros</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Código</th><th>Nombre</th><th>Widgets</th><th></th></tr></thead>
                <tbody>
                @forelse($personales as $item)
                    <tr>
                        <td>{{ $item->codigo }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->widgets_count }}</td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.dashboards.show', $item) }}">Abrir</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">Todavía no tiene una copia personal.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
