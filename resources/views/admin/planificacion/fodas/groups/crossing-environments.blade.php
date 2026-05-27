@extends('layouts.master')
@section('title', 'Cruce de Ambientes')

@section('css')
<style>
/* Contener texto en celdas FODA */
.table-responsive table td {
    overflow: hidden;
    word-break: break-word;
    overflow-wrap: break-word;
    vertical-align: top;
}
/* Evitar que los divs internos rompan el layout */
.table-responsive table td > div {
    max-width: 100%;
    overflow: hidden;
}
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-random mr-2"></i>
            Cruce de Ambientes
            @if(isset($profile) && $profile) — {{ strip_tags($profile->name) }} @endif
        </h4>
        <p class="card-category">Estrategias FO · FA · DO · DA</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('foda-list-groups') }}">Análisis FODA</a></li>
            @if(isset($profile) && $profile)
            <li class="breadcrumb-item">
                @if($profile->group_id)
                <a href="{{ route('foda-matriz-groups', $profile->group_id) }}">Matriz</a>
                @else
                <span>Matriz</span>
                @endif
            </li>
            @endif
            <li class="breadcrumb-item active">Cruce de Ambientes</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- Colores por cuadrante para los badges de cobertura --}}
        @php
        $cuadranteColor = ['FO'=>'#1a7a4a','DO'=>'#1a5fa0','FA'=>'#b45309','DA'=>'#9b1c1c'];
        @endphp

        {{-- ══ TABLA PRINCIPAL — estructura original mejorada ══ --}}
        <div class="table-responsive">
            <table class="table table-bordered mb-0" style="table-layout:fixed;width:100%">

                <tbody>

                    {{-- ── FILA 1: Encabezado vacío | Fortalezas | Debilidades ── --}}
                    <tr>
                        {{-- Celda vacía esquina --}}
                        <td style="width:22%;background:#f8f9fa;border-color:#dee2e6">
                            @if(isset($profile) && $profile)
                            <div style="font-size:.78rem;color:#6c757d">
                                <div class="font-weight-bold text-dark mb-1">{{ strip_tags($profile->name) }}</div>
                                @if($profile->context)
                                <div class="text-muted" style="font-size:.72rem">{{ Str::limit(strip_tags($profile->context), 80) }}</div>
                                @endif
                            </div>
                            @endif
                        </td>

                        {{-- Fortalezas --}}
                        <td style="width:39%;background:#f0fff4;vertical-align:top;padding:0">
                            <div style="background:#28a745;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-arrow-up mr-1"></i> Fortalezas
                                <span class="badge badge-light text-success ml-1">{{ count($fortalezas) }}</span>
                            </div>
                            <div style="padding:10px 14px">
                                @foreach($fortalezas as $v)
                                @php $cuadrantes = $fortalezasCubiertas[$v->aspecto->id] ?? []; @endphp
                                <div class="d-flex align-items-start mb-1">
                                    <span class="badge badge-success mr-2 mt-1 flex-shrink-0" style="font-size:.68rem;min-width:26px"
                                          data-toggle="tooltip" title="{{ $v->aspecto->name }}">F{{ $v->aspecto->id }}</span>
                                    <div style="font-size:.78rem;color:#374151;line-height:1.4">{{ $v->aspecto->name }}
                                        @foreach($cuadrantes as $q)
                                        @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                        <span class="badge ml-1" style="font-size:.58rem;background:{{ $qc }};color:#fff" data-toggle="tooltip" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- Debilidades --}}
                        <td style="width:39%;background:#fff5f5;vertical-align:top;padding:0">
                            <div style="background:#dc3545;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-arrow-down mr-1"></i> Debilidades
                                <span class="badge badge-light text-danger ml-1">{{ count($debilidades) }}</span>
                            </div>
                            <div style="padding:10px 14px">
                                @foreach($debilidades as $v)
                                @php $cuadrantes = $debilidadesCubiertas[$v->aspecto->id] ?? []; @endphp
                                <div class="d-flex align-items-start mb-1">
                                    <span class="badge badge-danger mr-2 mt-1 flex-shrink-0" style="font-size:.68rem;min-width:26px"
                                          data-toggle="tooltip" title="{{ $v->aspecto->name }}">D{{ $v->aspecto->id }}</span>
                                    <div style="font-size:.78rem;color:#374151;line-height:1.4">{{ $v->aspecto->name }}
                                        @if($v->causa_raiz)
                                        <span class="badge badge-warning ml-1" style="font-size:.58rem" data-toggle="tooltip" title="{{ \App\Admin\Planificacion\Foda\FodaAnalisis::CAUSAS_RAIZ[$v->causa_raiz] ?? '' }}"><i class="fa fa-shield-alt"></i></span>
                                        @endif
                                        @foreach($cuadrantes as $q)
                                        @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                        <span class="badge ml-1" style="font-size:.58rem;background:{{ $qc }};color:#fff" data-toggle="tooltip" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>

                    {{-- ── FILA 2: Oportunidades | FO | DO ── --}}
                    <tr>
                        {{-- Oportunidades --}}
                        <td style="background:#e8f7fa;vertical-align:top;padding:0">
                            <div style="background:#17a2b8;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-star mr-1"></i> Oportunidades
                                <span class="badge badge-light text-info ml-1">{{ count($oportunidades) }}</span>
                            </div>
                            <div style="padding:10px 14px">
                                @foreach($oportunidades as $v)
                                @php $cuadrantes = $oportunidadesCubiertas[$v->aspecto->id] ?? []; @endphp
                                <div class="d-flex align-items-start mb-1">
                                    <span class="badge badge-info mr-2 mt-1 flex-shrink-0" style="font-size:.68rem;min-width:26px"
                                          data-toggle="tooltip" title="{{ $v->aspecto->name }}">O{{ $v->aspecto->id }}</span>
                                    <div style="font-size:.78rem;color:#374151;line-height:1.4">{{ $v->aspecto->name }}
                                        @foreach($cuadrantes as $q)
                                        @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                        <span class="badge ml-1" style="font-size:.58rem;background:{{ $qc }};color:#fff" data-toggle="tooltip" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- FO --}}
                        <td style="background:#f0fff4;vertical-align:top;padding:0">
                            <div class="d-flex align-items-center justify-content-between"
                                 style="background:#1a7a4a;color:#fff;padding:8px 14px">
                                <span style="font-weight:700;font-size:.82rem">
                                    FO — Ofensivas
                                    <span class="badge badge-light text-success ml-1">{{ count($FOs) }}</span>
                                </span>
                                <a href="{{ route('foda-matriz-crossing-fo', $idPerfil) }}"
                                   class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="FO"
                                   style="background:rgba(255,255,255,.2);color:#fff;width:28px;height:28px;padding:0;line-height:28px;text-align:center"
                                   title="Agregar estrategia FO">
                                    <i class="fa fa-plus" style="font-size:.7rem"></i>
                                </a>
                            </div>
                            <div style="padding:10px 14px">
                                @forelse($FOs as $vi)
                                <div class="mb-2 pb-2 border-bottom" style="font-size:.82rem">
                                    <div class="mb-1">
                                        @foreach($vi->fortalezas as $b)
                                        <span class="badge badge-success mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">F{{ $b->id }}</span>
                                        @endforeach
                                        @foreach($vi->oportunidades as $b)
                                        <span class="badge badge-info mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">O{{ $b->id }}</span>
                                        @endforeach
                                    </div>
                                    <div style="color:#374151;line-height:1.4">{{ $vi->estrategia }}</div>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-circle btn-info btnEditarEstrategia"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-id="{{ $vi->id }}" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {!! Form::open(['route'=>['foda-cruce-ambientes.destroy',$vi->id],'method'=>'DELETE','style'=>'display:inline']) !!}
                                        <button type="button" class="btn btn-circle btn-danger btnEliminar"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-texto="{{ Str::limit($vi->estrategia,40) }}" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin estrategias FO</p>
                                @endforelse
                            </div>
                        </td>

                        {{-- DO --}}
                        <td style="background:#f0f8ff;vertical-align:top;padding:0">
                            <div class="d-flex align-items-center justify-content-between"
                                 style="background:#1a5fa0;color:#fff;padding:8px 14px">
                                <span style="font-weight:700;font-size:.82rem">
                                    DO — Reorientación
                                    <span class="badge badge-light text-primary ml-1">{{ count($DOs) }}</span>
                                </span>
                                <a href="{{ route('foda-matriz-crossing-do', $idPerfil) }}"
                                   class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="DO"
                                   style="background:rgba(255,255,255,.2);color:#fff;width:28px;height:28px;padding:0;line-height:28px;text-align:center"
                                   title="Agregar estrategia DO">
                                    <i class="fa fa-plus" style="font-size:.7rem"></i>
                                </a>
                            </div>
                            <div style="padding:10px 14px">
                                @forelse($DOs as $vi)
                                <div class="mb-2 pb-2 border-bottom" style="font-size:.82rem">
                                    <div class="mb-1">
                                        @foreach($vi->debilidades as $b)
                                        <span class="badge badge-danger mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">D{{ $b->id }}</span>
                                        @endforeach
                                        @foreach($vi->oportunidades as $b)
                                        <span class="badge badge-info mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">O{{ $b->id }}</span>
                                        @endforeach
                                    </div>
                                    <div style="color:#374151;line-height:1.4">{{ $vi->estrategia }}</div>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-circle btn-info btnEditarEstrategia"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-id="{{ $vi->id }}" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {!! Form::open(['route'=>['foda-cruce-ambientes.destroy',$vi->id],'method'=>'DELETE','style'=>'display:inline']) !!}
                                        <button type="button" class="btn btn-circle btn-danger btnEliminar"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-texto="{{ Str::limit($vi->estrategia,40) }}" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin estrategias DO</p>
                                @endforelse
                            </div>
                        </td>
                    </tr>

                    {{-- ── FILA 3: Amenazas | FA | DA ── --}}
                    <tr>
                        {{-- Amenazas --}}
                        <td style="background:#fff8f0;vertical-align:top;padding:0">
                            <div style="background:#fd7e14;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-exclamation-triangle mr-1"></i> Amenazas
                                <span class="badge badge-light text-warning ml-1">{{ count($amenazas) }}</span>
                            </div>
                            <div style="padding:10px 14px">
                                @foreach($amenazas as $v)
                                @php $cuadrantes = $amenazasCubiertas[$v->aspecto->id] ?? []; @endphp
                                <div class="d-flex align-items-start mb-1">
                                    <span class="badge badge-warning mr-2 mt-1 flex-shrink-0" style="font-size:.68rem;min-width:26px"
                                          data-toggle="tooltip" title="{{ $v->aspecto->name }}">A{{ $v->aspecto->id }}</span>
                                    <div style="font-size:.78rem;color:#374151;line-height:1.4">{{ $v->aspecto->name }}
                                        @if($v->causa_raiz)
                                        <span class="badge badge-warning ml-1" style="font-size:.58rem" data-toggle="tooltip" title="{{ \App\Admin\Planificacion\Foda\FodaAnalisis::CAUSAS_RAIZ[$v->causa_raiz] ?? '' }}"><i class="fa fa-shield-alt"></i></span>
                                        @endif
                                        @foreach($cuadrantes as $q)
                                        @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                        <span class="badge ml-1" style="font-size:.58rem;background:{{ $qc }};color:#fff" data-toggle="tooltip" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- FA --}}
                        <td style="background:#fff8f0;vertical-align:top;padding:0">
                            <div class="d-flex align-items-center justify-content-between"
                                 style="background:#b45309;color:#fff;padding:8px 14px">
                                <span style="font-weight:700;font-size:.82rem">
                                    FA — Defensivas
                                    <span class="badge badge-light text-warning ml-1">{{ count($FAs) }}</span>
                                </span>
                                <a href="{{ route('foda-matriz-crossing-fa', $idPerfil) }}"
                                   class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="FA"
                                   style="background:rgba(255,255,255,.2);color:#fff;width:28px;height:28px;padding:0;line-height:28px;text-align:center"
                                   title="Agregar estrategia FA">
                                    <i class="fa fa-plus" style="font-size:.7rem"></i>
                                </a>
                            </div>
                            <div style="padding:10px 14px">
                                @forelse($FAs as $vi)
                                <div class="mb-2 pb-2 border-bottom" style="font-size:.82rem">
                                    <div class="mb-1">
                                        @foreach($vi->fortalezas as $b)
                                        <span class="badge badge-success mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">F{{ $b->id }}</span>
                                        @endforeach
                                        @foreach($vi->amenazas as $b)
                                        <span class="badge badge-warning mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">A{{ $b->id }}</span>
                                        @endforeach
                                    </div>
                                    <div style="color:#374151;line-height:1.4">{{ $vi->estrategia }}</div>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-circle btn-info btnEditarEstrategia"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-id="{{ $vi->id }}" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {!! Form::open(['route'=>['foda-cruce-ambientes.destroy',$vi->id],'method'=>'DELETE','style'=>'display:inline']) !!}
                                        <button type="button" class="btn btn-circle btn-danger btnEliminar"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-texto="{{ Str::limit($vi->estrategia,40) }}" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin estrategias FA</p>
                                @endforelse
                            </div>
                        </td>

                        {{-- DA --}}
                        <td style="background:#fff5f5;vertical-align:top;padding:0">
                            <div class="d-flex align-items-center justify-content-between"
                                 style="background:#9b1c1c;color:#fff;padding:8px 14px">
                                <span style="font-weight:700;font-size:.82rem">
                                    DA — Supervivencia
                                    <span class="badge badge-light text-danger ml-1">{{ count($DAs) }}</span>
                                </span>
                                <a href="{{ route('foda-matriz-crossing-da', $idPerfil) }}"
                                   class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="DA"
                                   style="background:rgba(255,255,255,.2);color:#fff;width:28px;height:28px;padding:0;line-height:28px;text-align:center"
                                   title="Agregar estrategia DA">
                                    <i class="fa fa-plus" style="font-size:.7rem"></i>
                                </a>
                            </div>
                            <div style="padding:10px 14px">
                                @forelse($DAs as $vi)
                                <div class="mb-2 pb-2 border-bottom" style="font-size:.82rem">
                                    <div class="mb-1">
                                        @foreach($vi->debilidades as $b)
                                        <span class="badge badge-danger mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">D{{ $b->id }}</span>
                                        @endforeach
                                        @foreach($vi->amenazas as $b)
                                        <span class="badge badge-warning mr-1" style="font-size:.65rem"
                                              data-toggle="tooltip" title="{{ $b->name }}">A{{ $b->id }}</span>
                                        @endforeach
                                    </div>
                                    <div style="color:#374151;line-height:1.4">{{ $vi->estrategia }}</div>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-circle btn-info btnEditarEstrategia"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-id="{{ $vi->id }}" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {!! Form::open(['route'=>['foda-cruce-ambientes.destroy',$vi->id],'method'=>'DELETE','style'=>'display:inline']) !!}
                                        <button type="button" class="btn btn-circle btn-danger btnEliminar"
                                                style="width:26px;height:26px;padding:0;line-height:26px;font-size:.65rem"
                                                data-texto="{{ Str::limit($vi->estrategia,40) }}" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin estrategias DA</p>
                                @endforelse
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- ── Acciones ── --}}
        <div class="mt-3 text-right">
            <a href="{{ route('foda-cruce-pdf', $idPerfil) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-file-pdf mr-1"></i> Descargar PDF
            </a>
        </div>

    </div>
