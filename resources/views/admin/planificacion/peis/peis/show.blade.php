@extends('layouts.master')
@section('title', 'Planificación Estratégica')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Módulo de Planificación Estratégica</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            @php
                $taskShowUrl = request()->cookie('task_show_url');
            @endphp


            @if ($taskShowUrl)
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ $taskShowUrl }}">Lista de Tareas</a>

                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Módulo de Planificación Estratégica</li>
                </ol>
            @else
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Módulo de Planificación Estratégica</li>
                </ol>
            @endif

        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card mision">
                            <div class="card-header">
                                <h2 for="">Misión </h2>
                            </div>
                            <div class="card-body">
                                <div class="mision">{!! $profile->mision !!}</div>
                            </div>
                            <div class="card-footer">
                                <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                    id="createMision">Definir
                                    Misión
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card vision">
                            <div class="card-header">
                                <h2 for="">Visión </h2>
                            </div>
                            <div class="card-body">
                                <div class="vision">
                                    {!! $profile->vision !!}
                                </div>
                            </div>
                            <div class="card-footer">
                                <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                    id="createVision">Definir
                                    Visión
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card values">
                            <div class="card-header">
                                <h2 for="">Valores </h2>
                            </div>
                            <div class="card-body">
                                {!! $profile->values !!}
                            </div>
                            <div class="card-footer">
                                <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                    id="createValues">Definir
                                    Valores
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </div>



                        {{-- <ul>
                            @foreach ($niveles as $nivel)
                            <li>{{ $nivel->nivel }}
                                <a class="btn btn-success btn-circle" href="{{ route('peis-crear-sub-nivel',[$nivelSuperior,$nivel->id]) }}"><i class="fa fa-plus"></i></a>
                                <a class="btn btn-info btn-circle" href="{{ route('peis-editar-sub-nivel',$nivel->id) }}"><i class="fa fa-edit"></i></a>
                                {!! Form::open(['route' => ['peis-eliminar-nivel', $nivelSuperior, $nivel->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                    <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar la Dependencia {{$nivel->nivel}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                {!! Form::close() !!}
                            </li>
                            <ul>
                                @foreach ($nivel->dependencies as $dependencia)
                                        <p class="badge badge-warning"> ({{$dependencia->dependency}})</p>
                                @endforeach
                            </ul>
                            
                            <hr>
                            <ul>
                                @foreach ($nivel->childrenNiveles as $childNivel)
                                    @include('admin.planificacion.peis.peis.child_nivel', ['child_nivel' => $childNivel])
                                @endforeach
                            </ul>
                            @endforeach
                        </ul> --}}

                    </div>

                    <div class="modal fade ajaxMisionModal" id="ajaxMisionModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeadingMision"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="misionForm" name="misionForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}
                                        {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                                        {{ Form::hidden('name', null, ['id' => 'name']) }}
                                        {{ Form::hidden('group_id', null, ['id' => 'group_id']) }}
                                        {{ Form::hidden('type', 'institucional', ['id' => 'type']) }}
                                        {{ Form::hidden('vision', null, ['class' => 'form-control', 'id' => 'vision']) }}
                                        {{ Form::hidden('values', null, ['class' => 'form-control', 'id' => 'values']) }}
                                        {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'period']) }}
                                        {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'denominator']) }}
                                        {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goal']) }}
                                        {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress']) }}


                                        <div class="mision mb-2">
                                            {{ Form::label('mision', 'MISIÓN:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('mision', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'mision',
                                            ]) }}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtnMision"
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxVisionModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeadingVision"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="visionForm" name="visionForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'vision_profile_id']) }}
                                        {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                                        {{ Form::hidden('name', null, ['id' => 'vision_name']) }}
                                        {{ Form::hidden('group_id', null, ['id' => 'vision_group_id']) }}
                                        {{ Form::hidden('type', 'institucional', ['id' => 'type']) }}
                                        {{ Form::hidden('mision', null, ['id' => 'vision_mision']) }}
                                        {{ Form::hidden('values', null, ['class' => 'form-control', 'id' => 'vision_values']) }}
                                        {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'period']) }}
                                        {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'denominator']) }}
                                        {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goal']) }}
                                        {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress']) }}

                                        <div class="vision mb-2">
                                            {{ Form::label('vision', 'Visión:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('vision', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'vision',
                                            ]) }}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtnVision"
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxValuesModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeadingValues"></h4>
                                </div>

                                <div class="modal-body">
                                    <form id="valuesForm" name="valuesForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'values_profile_id']) }}
                                        {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                                        {{ Form::hidden('name', null, ['id' => 'values_name']) }}
                                        {{ Form::hidden('group_id', null, ['id' => 'values_group_id']) }}
                                        {{ Form::hidden('mision', null, ['id' => 'values_mision']) }}
                                        {{ Form::hidden('vision', null, ['id' => 'values_vision']) }}
                                        {{ Form::hidden('type', 'institucional', ['id' => 'type']) }}
                                        {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'period']) }}
                                        {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'denominator']) }}
                                        {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goal']) }}
                                        {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress']) }}

                                        <div class="values mb-2">
                                            {{ Form::label('values', 'Visión:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('values', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'values',
                                            ]) }}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtnValues"
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxEjesModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalEjes"></h4>
                                </div>

                                <div class="modal-body">
                                    <form id="ejesForm" name="ejesForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'values_profile_id']) }}
                                        {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                                        {{ Form::hidden('name', null, ['id' => 'values_name']) }}
                                        {{ Form::hidden('group_id', null, ['id' => 'values_group_id']) }}
                                        {{ Form::hidden('mision', null, ['id' => 'values_mision']) }}
                                        {{ Form::hidden('vision', null, ['id' => 'values_vision']) }}
                                        {{ Form::hidden('type', 'institucional', ['id' => 'type']) }}
                                        {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'period']) }}
                                        {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'denominator']) }}
                                        {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goal']) }}
                                        {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress']) }}

                                        <div class="ejes mb-2">
                                            {{ Form::label('ejes', 'Ejes:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('ejes', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'ejes',
                                            ]) }}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtnValues"
                                                value="create">Guardar
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
                    ajax: "{{ route('pei-profiles.index') }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'group',
                        name: 'group'
                    }, {
                        data: 'analysts',
                        name: 'analysts',
                        render: function(data, type, full, meta) {
                            var analystsArray = data.split(', ');

                            var analystsHtml = '';

                            analystsArray.forEach(function(analyst) {
                                analystsHtml += '<span class="badge badge-secondary">' +
                                    analyst + '</span> ';
                            });

                            return analystsHtml;
                        }
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, ]
                });

                var misionEditor;

                ClassicEditor
                    .create(document.querySelector('#mision'))
                    .then(editor => {
                        misionEditor = editor;
                    })
                    .catch(err => {
                        console.error(err.stack);
                    });

                var visionEditor;

                ClassicEditor
                    .create(document.querySelector('#ajaxVisionModal #visionForm #vision'))
                    .then(editor => {
                        visionEditor = editor;
                    })
                    .catch(err => {
                        console.error(err.stack);
                    });

                var valuesEditor;

                ClassicEditor
                    .create(document.querySelector('#ajaxValuesModal #valuesForm #values'))
                    .then(editor => {
                        valuesEditor = editor;
                    })
                    .catch(err => {
                        console.error(err.stack);
                    })


                $('body').on('click', '#createMision', function() {
                    var profileID = $(this).data('id');
                    $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                        if (data.profile.mision === null) {
                            $('#modalHeadingMision').html("Misión");
                        } else {
                            $('#modalHeadingMision').html(data.profile.mision);
                        }
                        $('#saveBtnMision').val("edit-mision");
                        $('#ajaxMisionModal').modal('show');
                        $('#misionForm').trigger("reset");
                        $('.errors').removeClass("alert alert-danger")
                        $('#profile_id').val(data.profile.id);
                        $('#name').val(data.profile.name);
                        $('#type').val(data.profile.type);
                        $('#group_id').val(data.profile.group_id);
                        $('#values').val(data.profile.values);
                        $('#vision').val(data.profile.vision);
                        misionEditor.setData(data.profile.mision);
                    });
                });

                $('body').on('click', '#createVision', function() {
                    var profileID = $(this).data('id');
                    $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                        if (data.profile.mision === null) {
                            $('#modalHeadingVision').html("Visión");
                        } else {
                            $('#modalHeadingVision').html(data.profile.vision);
                        }
                        $('#saveBtn').val("edit-profile");
                        $('#ajaxVisionModal').modal('show');
                        $('#visionForm').trigger("reset");
                        $('.errors').removeClass("alert alert-danger")
                        $('#vision_profile_id').val(data.profile.id);
                        $('#vision_name').val(data.profile.name);
                        $('#vision_type').val(data.profile.type);
                        $('#vision_group_id').val(data.profile.group_id);
                        $('#vision_mision').val(data.profile.mision);
                        $('#vision_values').val(data.profile.values);

                        visionEditor.setData(data.profile.vision);
                    });
                });

                $('body').on('click', '#createValues', function() {
                    var profileID = $(this).data('id');
                    $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                        $('#modalHeadingValues').html("Valores");
                        $('#saveBtnValues').val("edit-values");
                        $('#ajaxValuesModal').modal('show');
                        $('#valuesForm').trigger("reset");
                        $('.errors').removeClass("alert alert-danger")
                        $('#values_profile_id').val(data.profile.id);
                        $('#values_name').val(data.profile.name);
                        $('#values_type').val(data.profile.type);
                        $('#values_group_id').val(data.profile.group_id);
                        $('#values_mision').val(data.profile.mision);
                        $('#values_vision').val(data.profile.vision);
                        valuesEditor.setData(data.profile.values);
                    });
                });

                $('body').on('click', '#createEjes', function() {
                    var profileID = $(this).data('id');
                    $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                        $('#modalHeadingEjes').html("Ejes");
                        $('#saveBtnEjes').val("edit-ejes");
                        $('#ajaxEjesModal').modal('show');
                        $('#ejesForm').trigger("reset");
                        $('#ejes_profile_id').val(data.profile.id);
                        $('#ejes_name').val(data.profile.name);
                        $('#ejes_type').val(data.profile.type);
                        $('#ejes_group_id').val(data.profile.group_id);
                        $('#ejes_mision').val(data.profile.mision);
                        $('#ejes_vision').val(data.profile.vision);
                        $('#ejes_eje').val(data.profile.ejes);
                        valuesEditor.setData(data.profile.values);
                    });
                });

                $('#saveBtnMision').click(function(e) {
                    e.preventDefault();
                    $(this).html('Enviando..');

                    var data = new FormData();
                    var form_data = $('#misionForm').serializeArray();

                    $.each(form_data, function(key, input) {
                        data.append(input.name, input.value);
                    });

                    data.append('mision', misionEditor.getData());

                    $.ajax({
                        data: data,
                        url: "{{ route('pei-profiles.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            console.log(data.profile.mision)
                            Swal.fire(
                                'Excelente!',
                                'Has Agregado una Misión.',
                                'success'
                            )

                            //Actaulizacmos La mision en el DOM
                            $('.card.mision .card-body').html(data.profile.mision);

                            $('#misionForm').trigger("reset");
                            $('#ajaxMisionModal').modal('hide');

                        },
                        error: function(data) {
                            var obj = data.responseJSON.errors;
                            $.each(obj, function(key, value) {
                                // Alert Toastr
                                toastr.options = {
                                    closeButton: true,
                                    progressBar: true,
                                };
                                toastr.error("Atención: " + value);
                            });

                            $('#saveBtnMision').html('Guardar Cambios');
                        }
                    });
                });

                $('#saveBtnVision').click(function(e) {
                    e.preventDefault();
                    $(this).html('Enviando..');

                    var data = new FormData();
                    var form_data = $('#visionForm').serializeArray();

                    $.each(form_data, function(key, input) {
                        data.append(input.name, input.value);
                    });

                    data.append('vision', visionEditor.getData());

                    $.ajax({
                        data: data,
                        url: "{{ route('pei-profiles.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            Swal.fire(
                                'Excelente!',
                                'Has Agregado una Visión.',
                                'success'
                            )

                            //Actaulizacmos La mision en el DOM
                            $('.card.vision .card-body').html(data.profile.vision);

                            $('#visionForm').trigger("reset");
                            $('#ajaxVisionModal').modal('hide');
                        },

                        error: function(data) {
                            var obj = data.responseJSON.errors;
                            $.each(obj, function(key, value) {
                                // Alert Toastr
                                toastr.options = {
                                    closeButton: true,
                                    progressBar: true,
                                };
                                toastr.error("Atención: " + value);
                            });
                            $('#saveBtnVision').html('Guardar Cambios');
                        }

                    });
                });

                $('#saveBtnValues').click(function(e) {
                    e.preventDefault();
                    $(this).html('Enviando..');

                    var data = new FormData();
                    var form_data = $('#valuesForm').serializeArray();

                    $.each(form_data, function(key, input) {
                        data.append(input.name, input.value);
                    });

                    data.append('values', valuesEditor.getData());

                    $.ajax({
                        data: data,
                        url: "{{ route('pei-profiles.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            Swal.fire(
                                'Excelente!',
                                'Has Agregado Valores.',
                                'success'
                            )

                            //Actaulizacmos La mision en el DOM
                            $('.card.values .card-body').html(data.profile.values);

                            $('#valuesForm').trigger("reset");
                            $('#ajaxValuesModal').modal('hide');
                        },

                        error: function(data) {
                            var obj = data.responseJSON.errors;
                            $.each(obj, function(key, value) {
                                // Alert Toastr
                                toastr.options = {
                                    closeButton: true,
                                    progressBar: true,
                                };
                                toastr.error("Atención: " + value);
                            });
                            $('#saveBtnVision').html('Guardar Cambios');
                        }

                    });
                });

                $('body').on('click', '.deleteGroup', function() {
                    Swal.fire({
                        title: 'Estás seguro de eliminarlo?',
                        text: "Si lo haces, no podras revertirlo!",
                        icon: 'warning',
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
                                url: "{{ route('globales.groups.store') }}" + '/' + cicle_id,
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
