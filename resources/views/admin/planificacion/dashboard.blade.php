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
                        <h4 class="card-title ">Planificacion</h4>
                       
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('home') }}"> Atras</a>
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
            name: 'Planificación Estratégica Institucional.',
            children: [
                {
                    name: 'Perfiles de Planificacion Estrategica',
                    children: [
                        { name: '<a href="{{route('peis-perfiles.create')}}">Crear Nuevo Perfil</a>'},
                        { name: '<a href="{{route('peis-perfiles.index')}}">Listar Perfiles</a>'},
                    ]
                },
                {
                    name: 'Analisis FODA',
                    children: [
                        { name: '<a href="{{route('foda-modelos.index')}}">Modelos</a>'},
                        { name: '<a href="{{route('foda-perfiles.index')}}">Perfiles</a>'},
                        { name: '<a href="{{route('foda-listado-perfiles')}}">Cruce de Ambientes</a>'},
                    ]
                },

                {
                    name: 'Mision - Vision',
                    children: [
                        { name: '<a href="{{route('foda-modelos.index')}}">Asociar Perfil</a>'},
                    ]
                }
            ]
        },    
        
        

        {
            name: 'Monitoreo y Evaluación',
            children: [
                { name: '<a href="{{route('monitoreo-tipo_evaluaciones.index')}}">Tipos de Evaluaciones</a>'},
                { name: '<a href="{{route('monitoreo-evaluaciones.index')}}">Evaluaciones</a>'},
                
                
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