@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Gestionar Talentos Humanos</h4>

                    @can('role-create')
                        <a class="btn btn-success" href="javascript:void(0)" id="crearNuevoTalentoHumano"> Nuevo Ítem</a>
                    @endcan

                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('proyectos-epc-home') }}"> Atras</a>
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Hora</th>
                                    <th>Costo</th>
                                    <th width="280px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- MODAL --}}
                <div class="modal fade" id="ajaxModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modalHeading"></h4>
                            </div>
                            <div class="modal-body">
                                <form id="tthhForm" name="tthhForm" class="form-horizontal">
                                    <input type="hidden" name="tthh_id" id="tthh_id">
                                    <div class="form-group">
                                        {{	Form::label('item', 'Ítem:')	}}  
                                        {{	Form::text('item', null,['class'=>'form-control'])	}}
                                    </div>
                                    <div class="form-group">
                                        {{	Form::label('type', 'Tipo') }}
                                        {{	Form::select('type', array(
                                                'Auxiliar Área Administrativa'   => 'Auxiliar Área Administrativa',
                                                'Auxiliar Área Salud'   => 'Auxiliar Área Salud',
                                                'Auxiliar Apoyo Salud' => 'Auxiliar Apoyo Salud',
                                                'Directivo'  => 'Directivo',
                                                'Profesional Área Administrativa'  => 'Profesional Área Administrativa',
                                                'Profesional Área Salud'  => 'Profesional Área Salud',
                                                'Técnico Área Administrativa'  => 'Técnico Área Administrativa',
                                                'Técnico Área Salud'  => 'Técnico Área Salud',
                                                ),null, ['class' => 'form-control', 'placeholder'=>'', 'id'=>'type','style'=>'width:100%', 'selected'])
                                        }}
                                    </div>
                                    <div class="form-group">
                                        {{	Form::label('hours', 'Horas:')	}}
	                                    {{	Form::text('hours', null,['class'=>'form-control', 'id'=>'hours'])	}}
                                    </div>
                                    <div class="form-group">
                                        {{	Form::label('cost', 'Costo:')	}}
	                                    {{	Form::text('cost', null,['class'=>'form-control'])	}}
                                    </div>
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">Guardar
                                            cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- FIN MODAL --}}
            </div>
        </div>
    </div>
@section('scripts')
    <script type="text/javascript">
         $('#type').select2({
			placeholder:"Seleccion el Tipo",
        });

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('proyectos-epc-tthh.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'item',
                        name: 'item'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },

                    {
                        data: 'hours',
                        name: 'hours'
                    },
                    {
                        data: 'cost',
                        name: 'cost'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#crearNuevoTalentoHumano').click(function() {
                var id = $('#tthh_id').val('');
                $('#modalHeading').html("Nuevo Item");
                $('#ajaxModal').modal('show');
                $('#tthhForm').trigger("reset");
                $('#type').val(null).trigger('change');
            });

            $('body').on('click', '.editTalentoHumano', function() {
                var tthh_id = $(this).data('id');
                $.get("{{ route('proyectos-epc-tthh.index') }}" + '/' + tthh_id + '/edit', function(
                    data) {
                    $('#modalHeading').html("Editar Item");
                    $('#ajaxModal').modal('show');
                    $('#tthh_id').val(data.id);
                    $('#item').val(data.item);
                    $('#type').val(data.type).trigger('change');
                    $('#hours').val(data.hours);
                    $('#cost').val(data.cost);
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando...');
                $.ajax({
                    data: $('#tthhForm').serialize(),
                    url: "{{ route('proyectos-epc-tthh.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data.id === null) {
                            Swal.fire(
                                'Exelente!',
                                'Nuevo ítem de Talento Humano creado!',
                                'success'
                            )
                            $('#tthhForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            table.draw();
                        }
                        else{
                            Swal.fire(
                                'Excelente!',
                                'Actualizado correctamente',
                                'success'
                            )
                            $('#tthhForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            table.draw();
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Guardar Cambios');
                    }
                });
            });

            $('body').on('click', '.deleteTalentoHumano', function() {
                var tthh_id = $(this).data("id");

                Swal.fire({
                    title: 'Estás seguro de eliminar?',
                    text: "No podrás revertir esta acción",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "Cancelar",
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, estoy seguro!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('proyectos-epc-tthh.store') }}" + '/' +
                                tthh_id,
                            success: function(data) {
                                table.draw();
                                Swal.fire(
                                    'Borrado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                )
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
@endsection
@endsection
