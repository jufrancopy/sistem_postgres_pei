@extends('layouts.master')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="card-title ">Perfiles</h4>

                            <div class="pull-right">
                                <a class="btn btn-warning" href="{{ route('foda-analisis-ambiente-interno', $idPerfil) }}">
                                    Atras</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <p>Debe Asignar aspectos a analizar al Perfil <a
                                    href="{{ route('foda-aspectos-categoria', ['idCategoria' => $idCategoria, 'idPerfil' => $idPerfil]) }}"><i
                                        class="fa fa-edit" aria-hidden="true"></i></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
