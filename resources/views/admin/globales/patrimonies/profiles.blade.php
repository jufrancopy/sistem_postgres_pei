@extends('layouts.master')
@section('title', 'Perfil de Patrimonios')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Listado de Perfiles de Patrimonio</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Lista de Perfil de Patrimonios</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewPatrimonyProfile">
                            Nuevo Perfil de Patrimonio</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Dependencia</th>
                                        <th>Descripción</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="patrimonyProfileModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="patrimonyProfileForm" name="patrimonyProfileForm" class="form-horizontal">

                                        {{ Form::hidden('patrimony_profile_id', null, ['id' => 'patrimony_profile_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('dependency_id', 'Seleccione Dependencia Responsable:') }}
                                            {!! Form::select('dependency_id', [], null, [
                                                'id' => 'dependency',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="mision mb-2">
                                            {{ Form::label('description', 'Descripción:', ['class' => 'control-label']) }}
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
                </div>
            </div>
        </div>
    @stop

    @section('scripts')
        {{-- My custom scripts --}}
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
                    ajax: "{{ route('globales.patrimony-profiles.index') }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'dependency',
                        name: 'dependency'
                    }, {
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                return $('<div>').html(data).text()
                            }
                            return data;
                        }
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }]
                });

                // Función para inicializar Select2
                function initializeSelect2(selector, placeholder, url) {
                    selector.val("").select2({
                        placeholder: placeholder,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name || item
                                                .dependency, // Use 'name' or 'dependency' depending on the selector
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                }
                var descriptionEditor;
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .then(editor => {
                        descriptionEditor = editor;
                    })
                    .catch(err => {
                        console.error(err.stack);
                    });


                $('#createNewPatrimonyProfile').click(function() {
                    $('#patrimony_profile_id').val('');
                    $('#patrimonyProfileForm').trigger("reset");
                    $('#modalHeading').html("Nuevo Perfil de Patrimonio");
                    $('#patrimonyProfileModal').modal('show');

                    // Inicializar el selector de dependencia
                    initializeSelect2($("#dependency"), 'Seleccione la dependencia',
                        '{{ route('globales.get-dependencies') }}');
                });

                $('body').on('click', '.editProfilePatrimony', function() {
                    var groupID = $(this).data('id');
                    $.get("{{ route('globales.groups.index') }}" + '/' + groupID + '/edit', function(data) {
                        $('#modalHeading').html("Editar Perfil de Patrimonio");
                        $('#saveBtn').val("edit-user");
                        $('#patrimonyProfileModal').modal('show');
                        $('#patrimonyProfileForm').trigger("reset");
                        $('.errors').removeClass("alert alert-danger")
                        $('#patrimony_profile_id').val(data.group.id);
                        $('#name').val(data.group.name);
                    });
                });

                $('#saveBtn').click(function(e) {
                    e.preventDefault();
                    $(this).html('Actualizando información..');

                    var data = new FormData();
                    var form_data = $('#patrimonyProfileForm').serializeArray();

                    $.each(form_data, function(key, input) {
                        data.append(input.name, input.value);
                    });

                    data.append('description', descriptionEditor.getData());
                    $.ajax({
                        data: data,
                        url: "{{ route('globales.patrimony-profiles.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data) {
                                $(".success").text(data.success).addClass(
                                    'alert alert-success');
                                setTimeout(function() {
                                    $(".success").hide().html('');
                                }, 5000);
                            }
                            $('#patrimonyProfileForm').trigger("reset");
                            $('#patrimonyProfileModal').modal('hide');
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
                            $('#saveBtn').html('Guardar Cambios');
                        }

                    });
                });

                $('body').on('click', '.deleteGroup', function() {
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
                                url: "{{ route('globales.groups.store') }}" + '/' +
                                    cicle_id,
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
