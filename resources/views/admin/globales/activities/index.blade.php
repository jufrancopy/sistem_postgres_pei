@extends('layouts.master')
@section('title', 'Grupos')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Evento</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lista de Actividades</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewActivity">
                            Nueva Actividad</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Responables</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="activityModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="activityHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="activityForm" name="activityForm" class="form-horizontal">
                                        <div class="alert alert-danger errors" role="alert"></div>

                                        {{ Form::hidden('activity_id', null, ['id' => 'activity_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('type', 'Tipo:') }}
                                            {!! Form::select('type', ['scrum' => 'Scrum', 'kanba' => 'Kanba'], null, [
                                                'id' => 'type',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('responsibles', 'Asignar Responsables:') }}
                                            {!! Form::select('responsible_id[]', [], null, [
                                                'id' => 'responsibles',
                                                'style' => 'width:100%',
                                                'multiple',
                                            ]) !!}
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
                    ajax: "{{ route('globales.activities.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        }, {
                            data: 'name',
                            name: 'name'
                        }, {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'responsibles',
                            name: 'responsibles',
                            render: function(data, type, full, meta) {
                                var responsiblesArray = data.split(', ');

                                var responsiblesHtml = '';

                                responsiblesArray.forEach(function(task) {
                                    responsiblesHtml += '<span class="badge badge-secondary">' +
                                        task + '</span> ';
                                });

                                return responsiblesHtml;
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });


                $('#createNewActivity').click(function() {
                    $('#saveBtn').val("create-user");
                    $('#activity_id').val('');
                    $('#activityForm').trigger("reset");
                    $('#activityHeading').html("Nueva Actividad");
                    $('#activityModal').modal('show');
                    $('#type').select2({
                        placeholder: 'Elige un Tipo'
                    });
                    $('.errors').removeClass("alert alert-danger")

                    //Responsibles
                    var url = '{{ route('globales.get-users') }}';
                    $("#responsibles").val([]).change();
                    $("#responsibles").trigger("change");

                    $('#responsibles').select2({
                        allowClear: true,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                });

                $('body').on('click', '.editGroup', function() {
                    var groupID = $(this).data('id');
                    $.get("{{ route('globales.groups.index') }}" + '/' + groupID + '/edit', function(data) {
                        $('#modalHeading').html("Editar Evento " + data.group.name);
                        $('#saveBtn').val("edit-user");
                        $('#activityModal').modal('show');
                        $('#activityForm').trigger("reset");
                        $('.errors').removeClass("alert alert-danger")
                        $('#activity_id').val(data.group.id);
                        $('#name').val(data.group.name);
                    });
                });

                $('#saveBtn').click(function(e) {
                    e.preventDefault();
                    $(this).html('Enviando..');
                    $.ajax({
                        data: $('#activityForm').serialize(),
                        url: "{{ route('globales.groups.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                $(".success").text(data.success).addClass(
                                    'alert alert-success');
                                setTimeout(function() {
                                    $(".success").hide().html('');
                                }, 5000);
                            }
                            $('#activityForm').trigger("reset");
                            $('#activityModal').modal('hide');
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
