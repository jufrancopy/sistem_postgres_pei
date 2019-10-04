@extends('layouts.master')
@section('content')
<div class="container">
    <section class="content-header">
        <ol class="breadcrumb ">
            <li><a href="{{route('accesos')}}"><i class="material-icons">arrow_back</i>Volver a Administracion</a></li>
        </ol>
    </section>
    <h3>Gestionar Roles</h3>
    @can('organigrama-create')
    <a class="btn btn-success" href="javascript:void(0)" id="crearNuevoRol"> Nueva Dependencia</a>
    @endcan
    
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Permisos</th>
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
                        {{ Form::label('permisos', 'Permisos:') }}
                        <select multiple="multiple" name="permiso_id[]" id="permiso_id" class="js-example-responsive" style="width:100%">
                            @foreach($permisos as $key => $value)
                            {-- in_array verifica el valor (llave => valor) este contenido en el array --}
                            <option value="{{ $key }}" {{ in_array($key, $permisosChecked) ? 'selected' : null }}>{{ $value }}</option>
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
                    data: 'permissions[].name',
                    name: 'permissions'
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

                var permisos = [];
                $(data.permissions).each(function(index, element)
                    {
                        permisos.push(element.id)
                    })
                $('#permiso_id').val(permisos).trigger('change');
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