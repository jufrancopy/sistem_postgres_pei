@extends('layouts.master')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="card-title ">Editar Organigrama</h4>
                            <div class="pull-right">
                                @if ($dependencia->parent_id == null)
                                    <a class="btn btn-warning"
                                        href="{{ route('globales.organigrama-gestionar', $dependencia->id) }}">
                                        Atras</a>
                                @else
                                    <a class="btn btn-warning"
                                        href="{{ route('globales.organigrama-gestionar', $dependencia->parent_id) }}">
                                        Atras</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::model($dependencia, [
                                'route' => ['globales.organigramas.update', $dependencia->id],
                                'method' => 'PUT',
                            ]) !!}
                            @include('admin.globales.organigramas.partials.form')
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
