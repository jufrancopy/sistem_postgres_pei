@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info">
                <h4 class="card-title ">Gestionar Prestaciones</h4>

                @can('role-create')
                <a class="btn btn-success" href="javascript:void(0)" id="crearPrestacion"> Nuevo Ítem</a>
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
                                <th>Detalles</th>
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
                            <form id="prestacionForm" name="prestacionForm" class="form-horizontal">
                                <input type="hidden" name="prestacion_id" id="prestacion_id">
                                <div class="form-group">
                                    {{ Form::label('item', 'Ítem:') }}
                                    {{ Form::text('item', null,['class'=>'form-control']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('type', 'Tipo') }}
                                    {{ Form::select('type', array(
                                    'Consulta' => 'Consulta',
                                    'Cirugía' => 'Cirugía',
                                    'Fisioterapia' => 'Fisioterapia',
                                    'Diagnóstico'=>'Diagnóstico'),
                                    null, ['class' => 'form-control', 'placeholder'=>'',
                                    'id'=>'type','style'=>'width:100%', 'selected'])
                                    }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('cost', 'Costo:') }}
                                    {{ Form::text('cost', null,['class'=>'form-control']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('detail', 'Detalle:') }}
                                    {{ Form::textarea('detail', null,['class'=>'form-control', 'id'=>'detail']) }}
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
            placeholder: "Seleccione el Tipo",
        });
        function CKEClear(){
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances.detail.setData('');
                }
            }
        
        function CKEUpdate(){
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances.detail.updateElement();
                }
            }
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('proyectos-epc-prestaciones.index') }}",
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
                        data: 'detail',
                        name: 'detail'
                        // render: $.fn.DataTable.render.text(),
                        
                    },
                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#crearPrestacion').click(function() {
                $('#modalHeading').html("Nuevo Item");
                $('#ajaxModal').modal('show');
                $('#prestacionForm').trigger("reset");
                $('#prestacion_id').val('');
                $('#type').val(null).trigger('change');
                CKEDITOR.instances.detail.setData('');
            });
            
            
            $('body').on('click', '.editPrestacion', function() {
                var prestacion_id = $(this).data('id');
                $.get("{{ route('proyectos-epc-prestaciones.index') }}" + '/' + prestacion_id +
                    '/edit', function(data) {
                        $('#modalHeading').html("Editar Item");
                        $('#ajaxModal').modal('show');
                        $('#prestacion_id').val(data.id);
                        $('#item').val(data.item);
                        $('#type').val(data.type).trigger('change');
                        $('#cost').val(data.cost);
                        CKEDITOR.instances.detail.setData(data.detail)
                        
                    })
                });

            $('#saveBtn').click(function(e) {   
                CKEUpdate()
                e.preventDefault();
                $(this).html('Enviando...');
                $.ajax({
                    data: $('#prestacionForm').serialize(),
                    url: "{{ route('proyectos-epc-prestaciones.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data.id === null) {
                            Swal.fire(
                                'Exelente!',
                                'Nuevo ítem de Turno creado!',
                                'success'
                            )
                            $('#prestacionForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            table.draw();
                            
                        } else {
                            Swal.fire(
                                'Excelente!',
                                'Turno actualizado correctamente',
                                'success'
                            )
                            $('#prestacionForm').trigger("reset");
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
            CKEDITOR.replace('detail');

            $('body').on('click', '.deletePrestacion', function() {
                var prestacion_id = $(this).data("id");

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
                            url: "{{ route('proyectos-epc-prestaciones.store') }}" + '/' + prestacion_id,
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