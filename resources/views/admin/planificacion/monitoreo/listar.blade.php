@extends('layouts.master')
@section('content')
<div class="container">
   <div class="col-md-8 col-md-offset-2">
      <section class="content-header">
         <h1>Listado de Actividades Reportadas para Evaluacion
            <small>Beta v1.0</small>
         </h1>
         <ol class="breadcrumb ">
            <li><a href="{{route('reportes.index')}}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active"><a href="{{route('reportes.index')}}">Listado de Evaluaciones</a></li>
            <a href="{{route('reportes.create')}}" class="btn btn-info btn-sm ml-auto pull-right">Crear</a>
         </ol>
      </section>
      <div class="panel panel-default">
         <div class="panel-heading">
            <div class="table-responsive">
               <table class="table table-bordered">
                  <thead>
                    <tr>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Beneficiado</th>
                                    <th>Condicion</th>
                                    <th>Actividades</th>
                                    <th>Total</th>
                                    <th>Anho</th>
                                    <th>Reponsable</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach ($evaluaciones as $evaluacion)
                                 <tr>
                                    <td>{{$evaluacion->item}}</td>
                                    <td>{{$evaluacion->tipos_evaluaciones->nombre}}</td>
                                    <td>{{$evaluacion->beneficiado}}</td>
                                    @switch($evaluacion->tipo)
                                                @case('sin_condicion')
                                                    <td>Nueva</td>
                                                    @break

                                                @case('permanente')
                                                    <td>Readecuación</td>
                                                    @break
                                                @case('contratado')
                                                    <td>Readecuación y Ampliación</td>
                                                    @break                                    

                                                    @case('permanente')
                                                    <td>Readecuación y Ampliación</td>
                                                    @break 

                                                @default
                                                    <td>Sin tipo</td>
                                            @endswitch
                                    <td>{{$evaluacion->actividades}}</td>
                                    <td>{{$evaluacion->total}}</td>
                                    <td>{{$evaluacion->anhos->anho}}</td>
                                    <td>{{$evaluacion->subgerencias->nombre}}</td>
                                 </tr>
                                 
                              </tbody>
                  @endforeach
               </table>
                  <div class="panel-footer" style="text-align: center;">
                     {{$evaluaciones->render()}}
                  </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection