@extends('layouts.master') @section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title "><b>{{ $dependency->dependency }}</b></h4>
                        </a>
                        <a class="btn btn-success" href="{{ route('peis-add-estrategias', $idObjetivo) }}"> Crear Estrategias</a>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('peis-objetivos.show', $idObjetivo) }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{--
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Objetivo:</label> {{ $objetivo->objetivo }}
                            </div>
                        </div>
                        --}}
                        

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Dependencia</th>
                                        <th width="350px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estrategias as $v)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $v->estrategia }}</td>
                                        <td>
                                            <a class="btn btn-success btn-circle"
                                                href="{{ route('peis-estrategias.show',$v->id) }}">
                                                <i class="fa fa-plus"></i>
                                            </a>

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
</div>
@endsection