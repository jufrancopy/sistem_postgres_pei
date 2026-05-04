@extends('layouts.master')
@section('title', 'Organigrama — ' . $dependencie->first()->dependency)

@section('css')
<link rel="stylesheet" href="{{ asset('assets/orgchart/dist/css/jquery.orgchart.min.css') }}">
<style>
/* ── Contenedor ── */
#chart-container {
    background: linear-gradient(135deg, #f0f4f8 0%, #e8edf2 100%);
    border-radius: 12px;
    min-height: 500px;
    overflow: auto;
    padding: 30px 20px;
    position: relative;
}

/* ── Nodos base ── */
.orgchart .node {
    width: 160px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
    transition: transform .2s, box-shadow .2s;
    border: none !important;
}
.orgchart .node:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,.18);
    z-index: 10;
}

/* ── Títulos por nivel ── */
.orgchart .node .title {
    border-radius: 10px 10px 0 0;
    font-size: .72rem;
    font-weight: 700;
    padding: 8px 6px;
    letter-spacing: .02em;
    text-transform: uppercase;
    line-height: 1.3;
    /* Truncar nombres largos */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    display: block;
    cursor: default;
}
/* Tooltip nativo al hover sobre el título */
.orgchart .node .title[title]:hover::after {
    content: attr(title);
    position: absolute;
    background: rgba(0,0,0,.85);
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: .7rem;
    white-space: normal;
    max-width: 220px;
    z-index: 999;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    margin-top: 4px;
    pointer-events: none;
    text-transform: none;
    font-weight: normal;
    letter-spacing: 0;
}
.orgchart .node .content {
    border-radius: 0 0 10px 10px;
    font-size: .68rem;
    padding: 5px 6px;
    color: #555;
    background: #fff;
    border: 1px solid rgba(0,0,0,.08);
    border-top: none;
    min-height: 28px;
}