</div>

{{-- ══ MODAL: Nueva Estrategia ══ --}}
<div class="modal fade" id="modalEstrategia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- Header dinámico según tipo --}}
            <div class="modal-header py-3" id="modalEstrategiaHeader" style="border-radius:4px 4px 0 0">
                <div>
                    <h5 class="modal-title mb-0 text-white font-weight-bold" id="modalEstrategiaTitulo"></h5>
                    <small class="text-white-50" id="modalEstrategiaSubtitulo"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body pt-3">
                <form id="formEstrategia">
                    <input type="hidden" name="perfil_id" value="{{ $idPerfil }}">
                    <input type="hidden" name="user_id"   value="{{ auth()->id() }}">
                    <input type="hidden" name="tipo"      id="estrategiaTipo">

                    <div class="row">
                        {{-- Columna izquierda: checkboxes --}}
                        <div class="col-md-5">
                            <div id="checkboxesGrupo1" class="mb-3">
                                <label class="font-weight-bold mb-2" id="labelGrupo1" style="font-size:.82rem"></label>
                                <div id="listaGrupo1" class="pl-1" style="max-height:180px;overflow-y:auto"></div>
                            </div>
                            <div id="checkboxesGrupo2">
                                <label class="font-weight-bold mb-2" id="labelGrupo2" style="font-size:.82rem"></label>
                                <div id="listaGrupo2" class="pl-1" style="max-height:180px;overflow-y:auto"></div>
                            </div>
                        </div>

                        {{-- Columna derecha: estrategia --}}
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="font-weight-bold" style="font-size:.82rem">
                                    Enunciado de la Estrategia <span class="text-danger">*</span>
                                </label>
                                <textarea name="estrategia" id="estrategiaTexto"
                                    class="form-control" rows="5"
                                    placeholder="Redactá la estrategia de forma concreta y accionable...&#10;&#10;Ej: Aprovechar la capacidad tecnológica instalada (F2) para capturar el crecimiento del mercado digital (O1)..."></textarea>
                                <small class="text-muted">
                                    Referenciá los aspectos seleccionados usando F#, O#, D#, A#
                                </small>
                            </div>

                            {{-- Indicador de aspectos seleccionados --}}
                            <div id="seleccionIndicador" class="mt-2" style="min-height:28px"></div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline-info" id="btnGenerarIA" title="Generar con Groq / Llama 3">
                    <i class="fa fa-magic mr-1"></i> Generar con IA
                </button>
                <button type="button" class="btn btn-success" id="btnGuardarEstrategia">
                    <i class="fa fa-save mr-1"></i> Guardar Estrategia
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $('[data-toggle="tooltip"]').tooltip({ placement: 'bottom' });

    // ── Datos de aspectos disponibles (pasados desde PHP) ────────────────────
    var aspectos = {
        fortalezas:   @json($fortalezas->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
        debilidades:  @json($debilidades->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
        oportunidades:@json($oportunidades->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
        amenazas:     @json($amenazas->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
    };

    // ── IDs de aspectos que ya tienen al menos una estrategia ────────────────
    var cubiertos = {
        fortaleza:   @json($fortalezasCubiertas),
        debilidad:   @json($debilidadesCubiertas),
        oportunidad: @json($oportunidadesCubiertas),
        amenaza:     @json($amenazasCubiertas),
    };

    // Configuración por tipo
    var config = {
        FO: { titulo: 'Nueva Estrategia Ofensiva', sub: 'Fortalezas × Oportunidades', color: '#1a7a4a',
              g1: { label: 'Fortalezas', name: 'fortaleza_id[]',   prefix: 'F', color: 'success', data: aspectos.fortalezas },
              g2: { label: 'Oportunidades', name: 'oportunidad_id[]', prefix: 'O', color: 'info',    data: aspectos.oportunidades } },
        DO: { titulo: 'Nueva Estrategia de Reorientación', sub: 'Debilidades × Oportunidades', color: '#1a5fa0',
              g1: { label: 'Debilidades', name: 'debilidad_id[]',   prefix: 'D', color: 'danger', data: aspectos.debilidades },
              g2: { label: 'Oportunidades', name: 'oportunidad_id[]', prefix: 'O', color: 'info',   data: aspectos.oportunidades } },
        FA: { titulo: 'Nueva Estrategia Defensiva', sub: 'Fortalezas × Amenazas', color: '#b45309',
              g1: { label: 'Fortalezas', name: 'fortaleza_id[]',   prefix: 'F', color: 'success', data: aspectos.fortalezas },
              g2: { label: 'Amenazas',   name: 'amenaza_id[]',     prefix: 'A', color: 'warning', data: aspectos.amenazas } },
        DA: { titulo: 'Nueva Estrategia de Supervivencia', sub: 'Debilidades × Amenazas', color: '#9b1c1c',
              g1: { label: 'Debilidades', name: 'debilidad_id[]', prefix: 'D', color: 'danger',  data: aspectos.debilidades },
              g2: { label: 'Amenazas',    name: 'amenaza_id[]',   prefix: 'A', color: 'warning', data: aspectos.amenazas } },
    };

    // ── Abrir modal al hacer clic en botón + ─────────────────────────────────
    $(document).on('click', '.btnAgregarEstrategia', function(e) {
        e.preventDefault();
        var tipo = $(this).data('tipo');
        var cfg  = config[tipo];

        // Header
        $('#modalEstrategiaHeader').css('background', cfg.color);
        $('#modalEstrategiaTitulo').text(cfg.titulo);
        $('#modalEstrategiaSubtitulo').text(cfg.sub);
        $('#estrategiaTipo').val(tipo);
        $('#estrategiaTexto').val('');
        $('#seleccionIndicador').empty();

        // Grupo 1
        $('#labelGrupo1').html('<i class="fa fa-check-square mr-1"></i>' + cfg.g1.label);
        renderCheckboxes('#listaGrupo1', cfg.g1);

        // Grupo 2
        $('#labelGrupo2').html('<i class="fa fa-check-square mr-1"></i>' + cfg.g2.label);
        renderCheckboxes('#listaGrupo2', cfg.g2);

        $('#modalEstrategia').modal('show');
    });

    // ── Renderizar checkboxes ─────────────────────────────────────────────────
    function renderCheckboxes(selector, grupo) {
        var $lista = $(selector).empty();
        if (!grupo.data || grupo.data.length === 0) {
            $lista.html('<small class="text-muted font-italic">Sin aspectos disponibles</small>');
            return;
        }

        // Clave para buscar en el mapa de cubiertos (fortaleza, debilidad, oportunidad, amenaza)
        var clave = grupo.name.replace('_id[]', '');
        // cubiertos[clave] es un objeto { id: [cuadrantes] } — ej: { 5: ['FO','FA'] }
        var mapaCubiertos = cubiertos[clave] || {};

        // Colores por cuadrante
        var qColor = { FO: '#1a7a4a', DO: '#1a5fa0', FA: '#b45309', DA: '#9b1c1c' };

        grupo.data.forEach(function(asp) {
            var cuadrantesAsp = mapaCubiertos[asp.id] || [];
            var $label = $('<label class="d-flex align-items-center mb-2" style="cursor:pointer;font-size:.82rem">').append(
                $('<input type="checkbox" class="mr-2 aspecto-check">').attr('name', grupo.name).val(asp.id),
                $('<span class="badge badge-' + grupo.color + ' mr-2" style="font-size:.68rem;min-width:26px">')
                    .text(grupo.prefix + asp.id),
                $('<span style="color:#374151;flex:1">').text(asp.name)
            );

            if (cuadrantesAsp.length > 0) {
                var $badges = $('<span class="ml-1">');
                cuadrantesAsp.forEach(function(q) {
                    $badges.append(
                        $('<span class="badge mr-1" style="font-size:.6rem;color:#fff;background:' + (qColor[q]||'#666') + '">')
                            .attr('title', 'Ya cubierto en ' + q)
                            .text(q)
                    );
                });
                $label.append($badges);
                $label.css('opacity', '0.75');
            }

            $lista.append($label);
        });
    }

    // ── Actualizar indicador de selección ─────────────────────────────────────
    $(document).on('change', '.aspecto-check', function() {
        var badges = [];
        $('#formEstrategia input[type=checkbox]:checked').each(function() {
            var name = $(this).attr('name');
            var val  = $(this).val();
            var tipo = $(this).data('tipo') || '';
            // Determinar prefijo por nombre del campo
            var prefix = name.startsWith('fortaleza') ? 'F' :
                         name.startsWith('debilidad')  ? 'D' :
                         name.startsWith('oportunidad')? 'O' : 'A';
            var color  = name.startsWith('fortaleza') ? 'success' :
                         name.startsWith('debilidad')  ? 'danger' :
                         name.startsWith('oportunidad')? 'info' : 'warning';
            badges.push('<span class="badge badge-' + color + ' mr-1">' + prefix + val + '</span>');
        });
        $('#seleccionIndicador').html(
            badges.length > 0
                ? '<small class="text-muted">Seleccionados: </small>' + badges.join('')
                : ''
        );
    });

    // ── Generar estrategia con IA (Groq / Llama 3) ───────────────────────────
    $('#btnGenerarIA').on('click', function() {
        var tipo = $('#estrategiaTipo').val();
        var cfg  = config[tipo];

        // Recopilar aspectos seleccionados
        var grupo1 = [];
        var grupo2 = [];

        $('#listaGrupo1 input[type=checkbox]:checked').each(function() {
            var id   = $(this).val();
            var name = $(this).closest('label').find('span:last').text().trim();
            grupo1.push({ id: id, name: name, prefix: cfg.g1.prefix });
        });

        $('#listaGrupo2 input[type=checkbox]:checked').each(function() {
            var id   = $(this).val();
            var name = $(this).closest('label').find('span:last').text().trim();
            grupo2.push({ id: id, name: name, prefix: cfg.g2.prefix });
        });

        if (grupo1.length === 0 || grupo2.length === 0) {
            toastr.warning('Seleccioná al menos un aspecto de cada grupo antes de generar.');
            return;
        }

        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando...').prop('disabled', true);
        $('#estrategiaTexto').val('').attr('placeholder', '⏳ Llama 3 está redactando la estrategia...');

        $.ajax({
            url: '{{ route('foda-cruce-ambientes.ia') }}',
            type: 'POST',
            data: {
                tipo:      tipo,
                grupo1:    grupo1,
                grupo2:    grupo2,
                perfil_id: $('input[name=perfil_id]').val(),
            },
            success: function(resp) {
                if (resp.estrategia) {
                    $('#estrategiaTexto').val(resp.estrategia);
                    toastr.success('Estrategia generada. Podés editarla antes de guardar.');
                } else {
                    toastr.error('No se pudo generar la estrategia.');
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.error || 'Error al conectar con la IA.';
                toastr.error(msg);
                $('#estrategiaTexto').attr('placeholder', 'Escribí la estrategia manualmente...');
            },
            complete: function() {
                btn.html('<i class="fa fa-magic mr-1"></i> Generar con IA').prop('disabled', false);
                $('#estrategiaTexto').attr('placeholder', 'Podés editar el texto generado...');
            }
        });
    });
    $('#btnGuardarEstrategia').on('click', function() {
        var estrategia = $('#estrategiaTexto').val().trim();
        if (!estrategia) {
            toastr.warning('Escribí el enunciado de la estrategia.');
            $('#estrategiaTexto').focus();
            return;
        }

        var btn    = $(this);
        var editId = $('#formEstrategia').data('editId');
        var modo   = $('#formEstrategia').data('modo') || 'crear';

        btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        var url  = modo === 'editar'
            ? '{{ url('foda-cruce-ambientes') }}/' + editId
            : '{{ route('foda-cruce-ambientes.store') }}';
        var type = modo === 'editar' ? 'PUT' : 'POST';

        $.ajax({
            url:  url,
            type: type,
            data: $('#formEstrategia').serialize(),
            success: function() {
                $('#modalEstrategia').modal('hide');
                toastr.success(modo === 'editar' ? 'Estrategia actualizada.' : 'Estrategia guardada.');
                setTimeout(function() { location.reload(); }, 800);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
            },
            complete: function() {
                btn.html('<i class="fa fa-save mr-1"></i> Guardar Estrategia').prop('disabled', false);
            }
        });
    });

    // ── Editar estrategia — cargar datos en el modal ─────────────────────────
    $(document).on('click', '.btnEditarEstrategia', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.get('{{ url('foda-cruce-ambientes') }}/' + id + '/edit', function(data) {
            var tipo = data.tipo;
            var cfg  = config[tipo];

            $('#modalEstrategiaHeader').css('background', cfg.color);
            $('#modalEstrategiaTitulo').text('Editar Estrategia ' + tipo);
            $('#modalEstrategiaSubtitulo').text(cfg.sub);
            $('#estrategiaTipo').val(tipo);
            $('#estrategiaTexto').val(data.estrategia);
            $('#seleccionIndicador').empty();
            $('#formEstrategia').data('editId', id);
            $('#formEstrategia').data('modo', 'editar');

            renderCheckboxes('#listaGrupo1', cfg.g1);
            renderCheckboxes('#listaGrupo2', cfg.g2);

            // Marcar checkboxes previamente seleccionados
            setTimeout(function() {
                var map = { fortaleza: data.fortalezas, debilidad: data.debilidades,
                            oportunidad: data.oportunidades, amenaza: data.amenazas };

                $('#listaGrupo1 input[type=checkbox], #listaGrupo2 input[type=checkbox]').each(function() {
                    var fieldName = $(this).attr('name').replace('_id[]','');
                    var ids = map[fieldName] || [];
                    if (ids.includes(parseInt($(this).val()))) $(this).prop('checked', true);
                });
                $('#listaGrupo1 input, #listaGrupo2 input').trigger('change');
            }, 50);

            $('#modalEstrategia').modal('show');
        });
    });

    // Resetear modo al abrir para crear
    $(document).on('click', '.btnAgregarEstrategia', function() {
        $('#formEstrategia').data('editId', null).data('modo', 'crear');
    });
    $(document).on('click', '.btnEliminar', function() {
        var texto = $(this).data('texto');
        var form  = $(this).closest('form');
        Swal.fire({
            title: '¿Eliminar estrategia?',
            text: '"' + texto + '"',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then(function(r) {
            if (r.isConfirmed) form.submit();
        });
    });
});
</script>
@endsection 