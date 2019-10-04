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
                    'name': '{{$gdts[0]->gerencias->nombre}}',
                    'title': '{{$gdts[0]->gerencias->responsable}}',
                    'className': 'gdts',
                    'children': [
                        @foreach($gdts as $gdt) {
                            'name': '{{$gdt->nombre}}',
                            'title': '{{$gdt->responsable}}',
                            'className': 'gdts'
                        },
                        @endforeach
                    ]

                },
                {
                    'name': '{{$gafs[0]->gerencias->nombre}}',
                    'title': '{{$gafs[0]->gerencias->responsable}}',
                    'className': 'gafs',
                    'children': [
                        @foreach($gafs as $gaf) {
                            'name': '{{$gaf->nombre}}',
                            'title': '{{$gaf->responsable}}',
                            'className': 'gafs'
                        },
                        @endforeach
                    ]
                },


                {
                    'name': '{{$gss[0]->gerencias->nombre}}',
                    'title': '{{$gss[0]->gerencias->responsable}}',
                    'className': 'gss',
                    'children': [
                        @foreach($gss as $gs) {
                            'name': '{{$gs->nombre}}',
                            'title': '{{$gs->responsable}}',
                            'className': 'gss'
                        },
                        @endforeach
                    ]
                },


                {
                    'name': '{{$gals[0]->gerencias->nombre}}',
                    'title': '{{$gals[0]->gerencias->responsable}}',
                    'children': [
                        @foreach($gals as $gal) {
                            'name': '{{$gal->nombre}}',
                            'title': '{{$gal->responsable}}'
                        },
                        @endforeach
                    ]
                },

                {
                    'name': '{{$gpes[0]->gerencias->nombre}}',
                    'title': '{{$gpes[0]->gerencias->responsable}}',
                    'className': 'gpes',
                    'children': [
                        @foreach($gpes as $gpe) {
                            'name': '{{$gpe->nombre}}',
                            'title': '{{$gpe->responsable}}',
                            'className': 'gpes'
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
            'visibleLevel': 4
        });

    });
</script>
@endsection
@endsection