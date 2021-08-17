@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title "><b>Crear Programa</b></h4>

                        <a class="btn btn-warning" href="{{ route('peis-estrategias.show', $estrategia->id) }}">
                            Atras</a>

                    </div>
                    <div class="card-body">
                        {!! Form::open(['route'=>'peis-programas.store']) !!}

                        @include('admin.planificacion.peis.programas.partials.form')

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection