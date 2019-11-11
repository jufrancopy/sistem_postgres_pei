@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Editar Estructura</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::model($estructura, ['route'=>['estructuras-control.update', $estructura->id],
                        'method'=>'PUT']) !!}
                        @include('admin.globales.estructuras-control.partials.form')

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection