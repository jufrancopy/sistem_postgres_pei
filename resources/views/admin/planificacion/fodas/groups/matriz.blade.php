@extends('layouts.master')
@section('title', 'Consolidados')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header card-header-info">
                            @if (isset($profile))
                                <h3 class="card-title ">Matriz FODA Consolidado de {{ $profile->name }}</h3>
                            @else
                                <h3 class="card-title ">Matriz FODA Consolidado</h3>
                            @endif
                        </div>
                        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('foda-list-groups') }}">Lista de Consolidados
                                    </a></li>
                                @if (isset($profile))
                                    <li class="breadcrumb-item active" aria-current="page">Matriz Foda -
                                        {{ $profile->name }}</li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">Matriz Foda Consolidado</li>
                                @endif
                            </ol>
                        </nav>
                        <div class="card-body">
                            <div class="card-header">
                                @if (isset($profiles) && count($profiles) > 0)
                                    <label>Perfiles Partipantes del Análisis:</label>
                                    @foreach ($profiles as $v)
                                        {{ $v->name }}
                                    @endforeach
                                @else
                                    <label>Perfiles:</label>
                                    {{ $profile->name }}
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-bordered">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h3>Análisis Interno</h3>
                                            </th>
                                            <th>
                                                <h3>Análisis Externo</h3>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <th class="table-danger">Debilidades</th>
                                            <th class="table-danger">Amenazas</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                @foreach ($debilidades as $v)
                                                    <ul>
                                                        <li>{{ $v->aspecto->name }}
                                                            @switch($v->tipo)
                                                                @case('Fortaleza')
                                                                    <p class="badge badge-success">{{ $v->tipo }}</p>
                                                                @break

                                                                @case('Oportunidad')
                                                                    <p class="badge badge-success">{{ $v->tipo }}</p>
                                                                @break

                                                                @case('Debilidad')
                                                                    <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                                @break

                                                                @case('Amenaza')
                                                                    <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                                @break

                                                                @default
                                                                    <p class="badge badge-default">Pendiente</p>
                                                                </li>
                                                        @endswitch
                                                    </ul>
                                                @endforeach
                                            </td>

                                            <td>
                                                @foreach ($amenazas as $v)
                                                    <ul>
                                                        <li>{{ $v->aspecto->name }}
                                                            @switch($v->tipo)
                                                                @case('Fortaleza')
                                                                    <p class="badge badge-success">{{ $v->tipo }}</p>
                                                                @break

                                                                @case('Oportunidad')
                                                                    <p class="badge badge-success">{{ $v->tipo }}</p>
                                                                @break

                                                                @case('Debilidad')
                                                                    <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                                @break

                                                                @case('Amenaza')
                                                                    <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                                @break

                                                                @default
                                                                    <p class="badge badge-default">Pendiente</p>
                                                                </li>
                                                        @endswitch
                                                    </ul>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="table-success">Fortalezas</th>
                                            <th class="table-success">Oportunidades</th>
                                        </tr>
                                        <td>
                                            @foreach ($fortalezas as $v)
                                                <ul>
                                                    <li>{{ $v->aspecto->name }}
                                                        @switch($v->tipo)
                                                            @case('Fortaleza')
                                                                <p class="badge badge-success">{{ $v->tipo }}</p>
                                                            @break

                                                            @case('Oportunidad')
                                                                <p class="badge badge-success">{{ $v->tipo }}</p>
                                                            @break

                                                            @case('Debilidad')
                                                                <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                            @break

                                                            @case('Amenaza')
                                                                <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                            @break

                                                            @default
                                                                <p class="badge badge-default">Pendiente</p>
                                                            </li>
                                                    @endswitch
                                                </ul>
                                            @endforeach

                                        <td>
                                            @foreach ($oportunidades as $v)
                                                <ul>
                                                    <li>{{ $v->aspecto->name }}
                                                        @switch($v->tipo)
                                                            @case('Fortaleza')
                                                                <p class="badge badge-success">{{ $v->tipo }}</p>
                                                            @break

                                                            @case('Oportunidad')
                                                                <p class="badge badge-success">{{ $v->tipo }}</p>
                                                            @break

                                                            @case('Debilidad')
                                                                <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                            @break

                                                            @case('Amenaza')
                                                                <p class="badge badge-danger">{{ $v->tipo }}</p>
                                                            @break

                                                            @default
                                                                <p class="badge badge-default">Pendiente</p>
                                                            </li>
                                                    @endswitch

                                                </ul>
                                            @endforeach
                                        </td>
                                        </td>


                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">
                                                <hr>
                                                <div class="createProfile">
                                                    @if (isset($profile))
                                                        <label>Perfil Maestro:</label>
                                                        {{ $profile->name }}<br>
                                                        <label>Contexto:</label>
                                                        {{ $profile->context }}<br>
                                                        <label>Evento:</label>
                                                        {{ $profile->group->name }}<br>
                                                        <label>Propietario Genérico:</label>
                                                        {{ $profile->dependency->dependency }}<br>

                                                        <a href="{{ route('foda-matriz-groups-crossing', $profile->group_id) }}"
                                                            class="btn btn-sm btn-info">
                                                            Gestionar Cruce de Ambientes
                                                        </a>
                                                    @else
                                                        <h3>Crear Perfil Consolidado</h3>

                                                        {!! Form::open(['url' => ['foda-profile', 'idGroup' => $idGroup], 'files' => true]) !!}

                                                        @include('admin.planificacion.fodas.groups.partials.form')
                                                        {!! Form::close() !!}
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
