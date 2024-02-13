@extends('layouts.master')
@section('title', 'Alumnos')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Patrimonios</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lista de Cidudades por Departamentos</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewPatrimony"> <i
                                class="material-icons ">add_box</i> Nuevo Patrimonio</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Departamento</th>
                                        <th>Descipción</th>
                                        <th>Ciudad</th>
                                        <th>Ubicación</th>
                                        <th>Finca</th>
                                        <th>Cta Corriente</th>
                                        <th>Estado</th>
                                        <th>Inversión</th>
                                        <th>Transferencia</th>
                                        <th>Saldo</th>
                                        <th>Arrendatario</th>
                                        <th>Canon</th>
                                        <th>Periodo</th>
                                        <th>Resolución</th>
                                        <th>Contrato</th>
                                        <th>Desde</th>
                                        <th>Hasta</th>
                                        <th>Documento</th>
                                        <th>Superficie(m2)</th>
                                        <th>Superficie(ha)</th>
                                        <th>Superficie(sub)</th>
                                        <th>Superficie(edif)</th>
                                        <th>Valor(edif)</th>
                                        <th>Valor(superficie)</th>
                                        <th>Valor(Total)</th>
                                        <th>Renta</th>
                                        <th>Acciones</th>
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
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    Lista de Ciudades
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalStudent" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="studentForm" name="studentForm" class="form-horizontal"
                            enctype="multipart/form-data">
                            {{ Form::hidden('student_id', null, ['id' => 'student_id']) }}
                            {{ Form::hidden('user_id', auth()->user()->id) }}

                            <div class="form-group">
                                {{ Form::label('first_name', 'Nombre:', ['class' => 'control-label']) }}
                                {{ Form::text('first_name', null, ['class' => 'form-control', 'id' => 'first_name']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('last_name', 'Apellido:', ['class' => 'control-label']) }}
                                {{ Form::text('last_name', null, ['class' => 'form-control', 'id' => 'last_name']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('birth_date', 'Fecha de Nacimiento:', ['class' => 'control-label']) }}
                                {{ Form::date('birth_date', new \DateTime(), ['class' => 'form-control', 'id' => 'birth_date']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('born', 'Nacido en:', ['class' => 'control-label']) }}
                                {{ Form::text('born', null, ['class' => 'form-control', 'id' => 'born']) }}
                            </div>


                            <div class="form-group">
                                {{ Form::label('nacionality', 'Nacionalidad:', ['class' => 'control-label']) }}
                                {{ Form::text('nacionality', null, ['class' => 'form-control', 'id' => 'nacionality']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('doc_number', 'Cedula de Identidad:', ['class' => 'control-label']) }}
                                {{ Form::text('doc_number', null, ['class' => 'form-control', 'id' => 'doc_number']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('phone', 'Teléfono:', ['class' => 'control-label']) }}
                                {{ Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('email', 'Correo Electrónico:', ['class' => 'control-label']) }}
                                {{ Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('address', 'Dirección:', ['class' => 'control-label']) }}
                                {{ Form::text('address', null, ['class' => 'form-control', 'id' => 'address']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('marital_status', 'Estado Civil') }}
                                {{ Form::select(
                                    'marital_status',
                                    [
                                        'Casado' => 'Casado',
                                        'Soltero' => 'Soltero',
                                        'Divorciado/a' => 'Divorciado/a',
                                        'Viudo/a' => 'Viudo/a',
                                        null => 'Pendiente',
                                    ],
                                    null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'id' => 'maritalStatus',
                                        'style' => 'width:100%',
                                    ],
                                ) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('sex', 'Sexo') }}
                                {{ Form::select(
                                    'sex',
                                    [
                                        'Masculino' => 'Masculino',
                                        'Femenino' => 'Femenino',
                                        null => 'Pendiente',
                                    ],
                                    null,
                                    ['class' => 'form-control', 'placeholder' => '', 'id' => 'sex', 'style' => 'width:100%'],
                                ) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('age', 'Edad:', ['class' => 'control-label']) }}
                                {{ Form::text('age', null, ['class' => 'form-control', 'id' => 'age']) }}
                            </div>

                            <div class="form-group state">
                                {!! Form::label('state', 'Departamento:') !!}
                                {!! Form::select('state', [], null, ['class' => 'form-control', 'id' => 'state']) !!}
                            </div>

                            <div class="form-group city">
                                {!! Form::label('city', 'Ciudad:') !!}
                                {!! Form::select('city', [], null, ['class' => 'form-control', 'id' => 'city']) !!}
                            </div>

                            <div class="form-group locality">
                                {!! Form::label('locality', 'Barrio:') !!}
                                {!! Form::select('locality', [], null, ['class' => 'form-control', 'id' => 'locality']) !!}
                            </div>

                            <div class="form-group">
                                {{ Form::label('responsible', 'Encargado:', ['class' => 'control-label']) }}
                                {{ Form::text('responsible', null, ['class' => 'form-control', 'id' => 'responsible']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('responsible_phone', 'Teléfono del Encargado:', ['class' => 'control-label']) }}
                                {{ Form::text('responsible_phone', null, ['class' => 'form-control', 'id' => 'responsible_phone']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('blood_type', 'Grupo sanguíneo:', ['class' => 'control-label']) }}
                                {{ Form::text('blood_type', null, ['class' => 'form-control', 'id' => 'blood_type']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('file', 'Foto de Perfil:') }}
                                <div class="container-file">
                                    <div class="choose-img">
                                        <div class="holder">
                                            <img id="imgPreview" src="" />
                                        </div>
                                        <label for="file"
                                            class="btn btn-info d-block rounded-0">Subir</label>
                                        <input type="file" name="file" id="file"
                                            style="display:none">
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-success" id="saveBtn"
                                    value="create">Guardar
                                    cambios
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" id="modalShowStudent" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <section style="background-color: #eee;">
                            <div class="container py-5">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <img src="" class="rounded-circle img-fluid"
                                                    id="img" style="width: 150px;">
                                                <h5 class="my-3" id='ful_name_image'></h5>
                                                <p class="text-muted mb-4" id='branch'></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h2>Información Principal</h2>
                                            </div>
                                            <div class="card-body detailStudent">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Nombre</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id='first_name'></p>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Apellido</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="last_name"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Nro CI</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id='doc_number'></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Nacionalidad</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id='nacionality'></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Estado Civil</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id='marital_status'></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Correo</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id='email'></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Teléfono</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id='phone'></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Edad</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="age"></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Sexo</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="sex"></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Direccion</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="address"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Barrios</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="locality"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Ciudad</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="city"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Departamento</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="state"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 othes_information">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h2>Otras Informaciones</h2>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Grupo Sanguíneo</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="blood_type"></p>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Responsable</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="responsible"></p>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Nro de Contacto</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0" id="responsible_phone"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
        <style>
            .container-file {
                position: relative;
                display: flex;
                justify-content: center;
            }

            .choose-img {
                position: relative;
            }

            .choose-img img {
                left: 50%;
                max-width: 300px;
                max-height: 300px;
                min-width: 300px;
                min-height: 300px;
            }

            input[type="file"] {
                margin-top: 0px;
                margin-bottom: 5px;
            }

            .heading {
                font-family: Montserrat;
                font-size: 45px;
                color: green;
            }
        </style>
    @stop

    @section('js')

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
                    columnDefs: [{
                        "targets": 1,
                        "data": 'foto',
                        "render": function(data, type, row, meta) {
                            console.log(data)
                            return '<img src="' + data + '" alt="' + data +
                                '"height="32" width="32"/>';
                        }
                    }],
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
                    ajax: "{{ route('globales.patrimonies.index') }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'image',
                        name: 'image'
                    }, {
                        data: 'first_name',
                        name: 'first_name'
                    }, {
                        data: 'last_name',
                        name: 'last_name'
                    }, {
                        data: 'birth_date',
                        name: 'birth_date'
                    }, {
                        data: 'born',
                        name: 'born'
                    }, {
                        data: 'nacionality',
                        name: 'nacionality'
                    }, {
                        data: 'doc_number',
                        name: 'doc_number'
                    }, {
                        data: 'branch',
                        name: 'branch'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, ]
                });
                $('#createNewStudent').click(function() {
                    $('#saveBtn').val("create-career");
                    $('#studentForm').trigger("reset");
                    $('#student_id').val('');
                    $('#modelHeading').html("Nuevo Alumno");
                    $('#modalStudent').modal('show');
                    $('#branchs').val('').trigger("reset");
                    $('#maritalStatus').select2().val('').trigger('change');
                    $('#sex').select2().val('').trigger('change');
                    $('#state').select2().val('').trigger('change.select2');
                    $('#city').select2().val([]).trigger('change.select2');
                    $('#locality').select2().val([]).trigger('change.select2');



                    var imgPreview = "{{ asset('images/students/web/sin_foto.png') }}";
                    $('.holder img').attr('src', imgPreview);

                    $('#file').change(function() {
                        const file = this.files[0];
                        if (file) {
                            let reader = new FileReader();
                            reader.onload = function(event) {
                                $('.holder img').attr('src', event.target.result);
                            }
                            reader.readAsDataURL(file);
                        }
                    });

                    $('#state').on('change', onSelectStateChange);

                    function onSelectStateChange() {
                        var state = $(this).val();
                        console.log('Departamento:', state)

                        //Ajax
                        $.get('/admin/locality/' + state + '/cities', function(res) {
                            var html_select = '<option value="">Seleccione una Ciudad</option>'
                            for (var i = 0; i < res.length; ++i)
                                html_select += '<option value="' + res[i].desc_dist + '">' + res[i]
                                .desc_dist + '</option>';
                            $('#city').html(html_select).trigger('change');
                            $('#locality').change()
                        });
                    }

                    $('#city').on('change', onSelectCityChange);

                    function onSelectCityChange() {
                        var city = $(this).val();
                        console.log('Ciudad:', city)
                        //Ajax
                        $.get('/admin/locality/' + city + '/localities', function(data) {
                            var html_select = '<option value="">Seleccione un Barrio</option>'
                            for (var i = 0; i < data.length; ++i)
                                html_select += '<option value="' + data[i].desc_barrio_loc + '">' +
                                data[i].desc_barrio_loc + '</option>';
                            $('#locality').html(html_select).trigger('change');
                        });
                    }
                    // Search Branch
                    $('#branchs').select2({
                        placeholder: 'Asigne Local',
                        ajax: {
                            url: ruta,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                });

                $('body').on('click', '.showStudent', function() {
                    var studentID = $(this).data('id');
                    let url = '{{ route('globales.patrimonies.show', ':studentID') }}';
                    url = url.replace(':studentID', studentID);

                    $.get(url, function(data) {
                        $('#modalShowStudent').modal('show');
                        let studenImg = data.file
                        var ruta = "{{ asset('/images/students') }}"
                        var rutaImgNull = "{{ asset('/images/web/logo.png') }}"

                        var file_path = data.file_path;
                        if (studenImg == null) {
                            $('#img').attr('src', rutaImgNull);
                        } else {
                            $('#img').attr('src', ruta + '/' + file_path + '/t_' + studenImg);
                        }


                        $('#branch').html(data.branch.name);
                        $('.detailStudent #first_name').html(data.first_name);
                        $('.detailStudent #last_name').html(data.last_name);
                        $('.detailStudent #doc_number').html(data.doc_number);
                        $('.detailStudent #nacionality').html(data.nacionality);
                        $('.detailStudent #email').html(data.email);
                        $('.detailStudent #phone').html(data.phone);
                        $('.detailStudent #age').html(data.age);
                        $('.detailStudent #sex').html(data.sex);
                        $('.detailStudent #address').html(data.address);
                        $('.detailStudent #locality').html(data.locality);
                        $('.detailStudent #city').html(data.city);
                        $('.detailStudent #state').html(data.state);
                        $('.detailStudent #marital_status').html(data.marital_status);

                        // Others Informations
                        $('.othes_information #blood_type').html(data.blood_type);
                        $('.othes_information #responsible').html(data.responsible);
                        $('.othes_information #responsible_phone').html(data.responsible_phone);

                    })
                })

                $('body').on('click', '.editStudent', function() {
                    var studentID = $(this).data('id');
                    $.get("{{ route('globales.patrimonies.index') }}" + '/' + studentID + '/edit', function(data) {
                        $('#modelHeading').html("Editar Carrera");
                        $('#saveBtn').val("edit-student");
                        $('#modalStudent').modal('show');
                        $('#studentForm').trigger("reset");
                        $('#student_id').val(data.id);
                        $('#first_name').val(data.first_name);
                        $('#last_name').val(data.last_name);
                        $('#birth_date').val(data.birth_date);
                        $('#born').val(data.born);
                        $('#nacionality').val(data.nacionality);
                        $('#doc_number').val(data.doc_number);
                        $('#phone').val(data.phone);
                        $('#email').val(data.email);
                        $('#address').val(data.address);
                        $('#age').val(data.age);
                        $('#responsible').val(data.responsible);
                        $('#responsible_phone').val(data.responsible_phone);
                        $('#blood_type').val(data.blood_type);
                        $('.holder img').attr('src', "/images/students/" + data.file_path + '/' + data
                            .file).trigger('change');
                        // $('.holder img').attr('src', 'images/students/'+data.file);
                        $('#city').empty();
                        $('#locality').empty();
                        // Preview image upload
                        $('#file').change(function() {
                            const file = this.files[0];
                            if (file) {
                                let reader = new FileReader();
                                reader.onload = function(event) {
                                    $('.holder img').attr('src', event.target.result);
                                }
                                reader.readAsDataURL(file);
                            }
                        });

                        $('#maritalStatus').select2().val(data.marital_status).trigger('change');
                        $('#sex').select2().val(data.sex).trigger('change');
                        $('#state').val(data.state).trigger('change.select2');

                        // Insertamos valores preseleccionados al INPUT Departamento , Ciudad y Barrio
                        selectCity($('#city'), data.city);

                        function selectCity(control, value) {
                            var data = {
                                id: value,
                                text: value
                            };

                            var initOption = new Option(data.text, data.id, false, false);
                            control.select2().append(initOption).trigger('change.select2');
                            control.val(value).trigger('change.select2');
                        }

                        selectLocality($('#locality'), data.locality);

                        function selectLocality(control, value) {
                            var data = {
                                id: value,
                                text: value
                            };

                            var initOption = new Option(data.text, data.id, false, false);
                            control.select2().append(initOption).trigger('change.select2');;
                            control.val(value).trigger('change.select2');
                        }

                        function initSelect2WithinRelationship(control, value) {
                            var data = {
                                id: value,
                                text: value
                            };

                            var initOption = new Option(data.text, data.text, false, false);
                            control.select2().append(initOption).trigger('change.select2');;
                            control.val(value).trigger('change.select2');

                            control.trigger({
                                type: 'select2:select',
                                params: {
                                    data: data
                                }
                            });
                        }

                        $('#state').on('change', onSelectStateChange);
                        // Funcion que despliega Ciudades de acuerdo a un Departamento
                        function onSelectStateChange() {
                            var state = $(this).val();
                            console.log('Estado:', state)

                            //Ajax
                            $.get('/admin/locality/' + state + '/cities', function(res) {
                                var html_select = '<option value="">Seleccione Ciudad</option>'
                                for (var i = 0; i < res.length; ++i)
                                    html_select += '<option value="' + res[i].desc_dist + '">' +
                                    res[i].desc_dist + '</option>';
                                $('#city').html(html_select).trigger('change.select2');
                                $('#locality').change()
                            });
                        }

                        $('#city').on('change', onSelectCityChange);
                        // Funcion que despliega Barrios de acuerdo a una Ciudad
                        function onSelectCityChange() {
                            var city = $(this).val();
                            console.log('Ciudad:', city)
                            //Ajax
                            $.get('/admin/locality/' + city + '/localities', function(data) {
                                var html_select = '<option value="">Seleccione Barrio</option>'
                                for (var i = 0; i < data.length; ++i)
                                    html_select += '<option value="' + data[i].desc_barrio_loc +
                                    '">' + data[i].desc_barrio_loc + '</option>';
                                $('#locality').html(html_select).trigger('change.select2');
                            });
                        }

                        // Carga de valores iniciales de Sucursales 
                        var branch = $('#branchs').select2();
                        var urlBranch = '/admin/get-branches/' + data.branch.id

                        initSelectWithRelationship(urlBranch, branch)

                        function initSelectWithRelationship(url, control) {
                            $.ajax({
                                type: 'GET',
                                url: url
                            }).then(function(data) {
                                // create the option and append to Select2
                                var option = new Option(data.name, data.id, true, true);

                                control.append(option).trigger('change');

                                // manually trigger the `select2:select` event
                                control.trigger({
                                    type: 'select2:select',
                                    params: {
                                        data: data
                                    }
                                });
                            });
                        }

                        $('#branchs').select2({
                            placeholder: 'Selecciones',
                            ajax: {
                                url: '{{ route('globales.patrimonies.index') }}',
                                dataType: 'json',
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                text: item.name,
                                                id: item.id
                                            }
                                        })
                                    };
                                },
                                cache: true
                            }
                        });
                    });
                });


                $('#saveBtn').click(function(e) {
                    e.preventDefault();
                    $(this).html('Enviando..');

                    var data = new FormData();

                    //Form data
                    var form_data = $('#studentForm').serializeArray();
                    $.each(form_data, function(key, input) {
                        data.append(input.name, input.value);
                    });

                    //File data
                    var file_data = $('input[name="file"]')[0].files;
                    for (var i = 0; i < file_data.length; i++) {
                        data.append("file", file_data[i]);
                    }

                    // Custom data
                    data.append('key', 'value');

                    $.ajax({
                        data: data,
                        url: "{{ route('globales.patrimonies.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data) {
                                $(".success").text(data.success).addClass('alert alert-success');
                                setTimeout(function() {
                                    $(".success").hide().html('');
                                }, 5000);
                            }
                            $("#previewImg").html(data).fadeIn();
                            $("#studentForm")[0].reset();
                            $('#studentForm').trigger("reset");
                            $('#modalStudent').modal('hide');
                            $(".success").removeAttr("style");
                            table.draw();
                        },

                        error: function(data) {
                            var obj = data.responseJSON.errors;
                            $.each(obj, function(key, value) {
                                toastr.options = {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                                toastr.info('Atención: ' + value);
                            });
                            $('#saveBtn').html('Guardar Cambios');
                        }

                    });
                });


                $('body').on('click', '.deleteStudent', function() {
                    Swal.fire({
                        title: 'Estás seguro de eliminarlo?',
                        text: "Si lo haces, no podras revertirlo!",
                        type: 'warning',
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
                            var student_id = $(this).data("id");
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('globales.patrimonies.store') }}" + '/' + student_id,
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
