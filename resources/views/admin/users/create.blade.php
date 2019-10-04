@extends('layouts.master') @section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Crear Nuevo Usuario</b>
                            <div class="pull-right">
                                <a class="btn btn-warning" href="{{ route('users.index') }}"> Volver</a>
                            </div>
                    </div>

                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="card-body">
                        {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nombre:</strong> {!! Form::text('name', null, array('placeholder' =>
                                    'Name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Correo:</strong> {!! Form::text('email', null, array('placeholder' =>
                                    'Email','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Password:</strong> {!! Form::password('password', array('placeholder' =>
                                    'Password','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Confirmar Password:</strong> {!! Form::password('confirm-password',
                                    array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    {{ Form::label('roles', 'Roles:') }}
                                    <select multiple="multiple" name="roles[]" id="roles" class="js-example-responsive" style="width:100%">
                                        @foreach($roles as $key => $value)
                                        <option value="{{ $key }}" {{ in_array($key, $rolesChecked) ? 'selected' : null }}>{{ $value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection