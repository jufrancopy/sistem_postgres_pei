@extends('layouts.master')
@section('title', 'SIESS — Gestión de Extractos')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-database mr-2"></i>Extractos Estadísticos</h4>
        <p class="card-category">Gestión del flujo de validación — Res. 266/2022</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Extractos</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Filtros ── --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="filtroModulo" class="form-control form-control-sm">
                    <option value="">Todos los módulos</option>
                    @foreach($modulos as $m)
                        <option value="{{ $m->id }}">{{ $m->codigo }} — {{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filtroEstado" class="form-control form-control-sm">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filtroPeriodo" class="form-control form-control-sm">
                    <option value="">Todos los períodos</option>
                    @foreach($periodos as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-right">
                <button class="btn btn-success btn-sm" id="btnNuevoExtracto">
                    <i class="fa fa-plus mr-1"></i> Nuevo Extracto
                </button>
            </div>
        </div>

        {{-- ── DataTable ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover data-table" id="extractosTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Módulo</th>
                        <th>Indicador</th>
                        <th>Período</th>
                        <th>Estado</th>
                        <th>Días restantes</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Modal Extracto ── --}}
<div class="modal fade" id="modalExtracto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalExtractoTitulo">Nuevo Extracto</h4>
                <small class="text-white opacity-75">Carga manual o registro de extracción automática</small>
            </div>
            <div class="modal-body">
                <form id="formExtracto">
                    {{ csrf_field() }}
                    <input type="hidden" id="extracto_id" name="extracto_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Módulo <span class="text-danger">*</span></label>
                                <select name="modulo_id" id="modulo_id" class="form-control" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($modulos as $m)
                                        <option value="{{ $m->id }}">{{ $m->codigo }} — {{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Indicador <span class="text-danger">*</span></label>
                                <select name="indicador_id" id="indicador_id" class="form-control" required>
                                    <option value="">Primero seleccione un módulo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Período <span class="text-danger">*</span></label>
                                <select name="periodo_id" id="periodo_id" class="form-control" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($periodos as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dirección Responsable</label>
                                {{-- Paso 1: elegir organigrama raíz --}}
                                <select id="organigrama_raiz" class="form-control mb-3" style="width:100%">
                                    <option value="">1. Elegir organigrama raíz...</option>
                                </select>
                                {{-- Paso 2: elegir dependencia hija --}}
                                <label class="text-muted" style="font-size:.85rem">Dependencia responsable:</label>
                                <select name="direccion_id" id="direccion_id" class="form-control" style="width:100%" disabled>
                                    <option value="">2. Elegir dependencia...</option>
                                </select>
                                <small class="text-muted">Primero elegí el organigrama raíz, luego la dependencia responsable.</small>
                            </div>

                            {{-- Establecimiento territorial (aparece según el módulo) --}}
                            <div class="form-group" id="campoEstablecimiento" style="display:none">
                                <label class="font-weight-bold text-info">
                                    <i class="fa fa-hospital mr-1"></i>
                                    Establecimiento / Punto de atención
                                </label>
                                <small class="d-block text-muted mb-1" id="textoEstablecimiento">
                                    Seleccioná el establecimiento al que corresponden estos datos.
                                </small>
                                <select name="organigrama_id" id="organigrama_id" class="form-control" style="width:100%">
                                    <option value="">Sin establecimiento específico</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Resumen / Descripción ejecutiva</label>
                        <textarea name="resumen" id="resumen" class="form-control" rows="3"
                            placeholder="Descripción del extracto para informes gerenciales..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Datos (JSON — opcional para carga estructurada)</label>
                        <textarea name="datos" id="datos" class="form-control font-monospace" rows="4"
                            placeholder='{"total": 0, "detalle": []}'></textarea>
                        <small class="text-muted">Formato libre. Se usará para integración con Tableau/BI.</small>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="btnGuardarExtracto">
                            <i class="fa fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal Objetar ── --}}
<div class="modal fade" id="modalObjetar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-danger">
                <h4 class="modal-title">Objetar Extracto</h4>
            </div>
            <div class="modal-body">
                <form id="formObjetar">
                    <input type="hidden" id="objetar_id">
                    <div class="form-group">
                        <label>Motivo de la objeción <span class="text-danger">*</span></label>
                        <textarea id="observacion_objecion" class="form-control" rows="4" required
                            placeholder="Describa el motivo de la objeción (mínimo 10 caracteres)..."></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times mr-1"></i> Confirmar Objeción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── DataTable ─────────────────────────────────────────────────────────────
    var table = $('#extractosTable').DataTable({
        processing: true,
        serverSide: true,
        language: {
            emptyTable: 'No hay extractos registrados',
            processing: 'Procesando...',
            search: 'Buscar:',
            zeroRecords: 'Sin resultados',
        },
        ajax: {
            url: "{{ route('siess.extractos.index') }}",
            data: function(d) {
                d.modulo_id  = $('#filtroModulo').val();
                d.estado     = $('#filtroEstado').val();
                d.periodo_id = $('#filtroPeriodo').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',      name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'modulo_nombre',    name: 'modulo_nombre' },
            { data: 'indicador_codigo', name: 'indicador_codigo' },
            { data: 'periodo_nombre',   name: 'periodo_nombre' },
            { data: 'estado_badge',     name: 'estado' },
            { data: 'dias_restantes',   name: 'dias_restantes', orderable: false },
            { data: 'action',           name: 'action', orderable: false, searchable: false },
        ]
    });

    // Refrescar al cambiar filtros
    $('#filtroModulo, #filtroEstado, #filtroPeriodo').on('change', function() { table.draw(); });

    // ── Carga dinámica de indicadores por módulo ──────────────────────────────
    $('#modulo_id').on('change', function() {
        var moduloId = $(this).val();
        var moduloCodigo = $(this).find('option:selected').text().split(' — ')[0].trim();
        $('#indicador_id').html('<option value="">Cargando...</option>');
        if (!moduloId) { $('#indicador_id').html('<option value="">Primero seleccione un módulo</option>'); return; }

        $.get("{{ url('siess/modulos') }}/" + moduloId + "/indicadores", function(data) {
            var opts = '<option value="">Seleccionar indicador...</option>';
            data.forEach(function(ind) {
                opts += '<option value="' + ind.id + '">[' + ind.codigo + '] ' + ind.nombre + ' (' + ind.unidad + ')</option>';
            });
            $('#indicador_id').html(opts);
        });

        // Mostrar selector de establecimiento según el módulo
        var modulosConEstablecimiento = {
            'AOP': { texto: 'Establecimiento con AOP (Aporte Obrero Patronal)', soloAop: true },
            'RL':  { texto: 'Hospital/Centro que emitió el certificado médico', soloAop: false },
            'RH':  { texto: 'Establecimiento donde trabaja el funcionario', soloAop: false },
            'GA':  { texto: 'Establecimiento al que corresponde el gasto', soloAop: false },
            'CAU': { texto: 'Centro de atención al usuario', soloAop: false },
        };

        if (modulosConEstablecimiento[moduloCodigo]) {
            var config = modulosConEstablecimiento[moduloCodigo];
            $('#textoEstablecimiento').text(config.texto);
            $('#campoEstablecimiento').show();

            // Inicializar select2 con filtro según módulo
            $('#organigrama_id').select2({
                dropdownParent: $('#modalExtracto'),
                placeholder: 'Buscar establecimiento...',
                allowClear: true,
                ajax: {
                    url: '{{ route('siess.establecimientos') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term, solo_aop: config.soloAop ? 1 : 0 };
                    },
                    processResults: function(data) {
                        return { results: data };
                    },
                    cache: true
                }
            });
        } else {
            $('#campoEstablecimiento').hide();
            $('#organigrama_id').val(null).trigger('change');
        }
    });

    // ── Selector de dirección responsable (2 pasos) ──────────────────────────
    // Paso 1: Organigrama raíz
    $('#organigrama_raiz').select2({
        dropdownParent: $('#modalExtracto'),
        placeholder: '1. Elegir organigrama raíz...',
        allowClear: true,
        ajax: {
            url: '{{ route('globales.get-dependencies-root') }}',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return { text: item.dependency, id: item.id };
                    })
                };
            },
            cache: true
        }
    });

    // Paso 2: Al elegir raíz, cargar sus dependencias hijas
    $('#organigrama_raiz').on('change', function() {
        var raizId = $(this).val();
        var $dir = $('#direccion_id');

        $dir.empty().append('<option value="">2. Elegir dependencia...</option>');
        $dir.prop('disabled', true);

        if (!raizId) return;

        $dir.prop('disabled', false);
        $dir.select2({
            dropdownParent: $('#modalExtracto'),
            placeholder: '2. Buscar dependencia...',
            allowClear: true,
            ajax: {
                url: '{{ url('admin/globales/get-dependencies') }}/' + raizId,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // Incluir también la raíz como opción
                    var results = [{ id: raizId, text: $('#organigrama_raiz option:selected').text() + ' (raíz)' }];
                    if (Array.isArray(data)) {
                        data.forEach(function(item) {
                            results.push({ text: item.dependency, id: item.id });
                        });
                    }
                    return { results: results };
                },
                cache: true
            }
        });
    });

    // ── Nuevo extracto ────────────────────────────────────────────────────────
    $('#btnNuevoExtracto').on('click', function() {
        $('#modalExtractoTitulo').text('Nuevo Extracto');
        $('#formExtracto')[0].reset();
        $('#extracto_id').val('');
        $('#indicador_id').html('<option value="">Primero seleccione un módulo</option>');
        // Resetear selector de dirección
        $('#organigrama_raiz').val(null).trigger('change');
        $('#direccion_id').empty().append('<option value="">2. Elegir dependencia...</option>').prop('disabled', true);
        $('#modalExtracto').modal('show');
    });

    // ── Guardar extracto ──────────────────────────────────────────────────────
    $('#formExtracto').on('submit', function(e) {
        e.preventDefault();
        $('#btnGuardarExtracto').html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        $.ajax({
            url:  "{{ route('siess.extractos.store') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                toastr.success(res.success);
                $('#modalExtracto').modal('hide');
                table.draw();
            },
            error: function(xhr) {
                var resp = xhr.responseJSON;
                if (resp && resp.errors) {
                    $.each(resp.errors, function(k, v) { toastr.error(v[0]); });
                } else if (resp && resp.message) {
                    toastr.error(resp.message);
                } else {
                    toastr.error('Error ' + xhr.status + '. Revisá la consola.');
                    console.error('SIESS store error:', xhr.responseText);
                }
            },
            complete: function() {
                $('#btnGuardarExtracto').html('<i class="fa fa-save mr-1"></i> Guardar').prop('disabled', false);
            }
        });
    });

    // ── Editar extracto ───────────────────────────────────────────────────────
    $('body').on('click', '.editExtracto', function() {
        var id = $(this).data('id');
        $.get("{{ url('siess/extractos') }}/" + id + "/edit", function(res) {
            var e = res.extracto;
            $('#modalExtractoTitulo').text('Extracto #' + e.id + ' — ' + e.indicador.codigo);
            $('#extracto_id').val(e.id);
            $('#modulo_id').val(e.modulo_id).trigger('change');
            setTimeout(function() { $('#indicador_id').val(e.indicador_id); }, 500);
            $('#periodo_id').val(e.periodo_id);
            $('#resumen').val(e.resumen);
            $('#datos').val(e.datos ? JSON.stringify(e.datos, null, 2) : '');

            // Precargar dirección responsable
            if (e.direccion_id && e.direccion) {
                // Buscar la raíz del organigrama de esta dirección
                $.get('{{ url('admin/globales/get-root-of-dependency') }}/' + e.direccion_id, function(raiz) {

                    // 1. Precargar el select de raíz
                    var optRaiz = new Option(raiz.dependency, raiz.id, true, true);
                    $('#organigrama_raiz').empty().append(optRaiz).trigger('change');

                    // 2. Habilitar el select de dependencia y precargarlo
                    $('#direccion_id').prop('disabled', false);

                    // Inicializar select2 en el selector de dependencia
                    $('#direccion_id').select2({
                        dropdownParent: $('#modalExtracto'),
                        placeholder: '2. Buscar dependencia...',
                        allowClear: true,
                        ajax: {
                            url: '{{ url('admin/globales/get-dependencies') }}/' + raiz.id,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                var results = [{ id: raiz.id, text: raiz.dependency + ' (raíz)' }];
                                if (Array.isArray(data)) {
                                    data.forEach(function(item) {
                                        results.push({ text: item.dependency, id: item.id });
                                    });
                                }
                                return { results: results };
                            },
                            cache: true
                        }
                    });

                    // Precargar el valor seleccionado
                    var optDir = new Option(e.direccion.dependency, e.direccion_id, true, true);
                    $('#direccion_id').empty().append(optDir).trigger('change');
                });
            } else {
                $('#organigrama_raiz').val(null).trigger('change');
                $('#direccion_id').empty().append('<option value="">2. Elegir dependencia...</option>').prop('disabled', true);
            }

            $('#modalExtracto').modal('show');
        });
    });

    // ── Enviar a validación ───────────────────────────────────────────────────
    $('body').on('click', '.enviarValidacion', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Enviar a validación?',
            text: 'La dirección responsable tendrá 5 días hábiles para aprobar u objetar (Art. 8 Res. 266/2022).',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post("{{ url('siess/extractos') }}/" + id + "/enviar-validacion", {}, function(res) {
                    toastr.success(res.success + ' Fecha límite: ' + res.fecha_limite);
                    table.draw();
                }).fail(function(xhr) { toastr.error(xhr.responseJSON?.error || 'Error'); });
            }
        });
    });

    // ── Aprobar ───────────────────────────────────────────────────────────────
    $('body').on('click', '.aprobarExtracto', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Aprobar extracto?',
            text: 'El dato quedará disponible para reportes gerenciales y el Observatorio.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Aprobar',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post("{{ url('siess/extractos') }}/" + id + "/aprobar", {}, function(res) {
                    toastr.success(res.success);
                    table.draw();
                }).fail(function(xhr) { toastr.error(xhr.responseJSON?.error || 'Error'); });
            }
        });
    });

    // ── Objetar ───────────────────────────────────────────────────────────────
    $('body').on('click', '.objetarExtracto', function() {
        $('#objetar_id').val($(this).data('id'));
        $('#observacion_objecion').val('');
        $('#modalObjetar').modal('show');
    });

    $('#formObjetar').on('submit', function(e) {
        e.preventDefault();
        var id = $('#objetar_id').val();
        $.post("{{ url('siess/extractos') }}/" + id + "/objetar", {
            observacion: $('#observacion_objecion').val()
        }, function(res) {
            toastr.warning(res.success);
            $('#modalObjetar').modal('hide');
            table.draw();
        }).fail(function(xhr) { toastr.error(xhr.responseJSON?.error || 'Error'); });
    });

    // ── Marcar Fuente Única (Art. 6) ──────────────────────────────────────────
    $('body').on('click', '.marcarFuenteUnica', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Marcar como Fuente Única',
            text: 'Este dato quedará registrado como única fuente de consulta (Art. 6 Res. 266/2022). ¿Confirmar?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            confirmButtonColor: '#6c757d',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post("{{ url('siess/extractos') }}/" + id + "/fuente-unica", {}, function(res) {
                    toastr.info(res.success);
                    table.draw();
                }).fail(function(xhr) { toastr.error(xhr.responseJSON?.error || 'Error'); });
            }
        });
    });

    // ── Eliminar ──────────────────────────────────────────────────────────────
    $('body').on('click', '.deleteExtracto', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar extracto?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('siess/extractos') }}/" + id,
                    type: 'DELETE',
                    success: function(res) { toastr.success(res.success); table.draw(); },
                    error: function(xhr) { toastr.error(xhr.responseJSON?.error || 'Error'); }
                });
            }
        });
    });
});
</script>
@stop
