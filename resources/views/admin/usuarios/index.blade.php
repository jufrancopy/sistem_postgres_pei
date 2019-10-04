@extends('layouts.master')
@section('content')
<div class="container">
    <section class="content-header">
        <ol class="breadcrumb ">
            <li><a href="{{route('accesos')}}"><i class="material-icons">arrow_back</i>Volver a Administracion</a></li>
        </ol>
    </section>
    <h3>Gestionar Roles</h3>
    <a class="btn btn-success" href="javascript:void(0)" id="crearNuevoRol"> Nuevo Usuario</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th width="280px">Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="roleForm" name="roleForm" class="form-horizontal">
                    <input type="hidden" name="role_id" id="role_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" value="" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Correo</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="email" name="email" value="" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('roles', 'Roles:') }}
                        <select multiple="multiple" name="role_id[]" id="role_id" class="js-example-responsive" style="width:100%">
                            @foreach($roles as $key => $value)
                            <option value="{{ $key }}" {{ in_array($key, $rolesChecked) ? 'selected' : null }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
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
            ajax: "{{ route('roles.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'roles[].name',
                    name: 'roles'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#crearNuevoRol').click(function() {
            $('#saveBtn').val("create-product");
            $('#role_id').val('');
            $('#roleForm').trigger("reset");
            $('#modelHeading').html("Nuevo");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editRole', function() {
            var role_id = $(this).data('id');
            $.get("{{ route('roles.index') }}" + '/' + role_id + '/edit', function(data) {
                $('#modelHeading').html("Editar Rol");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#role_id').val(data.id);
                $('#permiso_id').val(data.id);
                $('#name').val(data.name);
                $('#detail').val(data.detail);
            })
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Enviando..');

            $.ajax({
                data: $('#roleForm').serialize(),
                url: "{{ route('roles.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#roleForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Guardar Cambios');
                }
            });
        });

        $('body').on('click', '.deleteRole', function() {

            var role_id = $(this).data("id");
            confirm("Estas seguro de Borrar?!");
            $.ajax({
                type: "DELETE",
                url: "{{ route('roles.store') }}" + '/' + role_id,
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