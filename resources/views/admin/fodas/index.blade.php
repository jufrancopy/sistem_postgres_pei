@extends('layouts.master')
@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Panel de Configuración de FODA</h4>
                       
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('fodas-dashboard') }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">
                    <p id="tree1"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @section('scripts')
    <script>
        var data = [
        {
            name: 'Configurar',
            children: [
                { name: '<a href="{{route('foda-modelos.index')}}">Modelos</a>'},
                
            ]
        },    
        

        {
            name: 'Perfiles',
            children: [
                { name: '<a href="{{route('foda-perfiles.index')}}">Listar Perfiles</a>'},
                { name: '<a href="{{route('foda-perfiles.create')}}">Crear Nuevo Perfil de Analisis</a>'},
            ]
        },

        {
            name: 'Analisis de Perfiles',
            children: [
                { name: '<a href="{{route('foda-listado-perfiles')}}">Listar Perfiles</a>'},
            ]
        },
    ];

    $('#tree1').tree({
        data: data,
        autoEscape: false,
        saveState: true,
        closedIcon: $('<i class="fas fa-arrow-circle-right"></i>'),
        openedIcon: $('<i class="fas fa-arrow-circle-down"></i>'),
        autoOpen: true,
        openFolderDelay: 1000,
        dragAndDrop: true
    });
    </script>
    @endsection
@endsection