@extends('layouts.master') 

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Crear Nuevo Perfil</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'foda-perfiles.store','files'=>true]) !!}
                        @include('admin.fodas.perfiles.partials.form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection