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
                    <a class="btn btn-success" href="{{ route('globales.organigramas.show', $id) }}">Gestionar Organigrama</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('globales.organigramas.index') }}"> Atras</a>
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
            'title': '{{$institucion->responsable}} | {{$institucion->telefono}}',
            'children': 
                [
                    @foreach ($institucion->childrenDependencies as $consejo)   
                        {
                        'name': '{{$consejo->dependency}}',
                        'title': '{{$consejo->responsable}} | {{$consejo->telefono}}',
                        'className': 'gdt',
                        'children': 
                            [
                                @foreach ($consejo->childrenDependencies as $gerencia)    
                                    {
                                        'name': '{{$gerencia->dependency}}',
                                        'title': '{{$gerencia->responsable}} | {{$gerencia->telefono}}',
                                        'className': 'gdt',
                                        'children': 
                                        [
                                        @foreach ($gerencia->childrenDependencies as $direccion)    
                                            {
                                                'name': '{{$direccion->dependency}}',
                                                'title': '{{$direccion->responsable }} | {{$direccion->telefono}}',
                                                'className': 'gdt',
                                                'children': 
                                        [
                                            @foreach ($direccion->childrenDependencies as $departamento)    
                                                {
                                                    'name': '{{$departamento->dependency}}',
                                                    'title': '{{$departamento->responsable}} | {{$departamento->telefono}}',
                                                    'className': 'gdt',
                                                    'children': 
                                            [
                                                @foreach ($departamento->childrenDependencies as $seccion)    
                                                    {
                                                        'name': '{{$seccion->dependency}}',
                                                        'title': '{{$seccion->responsable}} | {{$seccion->telefono}}',
                                                        'className': 'gdt'
                                                    },
                                                    @endforeach   
                                            ]
                                                },
                                                @endforeach   
                                        ]
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

        // $('#chart-container').orgchart({
        //     'data': datascource,
        //     'nodeContent': 'title',
        //     'verticalLevel': 3,
        //     'visibleLevel': 4,
        //     'exportFileextension': 'pdf',
        //     'exportFilename':'Organigrama',
        //     'toggleSiblingsResp': true
        // });

        var oc = $('#chart-container').orgchart({
            'data': datascource,
            'nodeContent': 'title',
            'exportFileextension': 'pdf',
            'exportFilename':'Organigrama',
        });

        $(window).resize(function(){
            var width = $(window).width();
            if(width > 576){
                oc.init({'verticalLevel':undefined});
            }else{
                oc.init({'verticalLevel':2 });

            }
        })
});
</script>
@endsection
@endsection