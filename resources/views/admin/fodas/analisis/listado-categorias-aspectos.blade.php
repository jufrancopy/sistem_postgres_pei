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
                        <h4 class="card-title ">{{$categoria->nombre }} </h4>
                        {{--
                        <a href="{{route('foda-aspectos-categoria-edit', ['idCategoria' => $idCategoria, 'idPerfil' => $perfil->id])}}" class="btn btn-success">+ Aspectos</a>
                        --}}
                    </div>
                    <div class="card-body">
                        <p id="tree1"></p>
                        <div class="card text-center">
                            <a href="{{route('foda-analisis-ambiente-interno', $perfil->id )}}" class="btn btn-success">Analizar otra Categoria</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @section('scripts')
    <script>
        var data = [
            @foreach($analisis as $valor)
        {
            name: '{{$valor->aspecto->nombre}}'+
            '@switch($valor->tipo)@case('Fortaleza')<p class="badge badge-success">Fortaleza</p>'+
                '@break'+
                    '@case('Oportunidad')<p class="badge badge-info">Oportunidad</p>@break'+ 
                    '@case('Debilidad')<p class="badge badge-danger">Debilidad</td> @break'+
                    '@case('Amenaza') <p class="badge badge-warning">Amenaza</p> @break'+ 
                    '@default <p>Pendiente</p>'+
                    '@endswitch',
            children: [
                { name: 
                    '<table class="table table-bordered">'+
                    '<tr>'+
                        '<th>Impacto</th>'+
                        '<th>Ocurrencia</ht>'+
                        '<th>Ponderación</ht>'+
                        '<th colspan = 2>Acciones</h>'+
                        '</tr>'+
                    '<tr>'+
                    @switch($valor->ocurrencia) 
                        @case('0.1')'<td>Baja ({{$valor->ocurrencia}})</td>'+ @break
                        @case('0.3')'<td>Media ({{$valor->ocurrencia}})</td>'+ @break                             
                        @case('0.5')'<td>Alta ({{$valor->ocurrencia}})</td>' + @break
                        @case('0.7')'<td>Muy Alta ({{$valor->ocurrencia}})</td>' + @break  
                        @case('0.9')'<td>Cierta ({{$valor->ocurrencia}})</td>'+ @break
                    @default'<td>Pendiente</td>' + 
                    @endswitch

                    @switch($valor->impacto) 
                        @case('0.05')'<td>Muy Bajo ({{$valor->impacto}})</td>'+ @break
                        @case('0.1')'<td>Bajo ({{$valor->impacto}})</td>'+ @break                             
                        @case('0.2')'<td>Moderado ({{$valor->impacto}})</td>' + @break
                        @case('0.4')'<td>Alto ({{$valor->impacto}})</td>' + @break  
                        @case('0.8')'<td>Muy Alto ({{$valor->impacto}})</td>'+ @break 
                    @default'<td>Pendiente</td>' +
                    @endswitch
                    
                    @php 
                        $total = $valor->ocurrencia * $valor->impacto;
                        
                    @endphp
                    
                    @switch ($total) 
                        @case($total >= 0.18)'<td class="badge badge-success">Suficiente ({{$total}})</td>'+ @break
                        @case($total <= 0.17)'<td class="badge badge-danger">Insuficiente ({{$total}})</td>'+ @break
                        @case($total = 0)'<td >Pendiente</td>'+ @break
                    @endswitch

                        '<td><a href="{{ route('foda-analisis.edit', $valor->id) }}">Analizar</i></a>'+
                        '</td>'+
                        '<td>'+
                            '{!! Form::open(['route' => ['foda-analisis.destroy', $valor->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}'+
                                '<button class= "btn btn-danger" onclick="return confirm(\'Estas seguro de eliminar el analisis?\')">'+
                                    '<i class="fa fa-trash" aria-hidden="true"></i>'+
                                '</button>'+
                            '{!! Form::close() !!}'+
                        '</td>'+
                        '</tr>'+
                        '</table>'
                
                },
            ]
        },
        @endforeach   
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