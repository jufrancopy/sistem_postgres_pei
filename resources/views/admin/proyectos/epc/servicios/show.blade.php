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
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <label>Equipamientos: </label>
                            {{$servicio->equipamientos()->sum('cantidad')}}   
                            <table class="table">
                                <thead>
                                    <tr class="table-success">
                                        <td width="20%">Item</td>
                                        <td>Tipo</td>
                                        <td>Cantidad</td>
                                        <td>Precio Unitario</td>
                                        <td>Precio Total</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servicio->equipamientos as $equipamiento)
                                    <tr>
                                        <td>{{$equipamiento->item}}</td>
                                        @switch($equipamiento->type)
                                        @case('equipo_biomedico')
                                        <td>Equipo Biomédico</td>

                                        @break
                                        @case('equipo_informatico')
                                        <td>Equipo Informático</td>
                                        @break

                                        @case('equipo_mobiliario')
                                        <td>Equipo Mobiliario</td>
                                        @break

                                        @default
                                        <td>Sin tipo</td>
                                        @endswitch
                                        <td>{{$equipamiento->pivot->cantidad}}</td>
                                        <td>{{$equipamiento->cost}}</td>
                                        @php
                                            $subtotal = $equipamiento->pivot->cantidad * $equipamiento->cost;
                                            $totalEquipamiento+=$subtotal;
                                        @endphp
                                        <td>{{$subtotal}}</td> 
                                        @endforeach    
                                    </tr>                                
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan=4>Total Equipamientos</th>
                                        <td>{{$totalEquipamiento}}</td>    
                                    </tr>
                                </tfoot>
                                
                            </table>

                            

                            <table class="table">
                                <thead>
                                    <tr class="table-success">
                                        <td width="20%">Item</td>
                                        <td>Tipo</td>
                                        <td>Cantidad</td>
                                        <td>Precio Unitario</td>
                                        <td>Precio Total</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servicio->tthhs as $talentoHumano)
                                    <tr>
                                        <td>{{$talentoHumano->item}}</td>
                                        @switch($talentoHumano->type)
                                        @case('medico_de_consultorio')
                                        <td>Médico de Consultorio</td>

                                        @break
                                        @case('medico_de_guardia')
                                        <td>Médico de Guardia</td>
                                        @break

                                        @case('auxiliar_de_consultorio')
                                        <td>Auxiliar</td>
                                        @break

                                        @default
                                        <td>Sin tipo</td>
                                        @endswitch
                                        <td>{{$talentoHumano->pivot->cantidad}}</td>
                                        <td>{{$talentoHumano->cost}}</td>
                                        @php
                                            $subtotal = $talentoHumano->pivot->cantidad * $talentoHumano->cost;
                                            $totalTalentoHumano+=$subtotal;
                                        @endphp
                                        <td>{{$subtotal}}</td> 
                                        @endforeach   
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan=4>Total Talentos Humanos</th>
                                        <td>{{$totalTalentoHumano}}</td>  
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