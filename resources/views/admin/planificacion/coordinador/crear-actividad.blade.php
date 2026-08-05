@extends('layouts.master')

@section('title', 'Crear Nuevas Actividades')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-success">
                        <h4 class="card-title">Crear Nuevas Actividades</h4>
                        <a href="{{ route('coordinador.index') }}" class="btn btn-sm btn-secondary">Volver al Dashboard</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> 
                                    <strong>Información:</strong> Aquí puede crear nuevas actividades para el PEI de su grupo padre.
                                </div>
                            </div>
                        </div>

                        @if($pei)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-success">
                                        <h4 class="card-title">PEI Asociado</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Nombre:</strong> {{ $pei->name }}</p>
                                        <p><strong>Año de Inicio:</strong> {{ $pei->year_start }}</p>
                                        <p><strong>Año de Fin:</strong> {{ $pei->year_end }}</p>
                                        <p><strong>Periodo:</strong> {{ $pei->period }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-info">
                                        <h4 class="card-title">Crear Nueva Actividad</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('coordinador.crear-actividad') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="nombre">Nombre de la Actividad</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success">Crear Actividad</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <i class="fa fa-warning"></i> 
                                    <strong>Advertencia:</strong> No se encontró un PEI asociado a su grupo.
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
