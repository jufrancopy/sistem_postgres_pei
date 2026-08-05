@extends('layouts.master')

@section('title', 'Ver Organigrama')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-warning">
                        <h4 class="card-title">Ver Organigrama</h4>
                        <a href="{{ route('coordinador.index') }}" class="btn btn-sm btn-secondary">Volver al Dashboard</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> 
                                    <strong>Información:</strong> Aquí puede ver el organigrama asociado al PEI de su grupo padre.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-warning">
                                        <h4 class="card-title">Organigrama: {{ $estructura->first()->dependency }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Responsable:</strong> {{ $estructura->first()->manager }}</p>
                                        <p><strong>Email:</strong> {{ $estructura->first()->email }}</p>
                                        <p><strong>Teléfono:</strong> {{ $estructura->first()->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-info">
                                        <h4 class="card-title">Estructura del Organigrama</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="chart-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script type="text/javascript">
    $(function() {
        var datascource = {
            'name': '{{ $estructura->first()->dependency }}',
            'title': '{{ $estructura->first()->manager }} | {{ $estructura->first()->phone }}',
            'children': []
        };

        @foreach($estructura->first()->children as $hijo1)
        var hijo1Data = {
            'name': '{{ $hijo1->dependency }}',
            'title': '{{ $hijo1->manager }} | {{ $hijo1->phone }}',
            'children': []
        };

            @foreach($hijo1->children as $hijo2)
            var hijo2Data = {
                'name': '{{ $hijo2->dependency }}',
                'title': '{{ $hijo2->manager }} | {{ $hijo2->phone }}',
                'children': []
            };

                @foreach($hijo2->children as $hijo3)
                var hijo3Data = {
                    'name': '{{ $hijo3->dependency }}',
                    'title': '{{ $hijo3->manager }} | {{ $hijo3->phone }}',
                    'children': []
                };

                    @foreach($hijo3->children as $hijo4)
                    hijo3Data.children.push({
                        'name': '{{ $hijo4->dependency }}',
                        'title': '{{ $hijo4->manager }} | {{ $hijo4->phone }}'
                    });
                    @endforeach

                hijo2Data.children.push(hijo3Data);
                @endforeach

            hijo1Data.children.push(hijo2Data);
            @endforeach

        datascource.children.push(hijo1Data);
        @endforeach

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
