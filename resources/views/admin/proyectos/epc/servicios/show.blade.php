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
                            <a class="btn btn-warning" href="{{ route('proyectos-epc-home') }}"> Atras</a>
                        </div>

                        <div class="pull-left">
                            <a class="btn btn-success" href="{{ route('proyectos-epc-servicios.edit',$servicio->id) }}">
                                Editar</a>
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

                            <label>Talentos Humanos: </label>
                            {{$servicio->tthhs()->sum('cantidad')}}
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

                            <label>Infraestructuras: </label>
                            {{$servicio->infraestructuras()->sum('cantidad')}}
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
                                    @foreach($servicio->infraestructuras as $infraestructura)
                                    <tr>
                                        <td>{{$infraestructura->item}}</td>
                                        @switch($infraestructura->type)
                                        @case('servicio')
                                        <td>Servicio</td>
                                        @break
                                        @case('ambulatorio')
                                        <td>Ambulatorio</td>
                                        @break
                                        @case('administrativo')
                                        <td>Administrativo</td>
                                        @break
                                        @case('hospitalizacion')
                                        <td>Hospitalización</td>
                                        @break
                                        @case('quirurgico')
                                        <td>Quirúrgico</td>
                                        @break

                                        @default
                                        <td>Sin tipo</td>
                                        @endswitch
                                        <td>{{$infraestructura->pivot->cantidad}}</td>
                                        <td>{{$infraestructura->cost}}</td>
                                        @php
                                        $subtotal = $infraestructura->pivot->cantidad * $infraestructura->cost;
                                        $totalInfraestructura+=$subtotal;
                                        @endphp
                                        <td>{{$subtotal}}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan=4>Total Infraestructura</th>
                                        <td>{{$totalInfraestructura}}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <label>Otros Servicios: </label>
                            {{$servicio->otroServicios()->sum('cantidad')}}
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
                                    @foreach($servicio->otroServicios as $otroServicio)
                                    <tr>
                                        <td>{{$otroServicio->item}}</td>
                                        @switch($otroServicio->type)
                                        @case('limpieza')
                                        <td>Limpieza</td>
                                        @break
                                        @case('seguridad')
                                        <td>Seguridad</td>
                                        @break
                                        @case('gastronomia')
                                        <td>Gastronomía</td>
                                        @break
                                        @case('ambulancia')
                                        <td>Ambulancia</td>
                                        @break
                                        @default
                                        <td>Sin tipo</td>
                                        @endswitch
                                        <td>{{$otroServicio->pivot->cantidad}}</td>
                                        <td>{{$otroServicio->cost}}</td>
                                        @php
                                        $subtotal = $otroServicio->pivot->cantidad * $otroServicio->cost;
                                        $totalOtroServicio+=$subtotal;
                                        @endphp
                                        <td>{{$subtotal}}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan=4>Total Otros Servicios</th>
                                        <td>{{$totalOtroServicio}}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <table class="table">
                                @php
                                $totalGeneral = $totalEquipamiento + $totalTalentoHumano + $totalInfraestructura +
                                $totalApoyoAdministrativo + $totalOtroServicio;
                                @endphp

                                <tr class="table-warning">
                                    <th colspan=4 width=80%>Total General</th>
                                    <td>{{$totalGeneral}}</td>
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