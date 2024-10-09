@extends('layouts.master')
@section('title', 'Encuestas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Módulo Encuestas y Preguntas</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Módulo Encuestas y Preguntas</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewProfile">
                            Nueva Encuesta</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Periodo</th>
                                        <th>Tipo</th>
                                        <th>Grupo</th>
                                        <th>Analista</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Inicio Modal --}}
                    <div class="modal fade" id="ajaxModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="profileForm" name="profileForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}
                                        {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                                        {{ Form::hidden('type', 'group', ['id' => 'type']) }}
                                        {{ Form::hidden('level', 'master', ['id' => 'level']) }}
                                        {{ Form::hidden('mision', null, ['class' => 'form-control', 'id' => 'mision']) }}
                                        {{ Form::hidden('vision', null, ['class' => 'form-control', 'id' => 'vision']) }}
                                        {{ Form::hidden('values', null, ['class' => 'form-control', 'id' => 'values']) }}
                                        {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'period']) }}
                                        {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'denominator']) }}
                                        {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goal']) }}
                                        {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('year_start', 'Año de Inicio') !!}
                                            {!! Form::date('year_start', null, [
                                                'class' => 'form-control',
                                                'placeholder' => '2023',
                                                'data-date-format' => 'yyyy',
                                                'max' => date('Y'),
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('year_end', 'Año de Finalización') !!}
                                            {!! Form::date('year_end', null, [
                                                'class' => 'form-control',
                                                'placeholder' => '2028',
                                                'data-date-format' => 'yyyy',
                                                'max' => date('Y'),
                                            ]) !!}
                                        </div>

                                        <div class="form-group type_profile">
                                            {{ Form::label('type_profile', 'Tipo:') }}
                                            {!! Form::select('type_profile', ['group' => 'Grupal', 'corporative' => 'Corporativo'], null, [
                                                'id' => 'type_profile',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group dependencies" style="display: none;">
                                            {{ Form::label('dependency_id', 'Elija Corporación:') }}
                                            {!! Form::select('dependency_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'dependencies',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group group_roots">
                                            {{ Form::label('group_root_id', 'Evento:') }}
                                            {!! Form::select('group_root_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'group_roots',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group groups">
                                            {{ Form::label('groups', 'Asignar Grupo de Trabajo:') }}
                                            {!! Form::select('group_id', [], null, [
                                                'id' => 'groups',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('analysts', 'Asignar Analista:') }}
                                            {!! Form::select('analyst_id[]', [], null, [
                                                'id' => 'analysts',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                                'multiple',
                                            ]) !!}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtn"
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin Modal --}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript"></script>
@stop
