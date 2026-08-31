@extends('layouts.master')
@section('title', 'Bioestadística — Dashboards')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Dashboards']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">dashboard</i> Dashboards</h4>
        <p class="card-category">Plantillas institucionales y copias personales</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @can('bio.dashboard.manage')
            <div class="d-flex bio-toolbar mb-3">
                <a class="btn btn-info btn-sm" href="{{ route('bioestadistica.dashboards.create') }}">Nueva plantilla institucional</a>
            </div>
        @endcan

        <h5>Plantillas institucionales</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="10" data-order-false="3">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Widgets</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($institucionales as $item)
                    <tr>
                        <td>{{ $item->codigo }} @if($item->es_default)<span class="badge badge-info">Predeterminado</span>@endif</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->widgets_count }}</td>
                        <td>
                            <div class="bio-actions">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.dashboards.show', $item) }}">Abrir</a>
                                @can('bio.dashboard.manage')
                                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('bioestadistica.dashboards.edit', $item) }}">Editar</a>
                                @endcan
                                @can('bio.dashboard.personalize')
                                    <form class="d-inline" method="POST" action="{{ route('bioestadistica.dashboards.clone', $item) }}">
                                        @csrf
                                        <button class="btn btn-outline-secondary btn-sm" type="submit">Copiar</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <h5>Mis tableros</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="10" data-order-false="3">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Widgets</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($personales as $item)
                    <tr>
                        <td>{{ $item->codigo }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->widgets_count }}</td>
                        <td>
                            <div class="bio-actions">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.dashboards.show', $item) }}">Abrir</a>
                                @can('update', $item)
                                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('bioestadistica.dashboards.edit', $item) }}">Editar</a>
                                @endcan
                                @can('delete', $item)
                                    <form method="POST" action="{{ route('bioestadistica.dashboards.destroy', $item) }}" class="d-inline bio-confirm-form" data-confirm="¿Quitar este tablero de Mis tableros?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Quitar</button>
                                    </form>
                                @endcan
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
