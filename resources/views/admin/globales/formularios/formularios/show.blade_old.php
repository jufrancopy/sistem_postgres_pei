@extends('layouts.master')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Formulario {{ $formulario->formulario }}</h4>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('globales.formularios.index') }}">Volver</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card border-success">
                            {{-- <div class="card-header">Responsable Emisor:</div>
                            <div class="card-body">
                                <div class="form-group">
                                    {{ Form::label('name', 'Nombre:') }}
                                    {{ Form::text('name', $formulario->dependenciaEmisora->dependency, ['class' => 'form-control', 'id' => 'name', 'disabled'=>'disabled']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('phone', 'Nombre:') }}
                                    {{ Form::text('phone', $formulario->dependenciaEmisora->telefono, ['class' => 'form-control', 'id' => 'phone', 'disabled'=>'disabled']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('email', 'Nombre:') }}
                                    {{ Form::text('email', $formulario->dependenciaEmisora->email, ['class' => 'form-control', 'id' => 'phone', 'disabled'=>'disabled']) }}
                                </div>
                            </div> --}}

                            {{-- <div class="card-header bg-transparent border-success">{{$formulario->formulario}}</div> --}}
                            <div class="card-body">
                                {{-- <div class="form-group">
                                    {{ Form::label('name', 'Dependencias:') }}
                                    {{ Form::text('name', $formulario->dependenciaReceptora->dependency, ['class' => 'form-control', 'id' => 'name', 'disabled'=>'disabled']) }}
                                </div> --}}
                                @foreach ($dependencia->descendants as $item)
                                    <span class="badge badge-secondary">{{ $item->name }},</span>
                                @endforeach


                                <div class="form-group">
                                    {{ Form::label('phone', 'Teléfono:') }}
                                    {{ Form::text('phone', $formulario->dependenciaReceptora->telefono, ['class' => 'form-control', 'id' => 'phone', 'disabled' => 'disabled']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('email', 'Correo:') }}
                                    {{ Form::text('email', $formulario->dependenciaReceptora->email, ['class' => 'form-control', 'id' => 'phone', 'disabled' => 'disabled']) }}
                                </div>
                            </div>

                        </div>


                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Variables</th>
                                        <th>Items</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($formulario->variables as $variable)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $variable->variable }}</td>
                                            <td>
                                                <table>
                                                    @foreach ($variable->item as $item)
                                                        <tr>
                                                            <td>{{ $item->item }}
                                                            <td>
                                                                @foreach (json_decode($item->questions) as $key => $value)
                                                                    {{ $value }}
                                                                    <input type="checkbox" value="1">Si
                                                                    <input type="checkbox" value="0">No
                                                                    <br>
                                                                @endforeach
                                                            </td>
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>

                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
