@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-info">
                    <p>{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Panel de Organigrama Institucional</h4>
                    <a class="btn btn-success" href="{{ route('organigramas-listado') }}">Gestionar Organigrama</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('home') }}"> Atras</a>
                    </div>
                </div>
                <div id="chart-container"></div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script type="text/javascript">
    $(function() {
    @foreach ($dependencias as $institucion) 
        var datascource = {
            'name': '{{$institucion->dependency}}',
            'title': '{{$institucion->dependency}}',

             
            'children': 
                [
                    @foreach ($institucion->childrenDependencies as $consejo)   
                        {
                        'name': '{{$consejo->dependency}}',
                        'title': '{{$consejo->dependency}}',
                        'className': 'gdt',
                            'children': 
                            [
                                @foreach ($consejo->childrenDependencies as $gerencia)    
                                    {
                                        'name': '{{$gerencia->dependency}}',
                                        'title': '{{$gerencia->dependency}}',
                                        'className': 'gdt',

                                        'children': 
                                        [
                                        @foreach ($gerencia->childrenDependencies as $direccion)    
                                            {
                                                'name': '{{$direccion->dependency}}',
                                                'title': '{{$direccion->dependency}}',
                                                'className': 'gdt'
                                            },
                                        @endforeach
                                        ]
                                    },
                                @endforeach
                            ]
                        },
                    @endforeach    
            ],
            
        };

        @endforeach

        $('#chart-container').orgchart({
            'data': datascource,
            'nodeContent': 'title',
            'verticalLevel': 3,
            'visibleLevel': 4,
            'toggleSiblingsResp': true



        });

    });
</script>
@endsection
@endsection