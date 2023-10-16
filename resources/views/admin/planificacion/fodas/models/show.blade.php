@extends('layouts.master')
@section('title', 'Categorias - Aspectos')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">FODA - Categorías</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('foda-models.index') }}">Modelos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Agregar Categorías</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewCategory"> <i
                                class="material-icons ">add_box</i> Nueva Categoría (Capacidades o Factores)</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre</th>
                                        <th>Ambiente</th>
                                        <th>Descripión</th>
                                        <th width="280px">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="modalCategory" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalModelHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="categoryForm" name="categoryForm" class="form-horizontal">

                                        {{ Form::hidden('model_id', null, ['id' => 'model_id']) }}
                                        {{ Form::hidden('type', 'category', ['class' => 'form-control', 'id' => 'type']) }}
                                        {{ Form::hidden('parent_id', $category->id, ['id' => 'parent_id']) }}
                                        {{ Form::hidden('owner', $category->owner, ['id' => 'owner']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('environment', 'Ambiente:') }}
                                            {!! Form::select(
                                                'environment',
                                                ['Interno' => 'Análisis Estratégico Interno', 'Externo' => 'Análisis Estratégico Externo'],
                                                null,
                                                [
                                                    'id' => 'environment',
                                                    'placeholder' => '',
                                                    'style' => 'width:100%',
                                                ],
                                            ) !!}
                                        </div>

                                        <div class="description mb-2">
                                            {{ Form::label('description', 'Descripción técnica:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('description', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'description',
                                            ]) }}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtn"
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalShowAspects" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalShowAspectsHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-bordered aspectsTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Referencia</th>
                                                    </tr>
                                                    </head>
                                                <tbody>

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar
                                        </button>
                                    </div>

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
    {{-- My custom scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/he/1.2.0/he.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i>',
                        titleAttr: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Excel'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i>',
                        titleAttr: 'CSV'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Imprimir'
                    }
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                ajax: "{{ route('foda-models.show', $category->id) }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'environment',
                    name: 'environment'
                }, {
                    data: 'description',
                    name: 'description',

                    render: function(data, type, full, meta) {
                        if (type === 'display' || type === 'filter') {
                            // Deshacer la escapada de HTML utilizando jQuery
                            return $('<div/>').html(data).text();
                        }
                        return data;
                    }
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }, ]
            });

            var descriptionEditor;

            ClassicEditor
                .create(document.querySelector('#description'))
                .then(editor => {
                    descriptionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });

            $('#createNewCategory').click(function() {
                $('#saveBtn').val("create-model");
                $('#model_id').val('');
                $('#categoryForm').trigger("reset");
                $('#modalModelHeading').html("Nueva Categoría (Capacidades o Factores)");
                $('#modalCategory').modal('show');
                $('#environment').select2({
                    placeholder: "Seleccion el Ambiente"
                });

                descriptionEditor.setData('');

            });

            $('body').on('click', '.showAspects', function() {
                var categoryID = $(this).data('id');

                $.get("{{ route('foda-models.index') }}" + '/' + categoryID + '/showAspects', function(
                    data) {

                    $('#modalShowAspects').modal('show');
                    $('#modalShowAspectsHeading').html("Listado de Aspectos de la categoría " + data
                        .category.name + " (" + data.category.environment + ")");

                    $('#categoryForm').trigger("reset");

                    // Limpia la lista existente antes de agregar nuevos elementos
                    $(".aspectsTable tbody").empty();


                    $.each(data.aspects, function(index, value) {
                        var nombre = value.name;
                        var descripcion = value.description;

                        // Crea una nueva fila de la tabla
                        var nuevaFila = $("<tr>").append(
                            $("<td>").text(nombre),
                            $("<td>").html(descripcion)

                        );

                        // Agrega la nueva fila a la tabla
                        $(".aspectsTable  tbody").append(nuevaFila);
                    });
                });
            });

            $('body').on('click', '.editCategory', function() {
                var categoryID = $(this).data('id');

                $.get("{{ route('foda-models.index') }}" + '/' + categoryID + '/edit', function(data) {


                    $('#modalModelHeading').html("Editar categoría " + data.name + " (" + data
                        .environment + ")");
                    $('#saveBtn').val("edit-profile");
                    $('#modalCategory').modal('show');
                    $('#categoryForm').trigger("reset");
                    $('#model_id').val(data.id);
                    $('#name').val(data.name);
                    $('#owner').val(data.owner);
                    $('#environment').select2();
                    $('#environment').val(data.environment).trigger('change');

                    descriptionEditor.setData(data.description);
                });
            });


            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var data = new FormData();
                var form_data = $('#categoryForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                (descriptionEditor.getData())
                data.append('description', descriptionEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('foda-models.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#categoryForm').trigger("reset");
                        $('#modalCategory').modal('hide');
                        $(".success").removeAttr("style");
                        table.draw();
                    },

                    error: function(data) {
                        var obj = data.responseJSON.errors;
                        $.each(obj, function(key, value) {
                            // Alert Toastr
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.error("Atención: " + value);
                        });
                        $("#saveBtn").html("Guardar Cambios");
                    },

                });
            });

            $('body').on('click', '.deleteCategory', function() {
                Swal.fire({
                    title: 'Estás seguro de eliminarlo?',
                    text: "Si lo haces, no podras revertirlo!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Estoy seguro!'
                }).then((isConfirm) => {
                    if (isConfirm.value) {
                        Swal.fire(
                            'Borrado!',
                            'El registro ha sido eliminado correctamente.',
                            'success'
                        )
                        var cicle_id = $(this).data("id");
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('foda-models.store') }}" + '/' + cicle_id,
                            success: function(data) {
                                table.draw();
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                })
            });
        });
    </script>
@stop
