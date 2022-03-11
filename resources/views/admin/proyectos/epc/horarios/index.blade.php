@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Gestionar Talentos Humanos</h4>

                    @can('role-create')
                        <a class="btn btn-success" href="javascript:void(0)" id="crearHorario"> Nuevo Ítem</a>
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
                                    <th>Inicio</th>
                                    <th>Fin</th>
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
                                <form id="horarioForm" name="horarioForm" class="form-horizontal">
                                    <input type="hidden" name="horario_id" id="horario_id">
                                    <div class="form-group">
                                        {{	Form::label('item', 'Ítem:')	}}  
                                        {{	Form::text('item', null,['class'=>'form-control'])	}}
                                    </div>
                                    
                                    <div class="form-group">
                                        {{	Form::label('start_time', 'Hora Inicio:')	}}
                                        {{ Form::time('start_time', \Carbon\Carbon::now(), ['class'=>'form-control']) }}
	                                </div>

                                    <div class="form-group">
                                        {{	Form::label('end_time', 'Hora Final:')	}}
	                                    {{	Form::time('end_time',\Carbon\Carbon::now(), ['class'=>'form-control', 'style'=>'width:100%'])	}}
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
                ajax: "{{ route('proyectos-epc-horarios.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'item',
                        name: 'item'
                    },
                    {
                        data: 'start_time',
                        name: 'start_time'
                    },

                    {
                        data: 'end_time',
                        name: 'end_time'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#crearHorario').click(function() {
                $('#modalHeading').html("Nuevo Item");
                $('#ajaxModal').modal('show');
                $('#horarioForm').trigger("reset");
            });
            
            $('body').on('click', '.editHorario', function() {
                var horario_id = $(this).data('id');
                $.get("{{ route('proyectos-epc-horarios.index') }}" + '/' + horario_id +
                    '/edit', function(data) {
                        $('#modalHeading').html("Editar Item");
                        $('#ajaxModal').modal('show');
                        $('#horario_id').val(data.id);
                        $('#item').val(data.item);
                        $('#start_time').val(data.start_time);
                        $('#end_time').val(data.end_time);
                    })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando...');
                $.ajax({
                    data: $('#horarioForm').serialize(),
                    url: "{{ route('proyectos-epc-horarios.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data.id === null) {
                            Swal.fire(
                                'Exelente!',
                                'Nuevo ítem de Horario creado!',
                                'success'
                            )
                            $('#horarioForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            table.draw();
                        } else {
                            Swal.fire(
                                'Excelente!',
                                'Horario actualizado correctamente',
                                'success'
                            )
                            $('#horarioForm').trigger("reset");
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

            $('body').on('click', '.deleteHorario', function() {
                var horario_id = $(this).data("id");

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
                            url: "{{ route('proyectos-epc-horarios.store') }}" + '/' + horario_id,
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
