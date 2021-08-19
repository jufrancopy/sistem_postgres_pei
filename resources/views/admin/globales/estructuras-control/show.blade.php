@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title "> <b>{{ $estructura->subDependencia->dependency }}</b></h4>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('estructuras-control.index') }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <table class="table table-responnsive">
                                    <thead>
                                        <tr>
                                            <th>Departamento</th>
                                            <th>Responsable</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($estructura->dependencias as $subDependencia)
                                        <tr>
                                            <td>{{ $subDependencia->dependency }}</td>
                                            <td>{{ $subDependencia->responsable }}</td>
                                            <td>{{ $subDependencia->email }}</td>
                                            <td>{{ $subDependencia->telefono }}</td>
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
</div>
@endsection