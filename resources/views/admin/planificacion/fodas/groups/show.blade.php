@extends('layouts.master')
@section('title', 'Agregar Sub - Grupos')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Agregar Sub - Grupos</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('foda-groups.index') }}">{{ $group->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Listado de Sub - Grupos</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="{{ $group->id }}" href="javascript:void(0)"
                            id="createNewGroup">
                            Agregar</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Miembros</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="subGroupForm" name="subGroupForm" class="form-horizontal">
                                        <div class="alert alert-danger errors" role="alert"></div>

                                        {{ Form::text('group_id', null, ['id' => 'group_id']) }}
                                        {{ Form::text('parent_id', $group->id, ['id' => 'parent_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('members', 'Miembros:') !!}
                                            {!! Form::select('user_id[]', [], null, [
                                                'class' => 'form-control',
                                                'style' => 'width:100%',
                                                'id' => 'members',
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
                    ajax: "{{ route('foda-groups.show', $group->id) }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'members',
                        name: 'members'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, ]
                });
                $('#createNewGroup').click(function() {
                    $('#saveBtn').val("create-user");
                    $('#group_id').val('');
                    $('#subGroupForm').trigger("reset");
                    $('#modalHeading').html("Nuevo Grupo");
                    $('#ajaxModal').modal('show');
                    $('.errors').removeClass("alert alert-danger")

                    var url = '{{ route('get-foda-users') }}';
                    //members
                    $("#members").val([]).change();
                    $("#members").val("");
                    $("#members").trigger("change");

                    var dependencies = $('#members').select2({
                        placeholder: 'Seleccione los miembros para el grupo',
                        allowClear: true,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                console.log(data)
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
                    $.get("{{ route('foda-groups.index') }}" + '/' + groupID + '/edit', function(data) {
                        $('#modalHeading').html("Editar Grupo");
                        $('#saveBtn').val("edit-user");
                        $('#ajaxModal').modal('show');
                        $('#subGroupForm').trigger("reset");
                        $('.errors').removeClass("alert alert-danger")
                        $('#group_id').val(data.id);
                        $('#name').val(data.name);
                    });
                });

                $('#saveBtn').click(function(e) {
                    e.preventDefault();
                    $(this).html('Enviando..');
                    $.ajax({
                        data: $('#subGroupForm').serialize(),
                        url: "{{ route('foda-groups.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                $(".success").text(data.success).addClass('alert alert-success');
                                setTimeout(function() {
                                    $(".success").hide().html('');
                                }, 5000);
                            }
                            $('#subGroupForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            $(".success").removeAttr("style");
                            table.draw();
                        },

                        error: function(data) {
                            var obj = data.responseJSON.errors;
                            $.each(obj, function(key, value) {
                                $(".errors").fadeIn().append($("<p>" + value + "</p>")
                                    .addClass("alert alert-danger"));
                                setTimeout(function() {
                                    $(".errors").fadeOut().html('');
                                }, 5000);
                            });
                            $('#saveBtn').html('Guardar Cambios');
                        }

                    });
                });

                $('body').on('click', '.deleteCycle', function() {
                    Swal.fire({
                        title: 'Estás seguro de eliminarlo?',
                        text: "Si lo haces, no podras revertirlo!",
                        type: 'warning',
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
                                url: "{{ route('foda-groups.store') }}" + '/' + cicle_id,
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
