@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Ver el rol <b>{{ $role->name }}</b></h4>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('roles.index') }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nombre:</strong> {{ $role->name }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Permisos:</strong>
                                @if(!empty($rolePermissions))
                                @foreach($rolePermissions as $v)
                                <label class="label label-success">{{ $v->name }},</label>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection