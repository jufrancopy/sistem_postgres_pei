@extends('layouts.master')
@section('title', 'Árbol Detalles PEI')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Lista de Tareas Grupales</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Árbol de Detalles</a></li>
                <li class="breadcrumb-item active" aria-current="page">Árbol Detalles PEI</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <div id="treeProfile">

                            </div>
                        </div>
                    </div>

                    {{-- <div class="modal fade bd-example-modal-lg" id="ajaxShowMatrizFoda" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header card-header-info">
                                    <h4 class="modal-title" id="modalFODAHeading"></h4>
                                </div>
                                <div class="card-body">
                                    <label>Nombre: </label><span id="name"> </span><br>
                                    <label>Contexto: </label><span id="context"> </span><br>
                                    <label>Tipo: </label><span id="type"> </span><br>
                                    <label>Modelo: </label><span id="model"> </span><br>
                                    <label>Grupo: </label><span id="group"> </span>
                                    <h3>Miembros del Grupo de Análisis</h3>

                                    <div class="table members">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th class="">Nombre</th>
                                                <th class="">Correo</th>
                                            </thead>

                                            <tbody id="memberListFODA">


                                            </tbody>

                                        </table>
                                    </div>
                                </div>



                                <div class="modal-body"></div>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8 text-center">
                                        <button type="button" class="btn btn-info mb-2"
                                            data-dismiss="modal">Cerrar</button>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade bd-example-modal-lg" id="ajaxShowPeiDetails" aria-hidden="true">
                        <div class="modal-dialog modal-lg" id="peiDetails">
                            <div class="modal-content">
                                <div class="modal-header card-header-info">
                                    <h4 class="modal-title" id="modalPEIHeading"></h4>
                                </div>

                                <div class="card-body headerPei">
                                    <label>Nombre: </label><span id="name"> </span><br>
                                    <label>Tipo: </label><span id="type"> </span><br>
                                    <label>Grupo: </label><span id="group"> </span>

                                    <h3>Miembros del Grupo de Análisis</h3>
                                    <div class="table membersPEI">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th class="">Nombre</th>
                                                <th class="">Correo</th>
                                            </thead>
                                            <tbody id="memberListPEI"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <label>Definición de la Misión: </label>
                                    <h3 id="mision"> </h3><br>
                                    <label>Definición de la Visión: </label>
                                    <h3 id="vision"> </h3><br>
                                    <label>Definición de la Valores: </label>
                                    <h3 id="values"> </h3><br>
                                </div>
                            </div>
                        </div>
                    </div> --}}



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
            var data = [
                @foreach ($profile as $matriz)
                    {
                        name: '{!! $matriz->name !!}',
                        children: [
                            @foreach ($matriz->children as $axi)
                                {
                                    name: '<p class="badge badge-success">EJE</p> {!! $axi->name !!}',
                                    children: [
                                        @foreach ($axi->children as $goal)
                                            @foreach ($goal->children as $action)
                                                {
                                                    name: '<div class="card">' +
                                                        '<div class="card-body">' +
                                                        '<span class="badge badge-primary">Objetivo</span>' +
                                                        ' {!! $goal->name !!}' +
                                                        '<table class="table table-bordered">' +
                                                        '<tr>' +
                                                        '<th>Acción</th>' +
                                                        '<th>Indicador</th>' +
                                                        '<th>Línea de Base</th>' +
                                                        '<th>Meta</th>' +
                                                        '<th>Responsable</th>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td>{!! $action->name !!}</td>' +
                                                        '<td>{!! $action->indicator !!}</td>' +
                                                        '<td>{!! $action->baseline !!}</td>' +
                                                        '<td>{!! $action->target !!}</td>' +
                                                        '<td>' +
                                                        @foreach ($action->responsibles as $responsible)
                                                            '<span class="badge badge-secondary">{{ $responsible->dependency }}</span> ' +
                                                        @endforeach
                                                    '</td>' +
                                                    '</tr>' +
                                                    '</table>' +
                                                    '</div>' +
                                                    '</div>',

                                                },
                                            @endforeach
                                        @endforeach
                                    ]
                                },
                            @endforeach
                        ]
                    },
                @endforeach
            ];


            $('#treeProfile').tree({
                data: data,
                autoEscape: false,
                saveState: true,
                closedIcon: $('<i class="fas fa-arrow-circle-right"></i>'),
                openedIcon: $('<i class="fas fa-arrow-circle-down"></i>'),
                autoOpen: true,
                openFolderDelay: 1000,
                dragAndDrop: true
            });

            // Initilizaton JSTree
            // $('#treeProfile').jstree({
            //     'core': {
            //         'data': {
            //             'url': function(node) {
            //                 var idProfile = <?php echo json_encode($idProfile); ?>;
            //                 var routeDetailItem = "{!! route('pei-profiles-details.tree', $idProfile) !!}";
            //                 console.log("🚀 ~ file: details_tree.blade.php:131 ~ routeDetailItem:",
            //                     routeDetailItem)

            //                 return routeDetailItem;
            //             },
            //             'data': function(node) {
            //                 return {
            //                     'id': node.id
            //                 };
            //             }
            //         }
            //     }
            // });

            // Esta función agregará los miembros al modal
            function membersModalFODA(members) {
                var memberListFODA = $(
                    '#memberListFODA'); // Encuentra el contenedor de la lista de miembros

                // Vacía la lista actual (si hubiera elementos anteriores)
                memberListFODA.empty();

                // Itera sobre los miembros y agrégalos a la lista
                members.forEach(function(member) {
                    var row = '<tr>' +
                        '<td>' + member.name + '</td>' +
                        '<td>' + member.email + '</td>' +
                        '</tr>';
                    memberListFODA.append(row);
                });
            }

            // Esta función agregará los miembros al modal
            function membersModalPEI(members) {
                var memberListPEI = $('#memberListPEI'); // Encuentra el contenedor de la lista de miembros

                // Vacía la lista actual (si hubiera elementos anteriores)
                memberListPEI.empty();

                // Itera sobre los miembros y agrégalos a la lista
                members.forEach(function(member) {
                    var row = '<tr>' +
                        '<td>' + member.name + '</td>' +
                        '<td>' + member.email + '</td>' +
                        '</tr>';
                    memberListPEI.append(row);
                });
            }

            $('body').on('click', '#showMatrizFoda', function() {
                var fodaProfileID = $(this).data('id');

                $.get("{{ route('foda-analisis.index') }}" + '/' + fodaProfileID + '/matriz', function(
                    data) {

                    // Listado de mimbros del Grupo
                    var members = data.members;
                    membersModalFODA(members);

                    // Construye el contenido de la modal
                    $('#modalFODAHeading').html("FODA " + data.profile.name);
                    $('#name').text(data.profile.name);
                    $('#context').text(data.profile.context);
                    $('#type').text(data.profile.type);
                    $('#model').text(data.profile.model.name);
                    $('#group').text(data.profile.group.name);


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
                    $('.modal-body').html(modalContent);

                });
            });

            $('body').on('click', '#showPeiDetailes', function() {
                var peiProfileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + peiProfileID + '/detail', function(
                    data) {
                    $('#modalPEIHeading').html("PEI " + data.name);
                    $('#ajaxShowPeiDetails').modal('show');
                    $('.headerPei #name').text(data.profile.name);
                    $('.headerPei #type').text(data.profile.type);
                    $('.headerPei #group').text(data.profile.group.name);
                    $('#mision').html(data.profile.mision);
                    $('#vision').html(data.profile.vision);
                    $('#values').html(data.profile.values);

                    var members = data.members;
                    console.log(members)
                    membersModalPEI(members);
                });

            });

        })
    </script>

@stop
