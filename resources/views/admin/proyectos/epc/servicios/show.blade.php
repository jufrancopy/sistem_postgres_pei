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
                        <div class="card-title">
                            <label>Nombre: </label>{{$servicio->item}}
                            <label>Tipo: </label>{{$servicio->type}}
                            <label>Descripcion: </label>{{$servicio->description}}
                            <label>Equipamientos: </label>
                            {{$servicio->detailEquipamientos}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection