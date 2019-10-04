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
                    @can('organigrama-create')
                    <a class="btn btn-success" href="{{ route('organigramas.index') }}">Gestionar</a>
                    @endcan
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('accesos') }}"> Atras</a>
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

        var datascource = {
            'name': 'Organigrama IPS',
            'title': 'Consejo de Administracion',


            //Gerencia de Desarrollo y Tecnologia
            'children': [{
                    'name': '{{$gdt[0]->nombre}}',
                    'title': '{{$gdt[0]->responsable}}',
                    'className': 'gdt',
                    'children': [
                        @foreach($dgdts as $dgdt) {
                            'name': '{{$dgdt->nombre}}',
                            'title': '{{$dgdt->responsable}}',
                            'className': 'gdt'
                        },
                        @endforeach
                    ]

                },
                {
                    'name': '{{$gaf[0]->nombre}}',
                    'title': '{{$gaf[0]->responsable}}',
                    'className': 'gaf',
                    'children': [
                        @foreach($dgafs as $dgaf) {
                            'name': '{{$dgaf->nombre}}',
                            'title': '{{$dgaf->responsable}}',
                            'className': 'gaf'
                        },
                        @endforeach
                    ]
                },


                {
                    'name': '{{$gs[0]->nombre}}',
                    'title': '{{$gs[0]->responsable}}',
                    'className': 'gs',
                    'children': [
                        @foreach($dgss as $dgs) {
                            'name': '{{$dgs->nombre}}',
                            'title': '{{$dgs->responsable}}',
                            'className': 'gs'
                        },
                        @endforeach
                    ]
                },

                
                

                {
                    'name': '{{$gal[0]->nombre}}',
                    'title': '{{$gal[0]->responsable}}',
                    'className': 'gal',
                    'children': [
                        @foreach($dgals as $dgal) {
                            'name': '{{$dgal->nombre}}',
                            'title': '{{$dgal->responsable}}',
                            'className': 'gal'
                        },
                        @endforeach
                    ]
                },

                {
                    'name': '{{$gpess[0]->nombre}}',
                    'title': '{{$gpess[0]->responsable}}',
                    'className': 'gpess',
                    'children': [
                        @foreach($dgpesss as $dgpess) {
                            'name': '{{$dgpess->nombre}}',
                            'title': '{{$dgpess->responsable}}',
                            'className': 'gpess',
                            'children': [
                        @foreach($dgpesss as $dgpess) {
                            'name': '{{$dgpess->nombre}}',
                            'title': '{{$dgpess->responsable}}',
                            'className': 'gpess'
                        },
                        @endforeach
                    ]
                        },
                        @endforeach
                    ]
                },

            ]

        };

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