@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Agregar Ítem a {{$variable->variable}}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::model($item, ['route'=>['formulario-items.update', $item->id],
                        'method'=>'PUT']) !!}
                        @include('admin.globales.formularios.items.partials.form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection