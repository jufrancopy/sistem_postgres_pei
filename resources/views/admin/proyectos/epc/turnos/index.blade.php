@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Gestionar Turnos</h4>

                    @can('role-create')
                        <a class="btn btn-success" href="javascript:void(0)" id="crearTurno"> Nuevo Ítem</a>
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
                                <form id="turnoForm" name="turnoForm" class="form-horizontal">
                                    <input type="hidden" name="turno_id" id="turno_id">
                                    <div class="form-group">
                                        {{	Form::label('item', 'Ítem:')	}}  
                                        {{	Form::text('item', null,['class'=>'form-control'])	}}
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
                ajax: "{{ route('proyectos-epc-turnos.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'item',
                        name: 'item'
                    },
                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#crearTurno').click(function() {
                $('#modalHeading').html("Nuevo Item");
                $('#ajaxModal').modal('show');
                $('#turnoForm').trigger("reset");
            });
            
            $('body').on('click', '.editTurno', function() {
                var turno_id = $(this).data('id');
                $.get("{{ route('proyectos-epc-turnos.index') }}" + '/' + turno_id +
                    '/edit', function(data) {
                        $('#modalHeading').html("Editar Item");
                        $('#ajaxModal').modal('show');
                        $('#turno_id').val(data.id);
                        $('#item').val(data.item);
                    })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando...');
                $.ajax({
                    data: $('#turnoForm').serialize(),
                    url: "{{ route('proyectos-epc-turnos.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data.id === null) {
                            Swal.fire(
                                'Exelente!',
                                'Nuevo ítem de Turno creado!',
                                'success'
                            )
                            $('#turnoForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            table.draw();
                        } else {
                            Swal.fire(
                                'Excelente!',
                                'Turno actualizado correctamente',
                                'success'
                            )
                            $('#turnoForm').trigger("reset");
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

            $('body').on('click', '.deleteTurno', function() {
                var turno_id = $(this).data("id");

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
                            url: "{{ route('proyectos-epc-turnos.store') }}" + '/' + turno_id,
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
