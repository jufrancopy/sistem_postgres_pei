@extends('layouts.master')
@section('title', 'Marco Estratégico Específico — ' . strip_tags($profile->name))

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex align-items-center">
        <div>
            <h4 class="card-title mb-0">
                <i class="fa fa-balance-scale mr-2"></i>Marco Estratégico Específico
            </h4>
            <small class="text-white" style="opacity:.8">{{ strip_tags($profile->name) }}</small>
        </div>
        <div class="ml-auto d-flex" style="gap:.5rem">
            <a href="{{ route('pei-profiles.show', $profile->id) }}" class="btn btn-sm btn-light">
                <i class="fa fa-arrow-left mr-1"></i> Volver al PEI
            </a>
        </div>
    </div>

    <div class="card-body p-0">

        {{-- ══ SECCIÓN A: Marco Legal ══════════════════════════════════════════ --}}
        <div class="px-4 pt-4 pb-2">
            <div class="d-flex align-items-center mb-3">
                <div>
                    <h5 class="mb-0 font-weight-bold" style="color:#1a237e">
                        <i class="fa fa-gavel mr-2"></i>Marco Legal
                    </h5>
                    <small class="text-muted">Instrumentos normativos, competencias y responsables</small>
                </div>
                <button type="button" class="btn btn-sm btn-success ml-auto" id="btnNuevoMarcoLegal">
                    <i class="fa fa-plus mr-1"></i> Agregar Marco
                </button>
            </div>

            @if($marcos->isEmpty())
            <div class="text-center py-4 text-muted" id="emptyMarcos">
                <i class="fa fa-gavel fa-2x mb-2 d-block" style="opacity:.3"></i>
                <small>Sin marcos legales registrados. Usá el botón <strong>Agregar Marco</strong>.</small>
            </div>
            @endif

            <div class="table-responsive" id="tablaMarcoLegalWrap" {{ $marcos->isEmpty() ? 'style=display:none' : '' }}>
                <table class="table table-hover table-sm mb-0" id="tablaMarcoLegal">
                    <thead style="background:#e8eaf6">
                        <tr>
                            <th style="width:220px">Marco Legal</th>
                            <th>Competencias</th>
                            <th style="width:220px">Responsable(s)</th>
                            <th style="width:90px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($marcos as $m)
                        <tr id="meeml-row-{{ $m->id }}">
                            <td class="font-weight-bold" style="font-size:.85rem;vertical-align:top;padding-top:.75rem">
                                {{ $m->marco_legal }}
                            </td>
                            <td style="font-size:.82rem;vertical-align:top">
                                {!! nl2br(e($m->competencias)) !!}
                            </td>
                            <td style="vertical-align:top;padding-top:.6rem">
                                @foreach($m->responsables as $r)
                                <span class="badge badge-secondary mr-1 mb-1" style="font-size:.68rem">{{ $r->dependency }}</span>
                                @endforeach
                            </td>
                            <td class="text-center" style="vertical-align:top;padding-top:.6rem;white-space:nowrap">
                                <button class="btn btn-sm btn-outline-primary py-0 px-2 btnEditarMarco"
                                        data-id="{{ $m->id }}" title="Editar">
                                    <i class="fa fa-edit" style="font-size:.72rem"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger py-0 px-2 btnEliminarMarco"
                                        data-id="{{ $m->id }}" data-nombre="{{ $m->marco_legal }}" title="Eliminar">
                                    <i class="fa fa-trash" style="font-size:.72rem"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="mx-4">

        {{-- ══ SECCIÓN B: Oferta de Servicios ══════════════════════════════════ --}}
        <div class="px-4 pb-4 pt-2">
            <div class="d-flex align-items-center mb-3">
                <div>
                    <h5 class="mb-0 font-weight-bold" style="color:#1b5e20">
                        <i class="fa fa-concierge-bell mr-2"></i>Oferta de Servicios
                    </h5>
                    <small class="text-muted">Bienes, servicios institucionales y población beneficiaria</small>
                </div>
                <button type="button" class="btn btn-sm btn-success ml-auto" id="btnNuevaOferta">
                    <i class="fa fa-plus mr-1"></i> Agregar Servicio
                </button>
            </div>

            @if($ofertas->isEmpty())
            <div class="text-center py-4 text-muted" id="emptyOfertas">
                <i class="fa fa-concierge-bell fa-2x mb-2 d-block" style="opacity:.3"></i>
                <small>Sin servicios registrados. Usá el botón <strong>Agregar Servicio</strong>.</small>
            </div>
            @endif

            <div class="table-responsive" id="tablaOfertasWrap" {{ $ofertas->isEmpty() ? 'style=display:none' : '' }}>
                <table class="table table-hover table-sm mb-0" id="tablaOfertas">
                    <thead style="background:#e8f5e9">
                        <tr>
                            <th style="width:220px">Acción / Servicio</th>
                            <th>Descripción General</th>
                            <th style="width:220px">Beneficiarios Actuales</th>
                            <th style="width:90px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ofertas as $o)
                        <tr id="meeofs-row-{{ $o->id }}">
                            <td class="font-weight-bold" style="font-size:.85rem;vertical-align:top;padding-top:.75rem">
                                {{ $o->accion }}
                            </td>
                            <td style="font-size:.82rem;vertical-align:top">
                                {!! nl2br(e($o->descripcion)) !!}
                            </td>
                            <td style="font-size:.82rem;vertical-align:top">
                                {{ $o->beneficiarios }}
                            </td>
                            <td class="text-center" style="vertical-align:top;padding-top:.6rem;white-space:nowrap">
                                <button class="btn btn-sm btn-outline-primary py-0 px-2 btnEditarOferta"
                                        data-id="{{ $o->id }}" title="Editar">
                                    <i class="fa fa-edit" style="font-size:.72rem"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger py-0 px-2 btnEliminarOferta"
                                        data-id="{{ $o->id }}" data-nombre="{{ $o->accion }}" title="Eliminar">
                                    <i class="fa fa-trash" style="font-size:.72rem"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ══ Modal Marco Legal ═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalMarcoLegal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white mb-0" id="modalMarcoLegalTitulo">
                    <i class="fa fa-gavel mr-2"></i>Marco Legal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ml_id">
                <div class="form-group">
                    <label class="font-weight-bold">Marco Legal <span class="text-danger">*</span></label>
                    <input type="text" id="ml_marco_legal" class="form-control"
                           placeholder="Ej: Carta Orgánica del IPS, Código de Ética...">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Competencias</label>
                    <small class="form-text text-muted mb-1">
                        Describí las competencias que otorga este instrumento normativo.
                    </small>
                    <textarea id="ml_competencias" class="form-control" rows="5"
                              placeholder="• Competencia 1&#10;• Competencia 2&#10;• Competencia 3"></textarea>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Responsable(s)</label>
                    <small class="form-text text-muted mb-1">
                        Buscá y seleccioná las dependencias responsables.
                    </small>
                    <select id="ml_responsables" style="width:100%" multiple></select>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnGuardarMarcoLegal">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ Modal Oferta de Servicios ════════════════════════════════════════════ --}}
