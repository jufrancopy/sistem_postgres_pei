@extends('layouts.master')
@section('title', 'Perfiles')

@section('content_header')
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Globales</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ciclos</li>
                </ol>
            </nav>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="success"></div>
                    <a class="btn btn-success mb-2" href="javascript:void(0)" id="createNewProfile"> Agregar Perfil</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered data-table display nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nombre</th>
                                    <th>Contexto</th>
                                    <th width="280px">Accion</th>
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
                                <h4 class="modal-title" id="modelHeading"></h4>
                            </div>
                            <div class="modal-body">
                                <form id="cycleForm" name="cycleForm" class="form-horizontal">
                                    <div class="alert alert-danger errors" role="alert"></div>

                                    {{ Form::hidden('cycle_id', null, ['id' => 'cycle_id']) }}

                                    <div class="form-group">
                                        {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('contexto', 'Contexto:') }}
                                        {{ Form::text('contexto', null, ['class' => 'form-control', 'id' => 'contexto']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('modelo_id', 'Elija el Modelo:') }}
                                        {!! Form::select('modelo_id', $modelos, null, [
                                            'placeholder' => 'Seleccione el Modelo',
                                            'class' => 'js-example-responsive',
                                            'style' => 'width:100%',
                                        ]) !!}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('dependency_id', 'Seleccione Dependencia Responsable:') }}
                                        {!! Form::select('dependency_id', $dependencies, null, [
                                            'placeholder' => 'Seleccione la Dependencia Responsable',
                                            'class' => 'js-example-responsive',
                                            'style' => 'width:100%',
                                        ]) !!}
                                    </div>


                                    <div class="form-group specialties">
                                        {!! Form::label('categorias', 'Asingne una o variasCategorias:') !!}
                                        {!! Form::select('categoria_id[][]', [], null, [
                                            'class' => 'form-control categorias',
                                            'style' => 'width:100%',
                                            'id' => 'categories',
                                            'multiple',
                                        ]) !!}
                                    </div>


                                    {{-- <div class="form-group">

                                        {{ Form::label('categorias', 'Asingne una o variasCategorias:') }}
                                        <select multiple="multiple" name="categoria_id[]" id="categories"
                                            class="categorias" style="width:100%">
                                            @foreach ($categorias as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ in_array($key, $categoriasChecked) ? 'selected' : null }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                            <input type="checkbox" id="checkbox">Seleccionar todo
                                        </select>
                                    </div> --}}


                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">Guardar
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
                ajax: "{{ route('foda-perfiles.index') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'nombre',
                    name: 'nombre'
                }, {
                    data: 'contexto',
                    name: 'contexto'
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }, ]
            });

            $('#createNewProfile').click(function() {
                $('#saveBtn').val("create-user");
                $('#cycle_id').val('');
                $('#cycleForm').trigger("reset");
                $('#modelHeading').html("Nuevo Perfil");
                $('#ajaxModal').modal('show');
                $('.errors').removeClass("alert alert-danger")

                $("#categories").val([]).change();
                $("#categories").val("");
                $("#categories").trigger("change");

                var dependencies = $('#categories').select2({
                    placeholder: 'Seleccione las Categorías para Analizar',
                    ajax: {
                        url: '{{ route('get-foda-categories') }}',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nombre,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });

            });

            $('body').on('click', '.editProfile', function() {
                var profileId = $(this).data('id');
                $.get("{{ route('foda-perfiles.index') }}" + '/' + profileId + '/edit', function(data) {
                    $('#modelHeading').html("Editar Ciclo");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModal').modal('show');
                    $('#cycleForm').trigger("reset");
                    $('.errors').removeClass("alert alert-danger")
                    $('#cycle_id').val(data.id);
                    $('#name').val(data.nombre);
                });
            });


            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');
                $.ajax({
                    data: $('#cycleForm').serialize(),
                    url: "{{ route('foda-perfiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $(".success").text(data.success).addClass('alert alert-success');
                            setTimeout(function() {
                                $(".success").hide().html('');
                            }, 5000);
                        }
                        $('#cycleForm').trigger("reset");
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

            $('body').on('click', '.deleteProfile', function() {
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
                            url: "{{ route('foda-perfiles.store') }}" + '/' + cicle_id,
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
