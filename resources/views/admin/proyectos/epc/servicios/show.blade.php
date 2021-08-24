@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Detalles del Servicio {{$servicio->item}} </h4>

                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('proyectos-epc-servicios.index') }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Equipamientos --}}
                        <div class="card-title"><label>Nombre: </label>{{$servicio->item}}</div>

                        <p class="card-text">
                            <label>Tipo: </label>
                            @switch($servicio->type)
                            @case('final')
                            Final
                            @break

                            @case('de_apoyo')
                            De Apoyo
                            @break
                            @default
                            <td>Sin tipo</td>
                            @endswitch

                        </p>
                        <p class="card-text">
                            <label>Descripcion: </label>
                            {{$servicio->description}}
                        </p>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="table-success">
                                        <td>Item</td>
                                        <td>Tipo</td>
                                        <td>Costo</td>
                                    </tr>
                                </thead>
                                @foreach($servicio->equipamientos as $equipamiento)
                                <tbody>
                                    <tr>
                                        <td>{{$equipamiento->item}}</td>
                                        <td>{{$equipamiento->type}}</td>
                                        <td>{{$equipamiento->cost}}</td>
                                    </tr>
                                </tbody>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <th colspan=2>Total</th>
                                        <th>{{$equipamiento->sum('cost')}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection