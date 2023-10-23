@extends('layouts.master')
@section('title', 'Matrices Grupales')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Matrices Grupales</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Perfiles Matrices </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">

                            <div id="data">

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="ajaxShowMatrizFoda" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxShowPeiDetails" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initilizaton JSTree
            $('#data').jstree({
                'core': {
                    'data': {
                        'url': function(node) {
                            var routeDetailItem = "{!! route('tree-group') !!}";
                            return routeDetailItem;
                        },
                        'data': function(node) {
                            return {
                                'id': node.id
                            };
                        }
                    }
                }
            });

            $('body').on('click', '#showMatrizFoda', function() {
                var fodaProfileID = $(this).data('id');
                $.get("{{ route('foda-analisis.index') }}" + '/' + fodaProfileID + '/matriz', function(
                data) {
                    // Construye el contenido de la modal
                    var modalContent = '<div class="card-body">';
                    modalContent += '<div class="table-bordered">';
                    modalContent += '<table class="table table-striped table-hover">';
                    modalContent += '<thead>';
                    modalContent += '<tr>';
                    modalContent += '<th><h3>Análisis Interno</h3></th>';
                    modalContent += '<th><h3>Análisis Externo</h3></th>';
                    modalContent += '</tr>';
                    modalContent += '</thead>';
                    modalContent += '<tbody>';
                    modalContent += '<tr>';
                    modalContent += '<th class="table-danger">Debilidades</th>';
                    modalContent += '<th class="table-danger">Amenazas</th>';
                    modalContent += '</tr>';
                    modalContent += '<tr>';
                    modalContent += '<td>';

                    // Construye la sección de Debilidades con base en los datos recibidos
                    data.debilidades.forEach(function(debilidad) {
                        modalContent += '<ul>';
                        modalContent += '<li>' + debilidad.aspecto.name;

                        // Aquí puedes aplicar el switch para determinar la clase CSS según el tipo
                        // ...

                        modalContent += '</li>';
                        modalContent += '</ul>';
                    });

                    modalContent += '</td>';
                    modalContent += '<td>';

                    // Construye la sección de Amenazas con base en los datos recibos
                    data.amenazas.forEach(function(amenaza) {
                        modalContent += '<ul>';
                        modalContent += '<li>' + amenaza.aspecto.name;

                        // Aquí puedes aplicar el switch para determinar la clase CSS según el tipo
                        // ...

                        modalContent += '</li>';
                        modalContent += '</ul>';
                    });

                    modalContent += '</td>';
                    modalContent += '</tr>';
                    modalContent += '<tr>';
                    modalContent += '<th class="table-success">Fortalezas</th>';
                    modalContent += '<th class="table-success">Oportunidades</th>';
                    modalContent += '</tr>';
                    modalContent += '<td>';

                    // Construye la sección de Fortalezas con base en los datos recibidos
                    data.fortalezas.forEach(function(fortaleza) {
                        modalContent += '<ul>';
                        modalContent += '<li>' + fortaleza.aspecto.name;

                        // Aquí puedes aplicar el switch para determinar la clase CSS según el tipo
                        // ...

                        modalContent += '</li>';
                        modalContent += '</ul>';
                    });

                    modalContent += '</td>';
                    modalContent += '<td>';

                    // Construye la sección de Oportunidades con base en los datos recibidos
                    data.oportunidades.forEach(function(oportunidad) {
                        modalContent += '<ul>';
                        modalContent += '<li>' + oportunidad.aspecto.name;

                        // Aquí puedes aplicar el switch para determinar la clase CSS según el tipo
                        // ...

                        modalContent += '</li>';
                        modalContent += '</ul>';
                    });

                    modalContent += '</td>';
                    modalContent += '</tr>';
                    modalContent += '</tbody>';
                    modalContent += '</table>';
                    modalContent += '</div>';
                    modalContent += '</div>';

                    // Establece el contenido de la modal
                    $('#modalBody').html(modalContent);

                    // Abre la modal
                    $('#ajaxShowMatrizFoda').modal('show');
                });
            });

        })
    </script>

@stop
