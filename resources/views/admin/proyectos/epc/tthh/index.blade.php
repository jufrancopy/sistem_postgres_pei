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
                        <a class="btn btn-warning pull-right" href="{{ route('globales-dashboard') }}"> Atras</a>
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


                <div class="modal fade" id="ajaxModel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading"></h4>
                            </div>
                            <div class="modal-body">
                                <form id="tthhForm" name="tthhForm" class="form-horizontal">
                                    <input type="hidden" name="tthh_id" id="tthh_id">
                                    <div class="form-group">
                                        <label for="item" class="col-sm-4 control-label">Item</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="item" name="item" value=""
                                                maxlength="50" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="type" class="col-sm-4 control-label">Tipo</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="type" name="type" value=""
                                                maxlength="50" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="hours" class="col-sm-4 control-label">Hora</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="hours" name="hours" value=""
                                                maxlength="50" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cost" class="col-sm-4 control-label">Costo</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="cost" name="cost" value=""
                                                maxlength="50" required="">
                                        </div>
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
                $('#modelHeading').html("Nuevo Item");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.editTalentoHumano', function() {
                var tthh_id = $(this).data('id');
                $.get("{{ route('proyectos-epc-tthh.index') }}" + '/' + tthh_id + '/edit', function(
                    data) {
                    $('#modelHeading').html("Editar Item");
                    $('#ajaxModel').modal('show');
                    $('#tthh_id').val(data.id);
                    $('#item').val(data.item);
                    $('#type').val(data.type);
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
                        console.log(data)
                        
                        if (data) {
                            Swal.fire(
                                'Excelente!',
                                'Has actualizado los registros!',
                                'success'
                            )
                            $('#tthhForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                        else{
                            Swal.fire(
                                'Exelente!',
                                'Creaste!',
                                'success'
                            )
                            $('#tthhForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
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
