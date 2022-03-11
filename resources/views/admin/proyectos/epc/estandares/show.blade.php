@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Detalles del Estándar {{ $estandar->item }} </h4>

                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('proyectos-epc-home') }}"> Atras</a>
                        </div>

                        <div class="pull-left">
                            <a class="btn btn-success"
                                href="{{ route('proyectos-epc-estandares.edit', $estandar->id) }}">
                                Editar</a>
                        </div>


                    </div>
                    <div class="card-body">
                        <div class="card-title"><label>Nombre: </label>{{ $estandar->item }}</div>
                        <p class="card-text">
                            <label>Tipo: </label>
                        {{$estandar->type}}

                        </p>
                        <p class="card-text">
                            <label>Descripcion: </label>
                            {!! $estandar->detail !!}
                        </p>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <label>Servicios: </label>
                            {{ $estandar->servicios()->sum('cantidad') }}
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
                                    @foreach ($estandar->servicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->item }}</td>
                                        <td>{{ $servicio->type }}</td>
                                        <td>{{ $servicio->pivot->cantidad }}</td>

                                        @php
                                        $subtotal = $estandar->pivot->cantidad * 1;
                                        $totalServicio += $subtotal;
                                        @endphp
                                        <td>{{ $servicio->pivot->cantidad }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan=4>Total Servicios</th>
                                        <td>#</td>
                                    </tr>
                                </tfoot>

                            </table>
                            <table class="table">
                                @php
                                $totalGeneral = $totalServicio + 11
                                @endphp

                                <tr class="table-warning">
                                    <th colspan=4 width=80%>Total General</th>
                                    <td>{{ number_format($totalGeneral, 1, '.', '') }}</td>
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