/* ── Colores por nivel ── */
.orgchart .node.nivel-0 .title { background: linear-gradient(135deg, #1a3a5c, #2c5f8a); }
.orgchart .node.nivel-1 .title { background: linear-gradient(135deg, #155724, #28a745); }
.orgchart .node.nivel-2 .title { background: linear-gradient(135deg, #0c5460, #17a2b8); }
.orgchart .node.nivel-3 .title { background: linear-gradient(135deg, #856404, #ffc107); color: #333 !important; }
.orgchart .node.nivel-4 .title { background: linear-gradient(135deg, #721c24, #dc3545); }
.orgchart .node.nivel-5 .title { background: linear-gradient(135deg, #383d41, #6c757d); }

/* ── Líneas de conexión ── */
.orgchart .lines .downLine { background-color: #adb5bd; }
.orgchart .lines .topLine  { border-top: 2px solid #adb5bd; }
.orgchart .lines .rightLine{ border-right: 1px solid #adb5bd; }
.orgchart .lines .leftLine { border-left: 1px solid #adb5bd; }

/* ── Controles de zoom ── */
#zoomControls {
    position: absolute; top: 12px; right: 12px; z-index: 100;
    display: flex; flex-direction: column; gap: 4px;
}
#zoomControls button {
    width: 32px; height: 32px; border-radius: 6px;
    border: 1px solid #dee2e6; background: white;
    font-size: .9rem; cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
    transition: background .15s;
}
#zoomControls button:hover { background: #f8f9fa; }

/* ── Pantalla completa ── */
#chartWrapper:fullscreen,
#chartWrapper:-webkit-full-screen,
#chartWrapper:-moz-full-screen {
    background: #f0f4f8;
    padding: 20px;
    overflow: auto;
}
#chartWrapper.fullscreen-active #chart-container {
    min-height: calc(100vh - 80px);
    height: calc(100vh - 80px);
    border-radius: 0;
}
#chartWrapper:fullscreen #zoomControls {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}
/* Botón salir fullscreen visible */
#chartWrapper:fullscreen::before {
    content: 'Presioná ESC para salir de pantalla completa';
    display: block;
    text-align: center;
    font-size: .75rem;
    color: #6c757d;
    margin-bottom: 8px;
}

/* ── Leyenda ── */
.leyenda-item { display: flex; align-items: center; margin-right: 16px; margin-bottom: 6px; }
.leyenda-color {
    width: 14px; height: 14px; border-radius: 3px;
    margin-right: 6px; flex-shrink: 0;
}

/* ── Datos extra en el nodo (teléfono/correo) ── */
.node-extra {
    background: #f8f9fa;
    border: 1px solid rgba(0,0,0,.08);
    border-top: none;
    border-radius: 0 0 10px 10px;
    padding: 4px 6px;
    font-size: .65rem;
    color: #555;
}
.node-field {
    cursor: pointer;
    padding: 1px 2px;
    border-radius: 3px;
    transition: background .15s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.node-field:hover {
    background: #e9ecef;
}
.node-field:hover::after {
    content: ' ✎';
    color: #adb5bd;
    font-size: .6rem;
}
.inline-edit-input {
    font-size: .65rem !important;
    padding: 2px 4px !important;
    height: auto !important;
    min-width: 120px;
}
/* Ajustar ancho del nodo para mostrar más info */
.orgchart .node { width: 180px; }
.orgchart .node .title { position: relative; }
</style>
@stop

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-sitemap mr-2"></i>{{ $dependencie->first()->dependency }}
        </h4>
        <p class="card-category">Organigrama institucional</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('globales.organigramas.index') }}">Organigramas</a></li>
            <li class="breadcrumb-item active">{{ $dependencie->first()->dependency }}</li>
        </ol>
    </nav>

    <div class="card-body p-0">
        <ul class="nav nav-tabs px-3 pt-3" id="orgTabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tabVisual">
                    <i class="fa fa-project-diagram mr-1"></i> Organigrama Visual
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tabTabla">
                    <i class="fa fa-table mr-1"></i> Vista de Tabla
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tabInfo">
                    <i class="fa fa-info-circle mr-1"></i> Resumen
                </a>
            </li>
        </ul>

        <div class="tab-content p-3">

            {{-- ── Tab 1: Visual ── --}}
            <div class="tab-pane fade show active" id="tabVisual">

                {{-- Toolbar --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    {{-- Leyenda --}}
                    <div class="d-flex flex-wrap align-items-center">
                        @php
                        $leyenda = [
                            ['color'=>'#1a3a5c','label'=>'Institución'],
                            ['color'=>'#28a745','label'=>'Consejo / Presidencia'],
                            ['color'=>'#17a2b8','label'=>'Gerencias'],
                            ['color'=>'#ffc107','label'=>'Direcciones'],
                            ['color'=>'#dc3545','label'=>'Departamentos'],
                        ];
                        @endphp
                        @foreach($leyenda as $l)
                        <div class="leyenda-item">
                            <div class="leyenda-color" style="background:{{ $l['color'] }}"></div>
                            <small class="text-muted">{{ $l['label'] }}</small>
                        </div>
                        @endforeach
                    </div>
                    {{-- Acciones --}}
                    <div class="d-flex">
                        <a href="{{ route('globales.organigrama-gestionar', $dependencie->first()->id) }}"
                           class="btn btn-warning btn-circle mr-1" title="Gestionar estructura">
                            <i class="fa fa-sitemap"></i>
                        </a>
                        <button class="btn btn-secondary btn-circle mr-1" id="btnExportImg" title="Exportar como imagen">
                            <i class="fa fa-image"></i>
                        </button>
                        <button class="btn btn-dark btn-circle" id="btnFullscreen" title="Pantalla completa">
                            <i class="fa fa-expand"></i>
                        </button>
                    </div>
                </div>

                {{-- Organigrama --}}
                <div style="position:relative" id="chartWrapper">
                    <div id="zoomControls">
                        <button id="btnZoomIn"  title="Acercar"><i class="fa fa-plus"></i></button>
                        <button id="btnZoomOut" title="Alejar"><i class="fa fa-minus"></i></button>
                        <button id="btnZoomReset" title="Restablecer"><i class="fa fa-compress"></i></button>
                    </div>
                    <div id="chart-container"></div>
                </div>

                <small class="text-muted d-block mt-2 text-center">
                    <i class="fa fa-mouse-pointer mr-1"></i>
                    Click en un nodo para expandir/colapsar · Arrastrá el fondo para navegar
                </small>

                {{-- Control de nivel vertical --}}
                <div class="d-flex align-items-center justify-content-center mt-3 flex-wrap">
                    <small class="text-muted mr-2">
                        <i class="fa fa-arrows-alt-v mr-1"></i> Apilar verticalmente desde nivel:
                    </small>
                    <div class="btn-group btn-group-sm" id="verticalLevelBtns">
                        @foreach([2,3,4,5,6] as $lvl)
                        <button class="btn btn-outline-secondary vertical-level-btn {{ $lvl === 5 ? 'active' : '' }}"
                                data-level="{{ $lvl }}" title="Vertical desde nivel {{ $lvl }}">
                            N{{ $lvl }}
                        </button>
                        @endforeach
                        <button class="btn btn-outline-secondary vertical-level-btn"
                                data-level="99" title="Todo horizontal">
                            <i class="fa fa-arrows-alt-h"></i> Todo horizontal
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Tab 2: Tabla ── --}}
            <div class="tab-pane fade" id="tabTabla">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tablaOrg">
                        <thead class="thead-light">
                            <tr>
                                <th>Nivel</th>
                                <th>Dependencia</th>
                                <th>Responsable</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dependencie->first()->descendants as $dep)
                            @php
                                $nivel = max(0, ($dep->depth ?? 1) - 1);
                                $colores = ['badge-info','badge-primary','badge-success','badge-warning','badge-secondary'];
                                $color = $colores[min($nivel, count($colores)-1)];
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <span class="badge {{ $color }}">N{{ $nivel + 1 }}</span>
                                </td>
                                <td>
                                    <span style="padding-left:{{ $nivel * 15 }}px">
                                        @if($nivel > 0)<span class="text-muted mr-1">└</span>@endif
                                        {{ $dep->dependency }}
                                    </span>
                                </td>
                                <td><small>{{ $dep->manager ?? '—' }}</small></td>
                                <td><small class="text-muted">{{ $dep->email ?? '—' }}</small></td>
                                <td><small>{{ $dep->phone ?? '—' }}</small></td>
                                <td class="text-center">
                                    <a href="{{ route('globales.organigramas-editar-subdependencia', $dep->id) }}"
                                       class="btn btn-info btn-circle" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('globales.organigramas-crear-subdependencia', $dep->id) }}"
                                       class="btn btn-success btn-circle ml-1" title="Agregar hijo">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Tab 3: Resumen ── --}}
            <div class="tab-pane fade" id="tabInfo">
                @php
                    $root    = $dependencie->first();
                    $todos   = $root->descendants;
                    $conUser = $todos->whereNotNull('user_id')->count();
                    $maxDepth= $todos->max(fn($d) => $d->depth ?? 0);
                @endphp
                <div class="row">
                    <div class="col-md-5">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light"><h6 class="mb-0">Datos del organigrama raíz</h6></div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    <tr><td class="text-muted">Nombre</td><td><strong>{{ $root->dependency }}</strong></td></tr>
                                    <tr><td class="text-muted">Responsable</td><td>{{ $root->manager ?? '—' }}</td></tr>
                                    <tr><td class="text-muted">Correo</td><td>{{ $root->email ?? '—' }}</td></tr>
                                    <tr><td class="text-muted">Teléfono</td><td>{{ $root->phone ?? '—' }}</td></tr>
                                    <tr><td class="text-muted">ID</td><td><span class="badge badge-secondary">{{ $root->id }}</span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="card border-left-info shadow-sm text-center py-3">
                                    <div class="h3 font-weight-bold text-info mb-0">{{ $todos->count() }}</div>
                                    <div class="text-muted" style="font-size:.8rem">Total dependencias</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card border-left-primary shadow-sm text-center py-3">
                                    <div class="h3 font-weight-bold text-primary mb-0">{{ $root->children->count() }}</div>
                                    <div class="text-muted" style="font-size:.8rem">Hijos directos</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card border-left-success shadow-sm text-center py-3">
                                    <div class="h3 font-weight-bold text-success mb-0">{{ $conUser }}</div>
                                    <div class="text-muted" style="font-size:.8rem">Con responsable asignado</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card border-left-warning shadow-sm text-center py-3">
                                    <div class="h3 font-weight-bold text-warning mb-0">{{ $maxDepth }}</div>
                                    <div class="text-muted" style="font-size:.8rem">Niveles de profundidad</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('globales.organigrama-gestionar', $root->id) }}" class="btn btn-warning mr-2">
                                <i class="fa fa-sitemap mr-1"></i> Gestionar
                            </a>
                            <a href="{{ route('globales.organigramas.edit', $root->id) }}" class="btn btn-primary">
                                <i class="fa fa-edit mr-1"></i> Editar raíz
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('assets/orgchart/dist/js/jquery.orgchart.min.js') }}"></script>
<script>
$(function() {

    // ── Datasource generado recursivamente desde PHP ──────────────────────────
    @php
    function buildOrgNode($nodo, $nivel = 0): array {
        return [
            'name'      => addslashes($nodo->dependency),
            'title'     => addslashes($nodo->manager ?? ''),
            'className' => 'nivel-' . min($nivel, 5),
            'id'        => $nodo->id,
            'phone'     => addslashes($nodo->phone ?? ''),
            'email'     => addslashes($nodo->email ?? ''),
            'tipo'      => $nodo->tipo_establecimiento ?? '',
            'nivel'     => $nodo->nivel_complejidad ?? '',
            'tenencia'  => $nodo->tenencia ?? '',
            'aop'       => $nodo->tiene_aop ? true : false,
            'region'    => $nodo->region ?? '',
            'children'  => $nodo->children->map(fn($c) => buildOrgNode($c, $nivel + 1))->values()->toArray(),
        ];
    }
    $datasourcePhp = buildOrgNode($dependencie->first());
    @endphp

    var datasource = @json($datasourcePhp);

    // ── Función única de inicialización del orgchart ─────────────────────────
    var oc;
    var currentLevel = 5;

    function initOrgchart(verticalLevel) {
        $('#chart-container').empty();
        oc = $('#chart-container').orgchart({
            data:               datasource,
            nodeContent:        'title',
            toggleSiblingsResp: true,
            pan:                true,
            zoom:               true,
            zoominLimit:        2,
            zoomoutLimit:       0.3,
            exportFilename:     'organigrama-ips',
            exportFileextension:'png',
            verticalLevel:      verticalLevel >= 99 ? undefined : verticalLevel,
            createNode: function($node, data) {
                // Tooltip para nombres largos
                $node.find('.title').attr('title', data.name);

                // Badge tipo/nivel para establecimientos de salud
                if (data.tipo) {
                    var colorNivel = { 'N1':'#6c757d','N2':'#17a2b8','N3':'#28a745','N4':'#dc3545' };
                    var badges = '<div style="text-align:center;padding:2px 4px;background:#fff3cd;border-bottom:1px solid #ffc107">';
                    badges += '<span style="background:#495057;color:#fff;border-radius:3px;padding:1px 5px;font-size:.6rem;font-weight:700;margin-right:2px">' + data.tipo + '</span>';
                    if (data.nivel) {
                        badges += '<span style="background:' + (colorNivel[data.nivel]||'#6c757d') + ';color:#fff;border-radius:3px;padding:1px 5px;font-size:.6rem;font-weight:700">' + data.nivel + '</span>';
                    }
                    if (data.aop) {
                        badges += '<span style="background:#fd7e14;color:#fff;border-radius:3px;padding:1px 4px;font-size:.55rem;margin-left:2px" title="Tiene AOP">AOP</span>';
                    }
                    badges += '</div>';
                    $node.find('.title').after(badges);
                }

                // Teléfono y correo editables
                var extra = '<div class="node-extra">';
                extra += '<div class="node-field" data-field="phone" data-id="' + (data.id || '') + '" title="Doble click para editar">';
                extra += '<i class="fa fa-phone mr-1" style="font-size:.65rem"></i>';
                extra += '<span class="field-value">' + (data.phone || '<em class="text-muted">Sin teléfono</em>') + '</span>';
                extra += '</div>';
                extra += '<div class="node-field" data-field="email" data-id="' + (data.id || '') + '" title="Doble click para editar">';
                extra += '<i class="fa fa-envelope mr-1" style="font-size:.65rem"></i>';
                extra += '<span class="field-value">' + (data.email || '<em class="text-muted">Sin correo</em>') + '</span>';
                extra += '</div>';
                extra += '</div>';
                $node.find('.content').after(extra);
            }
        });
        zoomLevel = 1;
        $('#chart-container .orgchart').css('transform', 'scale(1)');
    }

    // Inicialización con nivel por defecto
    initOrgchart(currentLevel);

    // ── Pantalla completa ─────────────────────────────────────────────────────
    var $chartWrap = $('#chartWrapper');

    $('#btnFullscreen').on('click', function() {
        var el = document.getElementById('chartWrapper');
        if (!document.fullscreenElement) {
            el.requestFullscreen().catch(function(err) {
                toastr.warning('No se pudo activar pantalla completa: ' + err.message);
            });
        } else {
            document.exitFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', function() {
        var $btn  = $('#btnFullscreen i');
        var $wrap = $('#chartWrapper');
        if (document.fullscreenElement) {
            $btn.removeClass('fa-expand').addClass('fa-compress');
            $wrap.addClass('fullscreen-active');
        } else {
            $btn.removeClass('fa-compress').addClass('fa-expand');
            $wrap.removeClass('fullscreen-active');
        }
    });

    // ── Zoom manual ───────────────────────────────────────────────────────────
    var zoomLevel = 1;
    $('#btnZoomIn').on('click', function() {
        zoomLevel = Math.min(zoomLevel + 0.15, 2);
        $('#chart-container .orgchart').css('transform', 'scale(' + zoomLevel + ')');
    });
    $('#btnZoomOut').on('click', function() {
        zoomLevel = Math.max(zoomLevel - 0.15, 0.3);
        $('#chart-container .orgchart').css('transform', 'scale(' + zoomLevel + ')');
    });
    $('#btnZoomReset').on('click', function() {
        zoomLevel = 1;
        $('#chart-container .orgchart').css('transform', 'scale(1)');
    });

    // ── Exportar como imagen ──────────────────────────────────────────────────
    $('#btnExportImg').on('click', function() {
        var $btn = $(this);
        $btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
        html2canvas(document.getElementById('chart-container'), {
            backgroundColor: '#f0f4f8',
            scale: 2,
        }).then(function(canvas) {
            var link = document.createElement('a');
            link.download = 'organigrama-{{ addslashes($dependencie->first()->dependency) }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            $btn.html('<i class="fa fa-image"></i>').prop('disabled', false);
        });
    });

    // ── Edición inline con doble click ───────────────────────────────────────
    $(document).on('dblclick', '.node-field', function(e) {
        e.stopPropagation();
        var $field   = $(this);
        var field    = $field.data('field');
        var depId    = $field.data('id');
        var $span    = $field.find('.field-value');
        var current  = $span.text().trim();

        // Evitar doble edición
        if ($field.find('input').length) return;

        var $input = $('<input>')
            .addClass('form-control form-control-sm inline-edit-input')
            .val(current === 'Sin teléfono' || current === 'Sin correo' ? '' : current)
            .attr('placeholder', field === 'phone' ? 'Teléfono' : 'Correo');

        $span.hide();
        $field.append($input);
        $input.focus().select();

        function guardar() {
            var newVal = $input.val().trim();
            if (newVal === current) {
                $input.remove();
                $span.show();
                return;
            }

            var payload = { _token: '{{ csrf_token() }}' };
            payload[field] = newVal;

            $.ajax({
                url:  '{{ url('admin/globales/organigramas') }}/' + depId,
                type: 'POST',
                data: Object.assign(payload, { _method: 'PUT',
                    dependency: $field.closest('.node').find('.title').text().trim(),
                }),
                success: function() {
                    $span.html(newVal || '<em class="text-muted">Sin ' + (field === 'phone' ? 'teléfono' : 'correo') + '</em>');
                    $input.remove();
                    $span.show();
                    toastr.success('Actualizado correctamente.');
                },
                error: function() {
                    toastr.error('Error al guardar.');
                    $input.remove();
                    $span.show();
                }
            });
        }

        $input.on('blur', guardar);
        $input.on('keydown', function(e) {
            if (e.key === 'Enter')  { guardar(); }
            if (e.key === 'Escape') { $input.remove(); $span.show(); }
        });
    });

    // ── Control de nivel vertical ─────────────────────────────────────────────
    $('body').on('click', '.vertical-level-btn', function() {
        $('.vertical-level-btn').removeClass('active');
        $(this).addClass('active');
        currentLevel = parseInt($(this).data('level'));
        initOrgchart(currentLevel);
    });

    // ── Responsive ────────────────────────────────────────────────────────────
    $(window).resize(function() {
        initOrgchart($(window).width() > 768 ? currentLevel : 2);
    });

    // ── DataTable en tab tabla ────────────────────────────────────────────────
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr('href') === '#tabTabla') {
            if (!$.fn.DataTable.isDataTable('#tablaOrg')) {
                $('#tablaOrg').DataTable({
                    language: {
                        search: 'Buscar:', emptyTable: 'Sin dependencias',
                        zeroRecords: 'Sin resultados',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_',
                        paginate: { next: 'Siguiente', previous: 'Anterior' }
                    },
                    pageLength: 25, order: [[0, 'asc']],
                });
            }
        }
    });
});
</script>
@stop
