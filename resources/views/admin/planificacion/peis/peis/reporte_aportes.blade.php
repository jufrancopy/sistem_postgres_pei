<div class="card border-0 shadow-none mb-0">
    <div class="card-header bg-dark text-white p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
        <div>
            <span class="badge badge-warning text-dark font-weight-bold mb-1" style="font-size: 0.75rem;">
                <i class="fa fa-user-check mr-1"></i> ASESORÍA EXTERNA & VALIDACIÓN REMOTA
            </span>
            <h5 class="modal-title font-weight-bold text-white mb-0" style="font-size: 1.15rem;">
                {{ strip_tags($profile->name) }}
            </h5>
        </div>
        <div class="mt-2 mt-md-0 d-flex align-items-center" style="gap: 8px;">
            <button type="button" class="btn btn-sm btn-outline-light font-weight-bold" onclick="window.print();" title="Imprimir Reporte">
                <i class="fa fa-print mr-1"></i> Imprimir Reporte
            </button>
        </div>
    </div>

    @php
        $dictamenesCompletados = $asesorias->where('estado', 'COMPLETADO')->whereNotNull('dictamen_general')->where('dictamen_general', '!=', '');
        $totalComentariosNodos = 0;
        foreach($asesorias as $as) {
            $totalComentariosNodos += $as->comentarios->count();
        }
    @endphp

    <div class="card-body p-4" style="background: #f8fafc;">

        {{-- TARJETAS RESUMEN --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="p-3 bg-white rounded shadow-sm border text-center">
                    <div class="text-muted font-weight-bold small text-uppercase mb-1">Asesores Convocados</div>
                    <div class="h3 font-weight-bold text-dark mb-0">{{ $asesorias->count() }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="p-3 bg-white rounded shadow-sm border text-center border-warning">
                    <div class="text-warning font-weight-bold small text-uppercase mb-1">Dictámenes Finalizados</div>
                    <div class="h3 font-weight-bold text-warning mb-0">{{ $dictamenesCompletados->count() }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm border text-center border-primary">
                    <div class="text-primary font-weight-bold small text-uppercase mb-1">Sugerencias por Elemento</div>
                    <div class="h3 font-weight-bold text-primary mb-0">{{ $totalComentariosNodos }}</div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 1: DICTÁMENES GENERALES (SÍNTESIS MACRO) --}}
        <div class="card border shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fa fa-file-signature text-warning mr-2"></i> Dictámenes u Observaciones Generales de los Asesores
                </h6>
            </div>
            <div class="card-body p-4">
                @if($dictamenesCompletados->count() > 0)
                    <div class="row">
                        @foreach($dictamenesCompletados as $asDoc)
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded border shadow-sm h-100" style="background: #fffbeb; border-left: 5px solid #d97706 !important;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                <i class="fa fa-user-circle text-warning mr-1"></i> {{ $asDoc->nombre }}
                                            </div>
                                            @if($asDoc->institucion)
                                                <small class="text-muted"><i class="fa fa-building mr-1"></i>{{ $asDoc->institucion }}</small>
                                            @endif
                                        </div>
                                        <span class="badge badge-success font-weight-bold px-2 py-1" style="font-size: 0.72rem;">COMPLETADO</span>
                                    </div>
                                    <div class="p-2 bg-white rounded border text-dark" style="font-size: 0.88rem; line-height: 1.45; white-space: pre-wrap;">{{ $asDoc->dictamen_general }}</div>
                                    <div class="text-right mt-2 text-muted" style="font-size: 0.72rem;">
                                        <i class="fa fa-clock mr-1"></i> Registrado el: {{ \Carbon\Carbon::parse($asDoc->updated_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light border text-center py-4 mb-0 text-muted">
                        <i class="fa fa-info-circle fa-2x text-warning mb-2 d-block"></i>
                        Ningún asesor ha finalizado su dictamen general macro aún.
                    </div>
                @endif
            </div>
        </div>

        {{-- SECCIÓN 2: SUGERENCIAS POR ELEMENTO DEL PEI --}}
        <div class="card border shadow-sm mb-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fa fa-sitemap text-primary mr-2"></i> Sugerencias Específicas por Elemento de la Jerarquía PEI
                </h6>
                <span class="badge badge-pill badge-primary px-3 py-1" style="font-size: 0.78rem;">{{ $totalComentariosNodos }} aportes</span>
            </div>
            <div class="card-body p-4">
                @if($totalComentariosNodos > 0)
                    <div class="list-group list-group-flush">
                        @foreach($asesorias as $asVal)
                            @foreach($asVal->comentarios as $comItem)
                                @php
                                    $nodoTarget = $nodosMap[$comItem->node_id] ?? null;
                                    $iniTarget = $iniciativasMap[$comItem->node_id] ?? null;
                                    $nombreElem = $nodoTarget ? strip_tags($nodoTarget->name) : ($iniTarget ? strip_tags($iniTarget->nombre) : 'Elemento ID: '.$comItem->node_id);
                                    $tipoElem = $nodoTarget ? strtoupper($nodoTarget->level) : ($iniTarget ? 'ACCIÓN OPERATIVA' : 'COMPONENTE');
                                    
                                    // Formato de nombre de nivel
                                    if ($tipoElem === 'AXI') $tipoElem = 'OBJETIVO ESTRATÉGICO';
                                    if ($tipoElem === 'GOAL' || $tipoElem === 'OBJETIVO_ESPECIFICO') $tipoElem = 'OBJETIVO ESPECÍFICO';
                                    if ($tipoElem === 'ACTION' || $tipoElem === 'ACCION_ESTRATEGICA') $tipoElem = 'ACCIÓN ESTRATÉGICA';
                                @endphp
                                <div class="list-group-item px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <span class="badge badge-info mr-2" style="font-size: 0.7rem;">{{ $tipoElem }}</span>
                                            <strong class="text-dark" style="font-size: 0.92rem;">{{ $nombreElem }}</strong>
                                        </div>
                                        <span class="badge badge-light border text-dark" style="font-size: 0.75rem;">
                                            <i class="fa fa-user text-muted mr-1"></i> {{ $asVal->nombre }}
                                        </span>
                                    </div>
                                    <div class="p-2.5 rounded text-dark mt-2" style="background: #f1f5f9; border-left: 4px solid #2563eb; font-size: 0.88rem; line-height: 1.4; white-space: pre-wrap;">
                                        {{ $comItem->comentario }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <small class="text-muted" style="font-size:0.75rem;">
                                            <i class="fa fa-clock mr-1"></i> {{ $comItem->created_at ? $comItem->created_at->format('d/m/Y H:i') : '' }}
                                        </small>
                                        @if(($comItem->estado ?? 'PENDIENTE') === 'INTEGRADO')
                                            <span class="badge badge-success font-weight-bold px-2.5 py-1" style="font-size:0.75rem;">
                                                <i class="fa fa-check-circle mr-1"></i> Aporte Integrado
                                            </span>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold btn-integrar-aporte px-3 py-1" data-id="{{ $comItem->id }}" style="border-radius: 20px;">
                                                <i class="fa fa-paper-plane mr-1"></i> Integrar Aporte y Notificar por Correo
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light border text-center py-4 mb-0 text-muted">
                        <i class="fa fa-comments fa-2x text-primary mb-2 d-block"></i>
                        No se han registrado sugerencias técnicas por elementos específicos del plan aún.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
