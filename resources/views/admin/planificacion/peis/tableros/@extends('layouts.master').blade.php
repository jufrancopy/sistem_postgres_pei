@extends('layouts.master') 
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title "><b>{{ $niveles->first()->parent->nivel }}</b></h4>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('peis-perfiles.index') }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Responsable:</label>
                                {{ $niveles->first()->parent->dependencies()->first()->dependency }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Misión:</label>
                                {{ $niveles->first()->parent->mision }}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Visión:</label>
                                {{ $niveles->first()->parent->vision }}
                            </div>
                        </div>
                        <label>Objetivos:</label>
                        @foreach ($niveles as $objetivo)

                        <ul>
                            <li>
                                {{$objetivo->nivel}}
                                @foreach ($objetivo->childrenNiveles as $estrategia )
                                <ul>
                                    <li>{{$estrategia->nivel}}</li>

                                    {{-- Tabla --}}

                                    <table  class="table table-bordered" style="border: 1px solid #ddd !important;">
    <tr>
        <th bgcolor="#0080ff">Programa</th>
        <th bgcolor="#0080ff">Proyecto</th>
        <th bgcolor="#0080ff">Actividades</th>
    </tr>

    <td bgcolor="red" rowspan="3">
        {{-- Imprime el programa --}}
        {{$programa->nivel}}
        @foreach ($programa->childrenNiveles as $proyecto)    
        <td bgcolor="green" rowspan="3">
                {{--Imprime los Proyectos  --}}
                {{$proyecto->nivel}}

            @foreach($proyecto->childrenNiveles as $actividad)    
             <td>
                 <table>
                    <tr>
                        <td bgcolor="blue">
                            {{--Imprime Actividades  --}}
                            {{$actividad->nivel}}
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </td>
        @endforeach  
</table>

                                    
                                </ul>
                                @endforeach
                            </li>
                        </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