<div class="modal fade" id="modalOferta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#1b5e20,#2e7d32)">
                <h5 class="modal-title text-white mb-0" id="modalOfertaTitulo">
                    <i class="fa fa-concierge-bell mr-2"></i>Oferta de Servicios
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ofs_id">
                <div class="form-group">
                    <label class="font-weight-bold">Acción / Servicio <span class="text-danger">*</span></label>
                    <input type="text" id="ofs_accion" class="form-control"
                           placeholder="Ej: Prestaciones de salud, Prestaciones económicas...">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Descripción General</label>
                    <textarea id="ofs_descripcion" class="form-control" rows="4"
                              placeholder="Descripción del bien o servicio institucional..."></textarea>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Beneficiarios Actuales</label>
                    <textarea id="ofs_beneficiarios" class="form-control" rows="2"
                              placeholder="Ej: Asegurados activos, jubilados, pensionados y derechohabientes..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnGuardarOferta">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var _profileId = '{{ $profile->id }}';
    var _orgRaizId = '{{ $orgRaizId }}';
    var _baseUrl   = '{{ url("pei-profiles") }}/' + _profileId + '/mee';
    var _mlData    = @json($marcosJson);
    var _ofsData   = @json($ofertas);

    // ── Select2 Responsables ────────────────────────────────────────────────
    function initSelect2Responsables(preloaded) {
        var $sel = $('#ml_responsables');
        if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
        $sel.empty();

        if (preloaded && preloaded.length) {
            preloaded.forEach(function(r) {
                $sel.append(new Option(r.text, r.id, true, true));
            });
        }

        $sel.select2({
            dropdownParent: $('#modalMarcoLegal'),
            placeholder: 'Buscar dependencia...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ url("admin/globales/get-dependencies") }}/' + _orgRaizId,
                dataType: 'json', delay: 300,
                data: function(p) { return { q: p.term || '' }; },
                processResults: function(res) {
                    return { results: $.map(res, function(i) { return { id: i.id, text: i.dependency }; }) };
                },
                cache: true
            }
        });
    }

    // ══ MARCO LEGAL ═══════════════════════════════════════════════════════════

    $('#btnNuevoMarcoLegal').on('click', function() {
        $('#ml_id').val('');
        $('#ml_marco_legal,#ml_competencias').val('');
        $('#modalMarcoLegalTitulo').html('<i class="fa fa-gavel mr-2"></i>Nuevo Marco Legal');
        initSelect2Responsables([]);
        $('#modalMarcoLegal').modal('show');
    });

    $(document).on('click', '.btnEditarMarco', function() {
        var id  = $(this).data('id');
        var rec = _mlData.find(function(r) { return r.id == id; });
        if (!rec) return;
        $('#ml_id').val(rec.id);
        $('#ml_marco_legal').val(rec.marco_legal);
        $('#ml_competencias').val(rec.competencias);
        $('#modalMarcoLegalTitulo').html('<i class="fa fa-edit mr-2"></i>Editar Marco Legal');
        initSelect2Responsables(rec.responsables);
        $('#modalMarcoLegal').modal('show');
    });

    $('#btnGuardarMarcoLegal').on('click', function() {
        var id          = $('#ml_id').val();
        var marcoLegal  = $.trim($('#ml_marco_legal').val());
        if (!marcoLegal) { toastr.warning('El campo Marco Legal es obligatorio.'); return; }

        var responsables = $('#ml_responsables').val() || [];
        var payload = {
            marco_legal:  marcoLegal,
            competencias: $.trim($('#ml_competencias').val()),
        };
        responsables.forEach(function(r, i) { payload['responsables[' + i + ']'] = r; });

        $.ajax({
            url:  id ? _baseUrl + '/marcos/' + id : _baseUrl + '/marcos',
            type: id ? 'PUT' : 'POST',
            data: payload,
            success: function() {
                toastr.success(id ? 'Marco actualizado.' : 'Marco creado.');
                $('#modalMarcoLegal').modal('hide');
                setTimeout(() => location.reload(), 700);
            },
            error: function(xhr) {
                var e = xhr.responseJSON?.errors;
                if (e) $.each(e, (k,v) => toastr.error(v[0]));
                else toastr.error('Error al guardar.');
            }
        });
    });

    $(document).on('click', '.btnEliminarMarco', function() {
        var id = $(this).data('id'), nombre = $(this).data('nombre');
        Swal.fire({ title: '¿Eliminar marco?', html: '<strong>' + nombre + '</strong>',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({ url: _baseUrl + '/marcos/' + id, type: 'DELETE',
                success: function() {
                    $('#meeml-row-' + id).fadeOut(300, function() { $(this).remove(); });
                    _mlData = _mlData.filter(function(m) { return m.id != id; });
                    toastr.success('Marco eliminado.');
                }
            });
        });
    });

    // ══ OFERTA DE SERVICIOS ═══════════════════════════════════════════════════

    $('#btnNuevaOferta').on('click', function() {
        $('#ofs_id').val('');
        $('#ofs_accion,#ofs_descripcion,#ofs_beneficiarios').val('');
        $('#modalOfertaTitulo').html('<i class="fa fa-concierge-bell mr-2"></i>Nuevo Servicio');
        $('#modalOferta').modal('show');
    });

    $(document).on('click', '.btnEditarOferta', function() {
        var id  = $(this).data('id');
        var rec = _ofsData.find(function(r) { return r.id == id; });
        if (!rec) return;
        $('#ofs_id').val(rec.id);
        $('#ofs_accion').val(rec.accion);
        $('#ofs_descripcion').val(rec.descripcion);
        $('#ofs_beneficiarios').val(rec.beneficiarios);
        $('#modalOfertaTitulo').html('<i class="fa fa-edit mr-2"></i>Editar Servicio');
        $('#modalOferta').modal('show');
    });

    $('#btnGuardarOferta').on('click', function() {
        var id     = $('#ofs_id').val();
        var accion = $.trim($('#ofs_accion').val());
        if (!accion) { toastr.warning('El campo Acción / Servicio es obligatorio.'); return; }

        $.ajax({
            url:  id ? _baseUrl + '/ofertas/' + id : _baseUrl + '/ofertas',
            type: id ? 'PUT' : 'POST',
            data: {
                accion:        accion,
                descripcion:   $.trim($('#ofs_descripcion').val()),
                beneficiarios: $.trim($('#ofs_beneficiarios').val()),
            },
            success: function() {
                toastr.success(id ? 'Servicio actualizado.' : 'Servicio creado.');
                $('#modalOferta').modal('hide');
                setTimeout(() => location.reload(), 700);
            },
            error: function(xhr) {
                var e = xhr.responseJSON?.errors;
                if (e) $.each(e, (k,v) => toastr.error(v[0]));
                else toastr.error('Error al guardar.');
            }
        });
    });

    $(document).on('click', '.btnEliminarOferta', function() {
        var id = $(this).data('id'), nombre = $(this).data('nombre');
        Swal.fire({ title: '¿Eliminar servicio?', html: '<strong>' + nombre + '</strong>',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({ url: _baseUrl + '/ofertas/' + id, type: 'DELETE',
                success: function() {
                    $('#meeofs-row-' + id).fadeOut(300, function() { $(this).remove(); });
                    _ofsData = _ofsData.filter(function(o) { return o.id != id; });
                    toastr.success('Servicio eliminado.');
                }
            });
        });
    });
});
</script>
@stop
