@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Gestión de Infraestructura</h4>

                    @can('role-create')
                        <a class="btn btn-success" href="javascript:void(0)" id="crearInfraetructura">Nuevo Ítem</a>
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
                                    <th>Costo</th>
                                    <th>Medida</th>
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
                                <form id="infraestructuraForm" name="infraestructuraForm" class="form-horizontal">
                                    <input type="hidden" name="infraestructura_id" id="infraestructura_id">
                                    <div class="form-group">
                                        {{	Form::label('item', 'Ítem:')	}}  
                                        {{	Form::text('item', null,['class'=>'form-control'])	}}
                                    </div>
                                    
                                    <div class="form-group">
                                        {{	Form::label('type', 'Tipo') }}
                                        {{	Form::select('type', array(
                                                'Servicio'   => 'Servicio',
                                                'Ambulatorio' => 'Ambulatorio',
                                                'Administrativo'  => 'Administrativo',
                                                'Hospitalización'=>'Hospitalización',
                                                'Quirúrgico'=>'Quirúrgico'),
                                                null, ['class' => 'form-control', 'placeholder'=>'', 'id'=>'type','style'=>'width:100%', 'selected'])
                                        }}
                                    </div>

                                    <div class="form-group">
                                        {{	Form::label('measurement', 'Medidas:')	}}
	                                    {{	Form::number('measurement', null,['class'=>'form-control'])	}}
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
                ajax: "{{ route('proyectos-epc-infraestructuras.index') }}",
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
                        data: 'cost',
                        name: 'cost'
                    },
                    {
                        data: 'measurement',
                        name: 'measurement'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#crearInfraetructura').click(function() {
                $('#modalHeading').html("Nuevo Item");
                $('#ajaxModal').modal('show');
                $('#infraestructuraForm').trigger("reset");
                $('#type').val(null).trigger('change');
            });
            
            $('body').on('click', '.editInfraestructura', function() {
                var infraestructura_id = $(this).data('id');
                $.get("{{ route('proyectos-epc-infraestructuras.index') }}" + '/' + infraestructura_id +
                    '/edit', function(data) {
                        $('#modalHeading').html("Editar Item");
                        $('#ajaxModal').modal('show');
                        $('#infraestructura_id').val(data.id);
                        $('#item').val(data.item);
                        $('#type').val(data.type).trigger('change');
                        $('#measurement').val(data.measurement);
                        $('#cost').val(data.cost);
                    })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando...');
                $.ajax({
                    data: $('#infraestructuraForm').serialize(),
                    url: "{{ route('proyectos-epc-infraestructuras.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data.id === null) {
                            Swal.fire(
                                'Exelente!',
                                'Nuevo ítem de Infraestructura creado!',
                                'success'
                            )
                            $('#infraestructuraForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            table.draw();
                        } else {
                            Swal.fire(
                                'Excelente!',
                                'otro Servicio actualizado correctamente',
                                'success'
                            )
                            $('#infraestructuraForm').trigger("reset");
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

            $('body').on('click', '.deleteInfraestructura', function() {
                var infraestructura_id = $(this).data("id");

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
                            url: "{{ route('proyectos-epc-infraestructuras.store') }}" + '/' + infraestructura_id,
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
