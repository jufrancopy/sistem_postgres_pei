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
                        <h4 class="card-title ">Organigrama de Control Institucional -
                            {{ $dependencie->first()->dependency }}</h4>
                        <a class="btn btn-success" href="{{ route('globales.organigramas.edit', $id) }}">Gestionar
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
            @foreach ($dependencie as $depth0)
                var datascource = {
                    'name': '{{ $depth0->dependency }}',
                    'title': '{{ $depth0->manager }} | {{ $depth0->phone }}',
                    'children': [
                        @foreach ($depth0->children as $depth1)
                            {
                                'name': '{{ $depth1->dependency }}',
                                'title': '{{ $depth1->manager }} | {{ $depth1->phone }}',
                                'className': 'gdt',
                                'children': [
                                    @foreach ($depth1->children as $depth2)
                                        {
                                            'name': '{{ $depth2->dependency }}',
                                            'title': '{{ $depth2->manager }} | {{ $depth2->phone }}',
                                            'className': 'gdt',
                                            'children': [
                                                @foreach ($depth2->children as $depth3)
                                                    {
                                                        'name': '{{ $depth3->dependency }}',
                                                        'title': '{{ $depth3->manager }} | {{ $depth3->phone }}',
                                                        'className': 'gdt',
                                                    },
                                                @endforeach
                                            ],
                                        },
                                    @endforeach
                                ],
                            },
                        @endforeach
                    ],

                };
            @endforeach
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
