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
                <h4 class="card-title ">Gestionar Permisos</h4>

                @can('role-create')
                <a class="btn btn-success" href="javascript:void(0)" id="crearNuevoRol"> Nuevo Permiso</a>
                @endcan

                <div class="pull-right">
                    <a class="btn btn-warning pull-right" href="{{ route('globales.dashboard') }}"> Atras</a>
                </div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Detalles</th>
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
                            <form id="permissionForm" name="permissionForm" class="form-horizontal">
                                <input type="hidden" name="permiso_id" id="permiso_id">
                                <div class="form-group">
                                    <label for="name" class="col-sm-4 control-label">Nombre</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="name" name="name" value="" maxlength="50" required="">
                                    </div>
                                </div>

                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-success" id="saveBtn" value="create">Guardar cambios
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
            ajax: "{{ route('globales.permisos.index') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'guard_name',
                name: 'guard_name'
            }, {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }, ]
        });

        $('#crearNuevoRol').click(function() {
            $('#saveBtn').val("create-product");
            $('#permiso_id').val('');
            $('#permissionForm').trigger("reset");
            $('#modelHeading').html("Nuevo Permiso");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editPermiso', function() {
            var permiso_id = $(this).data('id');
            $.get("{{ route('globales.permisos.index') }}" + '/' + permiso_id + '/edit', function(data) {
                $('#modelHeading').html("Editar Permiso");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#permiso_id').val(data.id);
                $('#name').val(data.name);
                $('#detail').val(data.detail);
            })
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Enviando..');

            $.ajax({
                data: $('#permissionForm').serialize(),
                url: "{{ route('globales.permisos.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#permissionForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Guardar Cambios');
                }
            });
        });

        $('body').on('click', '.deletePermiso', function() {

            var permiso_id = $(this).data("id");
            confirm("Estas seguro de Borrar?!");

            $.ajax({
                type: "DELETE",
                url: "{{ route('globales.permisos.store') }}" + '/' + permiso_id,
                success: function(data) {
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });

    });
</script>
@endsection
@endsection