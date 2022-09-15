@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Formulario {{$formulario->formulario}}</h4>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right"
                            href="{{ route('globales.formularios.index') }}">Volver</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card border-success">
                        <div class="card-header">Responsable Emisor:</div>
                        <div class="card-body">
                            <p><label>Nombre: </label>{{$formulario->dependenciaEmisora->responsable}}</p>
                            <p><label>Teléfono: </label>{{$formulario->dependenciaEmisora->telefono}}</p>
                            <p><label>Correo: </label>{{$formulario->dependenciaEmisora->email}}</p>
                        </div>

                        <div class="card-header bg-transparent border-success">Responsable Receptor:</div>
                        <div class="card-body">
                                <p><label>Nombre: </label>{{$formulario->dependenciaReceptora->responsable}}</p>
                                <p><label>Teléfono: </label>{{$formulario->dependenciaReceptora->telefono}}</p>
                                <p><label>Correo: </label>{{$formulario->dependenciaReceptora->email}}</p>
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
                                            @foreach($variable->item as $item)
                                            <tr>
                                                <td>{{$item->item}}</td>
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