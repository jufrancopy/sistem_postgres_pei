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
                        <a class="btn btn-success" href="{{ route('globales.organigramas.show', $id) }}">Gestionar
                            Organigrama</a>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('globales.organigramas.index') }}">
                                Atras</a>
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
            @foreach ($dependencia as $depth0)
                var datascource = {
                    'name': '{{ $depth0->dependency }}',
                    'title': '{{ $depth0->responsable }} | {{ $depth0->telefono }}',
                    'children': [
                        @foreach ($depth0->descendants as $depth1)
                            {
                                'name': '{{ $depth1->dependency }}',
                                'title': '{{ $depth1->responsable }} | {{ $depth1->telefono }}',
                                'className': 'gdt',
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
                'exportFilename': 'Organigrama',
            });

            $(window).resize(function() {
                var width = $(window).width();
                if (width > 576) {
                    oc.init({
                        'verticalLevel': undefined
                    });
                } else {
                    oc.init({
                        'verticalLevel': 2
                    });

                }
            })
        });
    </script>
@endsection
@endsection
