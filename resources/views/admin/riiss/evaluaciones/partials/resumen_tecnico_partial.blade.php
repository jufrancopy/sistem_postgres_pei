@php
    $pctServicios    = (float)($evaluacion->porcentaje_cumplimiento ?? 0);
    $pctHabilitacion = (float)($evaluacion->pct_habilitacion ?? 0);
    $fotosList       = is_array($evaluacion->fotos) ? $evaluacion->fotos : [];
    $lat             = $est->latitude ?? null;
    $lng             = $est->longitude ?? null;
    $mapId           = 'mapaResumen_' . $evaluacion->id;

    $colorServicios = $pctServicios >= 90 ? '#10b981' : ($pctServicios >= 70 ? '#f59e0b' : '#ef4444');
    $colorHabilitacion = $pctHabilitacion >= 90 ? '#10b981' : ($pctHabilitacion >= 70 ? '#f59e0b' : '#ef4444');
    
    // Lista unificada de especialidades activas
    $especialidadesTodas = $est->especialidades->merge($especialidadesAgregadas);

    // Parsear evaluadores de forma segura
    $listaNombresEvaluadores = [];
    if (is_array($evaluacion->evaluadores)) {
        foreach ($evaluacion->evaluadores as $evItem) {
            $nom = is_array($evItem) ? ($evItem['text'] ?? ($evItem['nombre'] ?? ($evItem['name'] ?? ''))) : (is_object($evItem) ? ($evItem->text ?? ($evItem->nombre ?? ($evItem->name ?? ''))) : (string)$evItem);
            $nom = trim($nom);
            if ($nom && $nom !== trim((string)$evaluacion->evaluador_nombre) && !in_array($nom, $listaNombresEvaluadores)) {
                $listaNombresEvaluadores[] = $nom;
            }
        }
    }
@endphp

<div class="resumen-tecnico-wrapper">

    {{-- 1. ENCABEZADO DE LA FICHA TÉCNICA --}}
    <div class="rt-header">
        <div class="rt-header-info">
            <div class="rt-header-badges">
                <span class="rt-pill" style="background:#0284c7; color:#ffffff;">
                    <i class="fa fa-hospital mr-1"></i> {{ $est->id_establecimiento }}
                </span>
                @if($est->complejidadTipo)
                    <span class="rt-pill" style="background:{{ $est->complejidadTipo->color ?? '#3b82f6' }}; color:#ffffff;">
                        {{ $est->complejidadTipo->nombre }} (Grado {{ $est->complejidadTipo->grado }})
                    </span>
                @endif
                @if($est->area_gestion)
                    <span class="rt-pill" style="background:#0f172a; color:#ffffff;">
                        <i class="fa fa-map-marked-alt mr-1"></i> {{ $est->area_gestion }}
                    </span>
                @endif
                <span class="rt-pill" style="background:#e0f2fe; color:#0369a1;">
                    <i class="fa fa-map-pin mr-1"></i> {{ $est->departamento ?? 'Sin departamento' }}
                </span>
            </div>
            <h3 class="rt-title">{{ $est->nombre_oficial }}</h3>
            <p class="rt-subtitle">
                <span><i class="fa fa-calendar-alt text-primary mr-1"></i> Relevamiento: <strong>{{ $evaluacion->fecha_evaluacion?->format('d/m/Y') ?? 'En proceso' }}</strong></span>
                @if($evaluacion->evaluador_nombre)
                    <span> · <i class="fa fa-user-check text-info mr-1"></i> Evaluador Líder: <strong>{{ $evaluacion->evaluador_nombre }}</strong></span>
                @endif
            </p>
        </div>
        <div class="rt-header-actions">
            <a href="{{ route('evaluaciones.resumen-publico', $evaluacion->id) }}" target="_blank" class="btn-rt-action" title="Abrir en ventana completa">
                <i class="fa fa-external-link-alt"></i> Enlace Directo
            </a>
            <button type="button" class="btn-rt-action" onclick="copiarUrlFicha('{{ route('evaluaciones.resumen-publico', $evaluacion->id) }}')" title="Copiar enlace público">
                <i class="fa fa-copy"></i> Compartir
            </button>
        </div>
    </div>

    {{-- 2. GRID DE INDICADORES DE CUMPLIMIENTO --}}
    <div class="rt-kpi-grid">
        {{-- Cartera de Servicios --}}
        <div class="rt-kpi-card">
            <div class="rt-ring-box">
                <svg viewBox="0 0 36 36" class="rt-ring-svg">
                    <path class="rt-ring-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="rt-ring-fill" stroke="{{ $colorServicios }}" stroke-dasharray="{{ $pctServicios }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="rt-ring-text" style="color:{{ $colorServicios }}">{{ round($pctServicios) }}%</div>
            </div>
            <div class="rt-kpi-info">
                <div class="rt-kpi-label">Cartera de Servicios</div>
                <div class="rt-kpi-val">{{ $evaluacion->clasificacion_resultado ? str_replace('_', ' ', $evaluacion->clasificacion_resultado) : 'EN EVALUACIÓN' }}</div>
                <div class="rt-kpi-sub">{{ $gapCartera->where('estado', 'cumple')->count() }} de {{ $gapCartera->count() }} servicios verificados</div>
            </div>
        </div>

        {{-- Condiciones Habilitantes --}}
        <div class="rt-kpi-card">
            <div class="rt-ring-box">
                <svg viewBox="0 0 36 36" class="rt-ring-svg">
                    <path class="rt-ring-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="rt-ring-fill" stroke="{{ $colorHabilitacion }}" stroke-dasharray="{{ $pctHabilitacion }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="rt-ring-text" style="color:{{ $colorHabilitacion }}">{{ round($pctHabilitacion) }}%</div>
            </div>
            <div class="rt-kpi-info">
                <div class="rt-kpi-label">Condiciones Habilitantes</div>
                <div class="rt-kpi-val">{{ $evaluacion->clasificacion_habilitacion ? str_replace('_', ' ', $evaluacion->clasificacion_habilitacion) : 'EN EVALUACIÓN' }}</div>
                <div class="rt-kpi-sub">Infraestructura, equipamiento y recursos</div>
            </div>
        </div>

        {{-- Especialidades y Fotos --}}
        <div class="rt-kpi-card" style="justify-content: space-around;">
            <div class="text-center">
                <div class="font-weight-bold" style="font-size:24px; color:#0284c7; font-family:'Outfit', sans-serif;">
                    {{ $especialidadesTodas->count() }}
                </div>
                <div class="text-muted small font-weight-600" style="font-size:11px;">Especialidades Médicas</div>
            </div>
            <div style="width:1px; height:40px; background:#e2e8f0;"></div>
            <div class="text-center">
                <div class="font-weight-bold" style="font-size:24px; color:#8b5cf6; font-family:'Outfit', sans-serif;">
                    {{ count($fotosList) }}
                </div>
                <div class="text-muted small font-weight-600" style="font-size:11px;">Fotos Relevadas</div>
            </div>
        </div>
    </div>

    {{-- 3. ESPACIO PARA MAPA DE UBICACIÓN & DATOS DE TERRENO --}}
    <div class="rt-section-card">
        <div class="rt-section-header">
            <div class="rt-section-title">
                <i class="fa fa-map-marked-alt text-primary"></i> Ubicación Geográfica & Entorno Territorial
            </div>
            @if($lat && $lng)
                <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" target="_blank" class="btn-rt-map-link">
                    <i class="fa fa-directions mr-1"></i> Abrir en Google Maps / Waze
                </a>
            @endif
        </div>
        
        <div class="rt-map-layout-grid">
            <div id="{{ $mapId }}" class="rt-map-container" data-lat="{{ $lat ?? '' }}" data-lng="{{ $lng ?? '' }}" data-nombre="{{ addslashes($est->nombre_oficial) }}" data-complejidad="{{ $est->complejidadTipo->nombre ?? '' }}">
                @if(!$lat || !$lng)
                    <div class="rt-map-empty">
                        <i class="fa fa-map-marker-alt fa-2x mb-2" style="color:#94a3b8;"></i>
                        <div style="font-weight:700; color:#475569;">Coordenadas pendientes de georreferenciación</div>
                        <small style="color:#94a3b8;">No se registraron latitud y longitud satelital para este centro.</small>
                    </div>
                @endif
            </div>
            <div class="rt-location-details">
                <div class="rt-loc-item">
                    <i class="fa fa-map-pin" style="color:#ef4444;"></i>
                    <div>
                        <div class="rt-loc-label">Dirección / Referencia</div>
                        <div class="rt-loc-val">{{ $est->direccion ?? 'Sin dirección registrada en ficha' }}</div>
                    </div>
                </div>
                <div class="rt-loc-item">
                    <i class="fa fa-layer-group" style="color:#0284c7;"></i>
                    <div>
                        <div class="rt-loc-label">Distrito / Microred</div>
                        <div class="rt-loc-val">{{ $est->microred ?? 'Red Asistencial RIISS' }} · {{ $est->departamento }}</div>
                    </div>
                </div>
                <div class="rt-loc-item">
                    <i class="fa fa-building" style="color:#64748b;"></i>
                    <div>
                        <div class="rt-loc-label">Condición del Inmueble</div>
                        <div class="rt-loc-val">{{ $est->situacion_inmueble ?? ($est->condicion_inmueble ?? 'Propio / Comodato') }}</div>
                    </div>
                </div>
                @if($lat && $lng)
                    <div class="rt-loc-item">
                        <i class="fa fa-satellite" style="color:#10b981;"></i>
                        <div>
                            <div class="rt-loc-label">Coordenadas Satelitales (GPS)</div>
                            <div class="rt-loc-val" style="font-size:11.5px; font-family: monospace;">{{ number_format($lat, 6) }}, {{ number_format($lng, 6) }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 4. CARTERA DE ESPECIALIDADES MÉDICAS --}}
    <div class="rt-section-card">
        <div class="rt-section-header">
            <div class="rt-section-title">
                <i class="fa fa-stethoscope text-primary"></i> Cartera de Especialidades Médicas
            </div>
            <span class="rt-pill" style="background:#0284c7; color:#ffffff;">
                {{ $especialidadesTodas->count() }} Especialidades Validadas
            </span>
        </div>
        <div style="padding: 20px;">
            @if($especialidadesTodas->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-stethoscope fa-2x mb-2 text-secondary" style="opacity:0.35;"></i>
                    <p class="mb-0 font-weight-bold">No hay especialidades médicas registradas aún para este establecimiento.</p>
                </div>
            @else
                <div class="rt-esp-grid">
                    @foreach($especialidadesTodas as $esp)
                        @php
                            $valReg = $validacionesEspecialidades[$esp->id] ?? null;
                            $esActiva = ($valReg && $valReg->estado === 'inactiva') ? false : true;
                        @endphp
                        <div class="rt-esp-chip {{ $esActiva ? 'activa' : 'inactiva' }}">
                            <div class="rt-esp-icon">
                                <i class="fa {{ $esActiva ? 'fa-check' : 'fa-times' }}"></i>
                            </div>
                            <div class="rt-esp-body">
                                <div class="rt-esp-nombre">{{ $esp->nombre }}</div>
                                <div class="rt-esp-meta">
                                    <span class="rt-esp-badge {{ $esActiva ? 'badge-activa' : 'badge-inactiva' }}">
                                        {{ $esActiva ? 'Activa / Validada' : 'Inactiva' }}
                                    </span>
                                    @if($valReg && $valReg->es_agregada)
                                        <span class="rt-esp-badge" style="background:#fef3c7; color:#92400e; margin-left:4px;">
                                            <i class="fa fa-plus-circle mr-1"></i> Agregada en terreno
                                        </span>
                                    @endif
                                </div>
                                @if($valReg && !empty($valReg->justificacion))
                                    <div class="rt-esp-obs"><i class="fa fa-info-circle mr-1"></i>{{ $valReg->justificacion }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- 5. FARMACIA, PROGRAMAS Y MEDICAMENTOS CRÓNICOS --}}
    <div class="rt-section-card">
        <div class="rt-section-header">
            <div class="rt-section-title">
                <i class="fa fa-pills" style="color:#f59e0b;"></i> Cobertura Farmacéutica & Programas Crónicos
            </div>
        </div>
        <div style="padding: 20px;">
            <div class="rt-programs-grid">
                <div class="rt-program-box {{ $est->habilita_farmacia_cronicos ? 'enabled' : 'disabled' }}">
                    <div class="rt-program-icon">
                        <i class="fa fa-prescription-bottle-alt"></i>
                    </div>
                    <div>
                        <div class="rt-program-title">Dispensación de Medicamentos Crónicos</div>
                        <div class="rt-program-desc">
                            {{ $est->habilita_farmacia_cronicos ? 'Habilitado para entrega mensual de tratamientos de pacientes crónicos.' : 'No habilitado para dispensación directa de crónicos en este nivel.' }}
                        </div>
                        <span class="rt-program-badge {{ $est->habilita_farmacia_cronicos ? 'badge-success' : 'badge-secondary' }}">
                            {{ $est->habilita_farmacia_cronicos ? '✔ HABILITADO' : '✖ NO HABILITADO' }}
                        </span>
                    </div>
                </div>
                <div class="rt-program-box {{ $est->habilita_empadronamiento_cronicos ? 'enabled' : 'disabled' }}">
                    <div class="rt-program-icon">
                        <i class="fa fa-id-card-alt"></i>
                    </div>
                    <div>
                        <div class="rt-program-title">Empadronamiento de Pacientes Crónicos</div>
                        <div class="rt-program-desc">
                            {{ $est->habilita_empadronamiento_cronicos ? 'Habilitado para registro y validación en padrón de pacientes crónicos IPS.' : 'Sin registro de empadronamiento activo en este centro.' }}
                        </div>
                        <span class="rt-program-badge {{ $est->habilita_empadronamiento_cronicos ? 'badge-success' : 'badge-secondary' }}">
                            {{ $est->habilita_empadronamiento_cronicos ? '✔ HABILITADO' : '✖ NO HABILITADO' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 6. EVIDENCIA FOTOGRÁFICA RELEVADA --}}
    <div class="rt-section-card">
        <div class="rt-section-header">
            <div class="rt-section-title">
                <i class="fa fa-camera text-info"></i> Evidencia Fotográfica del Relevamiento
            </div>
            <span class="rt-pill" style="background:#0284c7; color:#ffffff;">
                {{ count($fotosList) }} Fotos Registradas
            </span>
        </div>
        <div style="padding: 20px;">
            @if(empty($fotosList))
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-images fa-2x mb-2 text-secondary" style="opacity:0.35;"></i>
                    <p class="mb-0 font-weight-bold">Sin fotografías adjuntas en el relevamiento de este centro.</p>
                </div>
            @else
                <div class="rt-fotos-grid">
                    @foreach($fotosList as $idx => $foto)
                        @php
                            $urlFoto = $foto['url'] ?? '#';
                            $descFoto = $foto['descripcion'] ?? 'Evidencia fotográfica #' . ($idx + 1);
                            $fechaFoto = $foto['fecha'] ?? '';
                        @endphp
                        <div class="rt-foto-card" onclick="verFotoLightbox('{{ $urlFoto }}', '{{ addslashes($descFoto) }}', '{{ $fechaFoto }}', 'Foto #{{ $idx + 1 }}')">
                            <div class="rt-foto-thumb">
                                <img src="{{ $urlFoto }}" alt="{{ $descFoto }}" loading="lazy">
                                <div class="rt-foto-overlay">
                                    <i class="fa fa-search-plus fa-lg"></i>
                                </div>
                                <span class="badge badge-dark rt-foto-badge">
                                    #{{ $idx + 1 }}
                                </span>
                            </div>
                            @if(!empty($foto['descripcion']))
                                <div class="rt-foto-caption" title="{{ $descFoto }}">
                                    {{ $descFoto }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- 7. SELLO DE CERTIFICACIÓN DIGITAL & CIERRE OFICIAL --}}
    <div class="rt-section-card rt-seal-box">
        <div class="rt-seal-header">
            <div class="rt-seal-header-left">
                <div class="rt-seal-icon">
                    <i class="fa fa-certificate"></i>
                </div>
                <div>
                    <h5 class="rt-seal-title">Sello de Certificación y Validación Oficial</h5>
                    <p class="rt-seal-subtitle">Instituto de Previsión Social — Red Integrada de Servicios de Salud (RIISS)</p>
                </div>
            </div>
            <div class="rt-seal-badge">
                <i class="fa fa-check-double mr-1"></i> RELEVAMIENTO OFICIAL
            </div>
        </div>
        <div class="rt-seal-body">
            <div class="rt-seal-grid">
                <div class="rt-seal-col rt-seal-border-right">
                    <div class="rt-seal-field-label"><i class="fa fa-user-shield mr-1"></i> Equipo Evaluador & Auditoría</div>
                    <div class="rt-seal-field-val">{{ $evaluacion->evaluador_nombre ?? 'Equipo Técnico de Planificación' }}</div>
                    @if(!empty($listaNombresEvaluadores))
                        <div class="rt-seal-field-sub">
                            <strong>Acompañantes:</strong> {{ implode(', ', $listaNombresEvaluadores) }}
                        </div>
                    @endif
                    <div class="rt-seal-field-sub"><i class="fa fa-calendar-check mr-1"></i> Fecha de Relevamiento: <strong>{{ $evaluacion->fecha_evaluacion?->format('d/m/Y') ?? '—' }}</strong></div>
                </div>
                <div class="rt-seal-col">
                    <div class="rt-seal-field-label"><i class="fa fa-hospital-user mr-1"></i> Responsable del Establecimiento</div>
                    <div class="rt-seal-field-val">{{ $evaluacion->responsable_nombre ?? 'Director / Encargado de Centro' }}</div>
                    <div class="rt-seal-field-sub">{{ $evaluacion->responsable_cargo ?? 'Dirección Médica / Administrativa' }}</div>
                    @if($evaluacion->cerrado_at)
                        <div class="rt-seal-stamp">
                            <i class="fa fa-stamp mr-1"></i> Acta sellada el {{ $evaluacion->cerrado_at->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
            @if(!empty($evaluacion->cierre_observaciones))
                <div class="rt-seal-obs-box">
                    <div class="rt-seal-obs-title"><i class="fa fa-comment-dots mr-1"></i> Observaciones del Cierre Institucional:</div>
                    <div class="rt-seal-obs-text">{{ $evaluacion->cierre_observaciones }}</div>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- Estilos encapsulados para el Resumen Técnico --}}
<style>
.resumen-tecnico-wrapper {
    color: #1e293b !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 13px;
    line-height: 1.5;
}
.resumen-tecnico-wrapper * {
    box-sizing: border-box;
}

/* Header */
.rt-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 22px 24px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 16px;
    color: #ffffff !important;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15);
}
.rt-header-badges {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 8px;
}
.rt-pill {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
}
.rt-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.45rem;
    font-weight: 800;
    letter-spacing: -0.3px;
    margin: 0 0 6px 0;
    color: #ffffff !important;
}
.rt-subtitle {
    margin: 0;
    font-size: 0.85rem;
    color: #cbd5e1 !important;
}
.rt-subtitle strong {
    color: #ffffff !important;
}
.rt-header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.btn-rt-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-rt-action:hover {
    background: #0284c7;
    color: #ffffff !important;
    border-color: #0284c7;
    transform: translateY(-1px);
}

/* KPI Grid */
.rt-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.rt-kpi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.rt-ring-box {
    position: relative;
    width: 62px;
    height: 62px;
    flex-shrink: 0;
}
.rt-ring-svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}
.rt-ring-bg {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 3.2;
}
.rt-ring-fill {
    fill: none;
    stroke-width: 3.2;
    stroke-linecap: round;
    transition: stroke-dasharray 0.8s ease;
}
.rt-ring-text {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    font-size: 13px;
    font-weight: 800;
    font-family: 'Outfit', sans-serif;
}
.rt-kpi-info {
    flex: 1;
}
.rt-kpi-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
}
.rt-kpi-val {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
}
.rt-kpi-sub {
    font-size: 11.5px;
    color: #94a3b8;
    margin-top: 2px;
}

/* Sections */
.rt-section-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.rt-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.rt-section-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.btn-rt-map-link {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 8px;
    border: 1px solid #0284c7;
    background: #f0f9ff;
    color: #0284c7 !important;
    font-weight: 700;
    font-size: 11px;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-rt-map-link:hover {
    background: #0284c7;
    color: #ffffff !important;
}

/* Map Grid */
.rt-map-layout-grid {
    display: grid;
    grid-template-columns: 1.3fr 0.9fr;
}
.rt-map-container {
    height: 280px;
    width: 100%;
    background: #f1f5f9;
    position: relative;
    border-right: 1px solid #e2e8f0;
}
.rt-map-empty {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
    color: #64748b;
}
.rt-location-details {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 14px;
    background: #ffffff;
}
.rt-loc-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.rt-loc-item i {
    margin-top: 3px;
    font-size: 15px;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}
.rt-loc-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.3px;
    margin-bottom: 2px;
}
.rt-loc-val {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
}

/* Specialties */
.rt-esp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 12px;
}
.rt-esp-chip {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    transition: all 0.2s ease;
}
.rt-esp-chip.activa {
    border-left: 4px solid #10b981;
    background: #f0fdf4;
}
.rt-esp-chip.inactiva {
    border-left: 4px solid #ef4444;
    background: #fef2f2;
    opacity: 0.75;
}
.rt-esp-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    font-size: 11px;
}
.rt-esp-chip.activa .rt-esp-icon {
    background: #dcfce7;
    color: #15803d;
}
.rt-esp-chip.inactiva .rt-esp-icon {
    background: #fee2e2;
    color: #b91c1c;
}
.rt-esp-nombre {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
.rt-esp-meta {
    margin-top: 4px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
.rt-esp-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
}
.badge-activa {
    background: #dcfce7;
    color: #166534;
}
.badge-inactiva {
    background: #fee2e2;
    color: #991b1b;
}
.rt-esp-obs {
    font-size: 11px;
    color: #64748b;
    margin-top: 4px;
    font-style: italic;
}

/* Programs */
.rt-programs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
}
.rt-program-box {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    height: 100%;
}
.rt-program-box.enabled {
    border-left: 4px solid #10b981;
    background: #f8fafc;
}
.rt-program-box.disabled {
    border-left: 4px solid #cbd5e1;
    background: #f8fafc;
    opacity: 0.85;
}
.rt-program-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #e0f2fe;
    color: #0369a1;
    display: grid;
    place-items: center;
    font-size: 18px;
    flex-shrink: 0;
}
.rt-program-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 3px;
}
.rt-program-desc {
    font-size: 12px;
    color: #64748b;
    line-height: 1.4;
    margin-bottom: 6px;
}
.rt-program-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10.5px;
    font-weight: 800;
}
.rt-program-badge.badge-success {
    background: #dcfce7;
    color: #166534;
}
.rt-program-badge.badge-secondary {
    background: #f1f5f9;
    color: #64748b;
}

/* Photos */
.rt-fotos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 14px;
}
.rt-foto-card {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}
.rt-foto-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}
.rt-foto-thumb {
    position: relative;
    height: 130px;
    background: #0f172a;
    overflow: hidden;
}
.rt-foto-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.rt-foto-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.4);
    display: grid;
    place-items: center;
    color: #ffffff;
    opacity: 0;
    transition: opacity 0.2s;
}
.rt-foto-card:hover .rt-foto-overlay {
    opacity: 1;
}
.rt-foto-badge {
    position: absolute;
    top: 6px;
    left: 6px;
    font-size: 10px;
    background: rgba(15, 23, 42, 0.85);
    color: #ffffff;
    padding: 2px 6px;
    border-radius: 4px;
}
.rt-foto-caption {
    padding: 8px 10px;
    font-size: 11px;
    font-weight: 600;
    color: #334155;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    background: #ffffff;
}

/* ═══ SELLO DE CERTIFICACIÓN DIGITAL (ESTILOS PROTEGIDOS) ═══ */
.rt-seal-box {
    background: #ffffff;
    border: 2px solid #cbd5e1;
    border-radius: 14px;
    overflow: hidden;
}
.rt-seal-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.rt-seal-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.rt-seal-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: rgba(56, 189, 248, 0.2) !important;
    color: #38bdf8 !important;
    display: grid;
    place-items: center;
    font-size: 18px;
    flex-shrink: 0;
}
.rt-seal-title {
    color: #ffffff !important;
    font-family: 'Outfit', sans-serif;
    font-size: 15px !important;
    font-weight: 800 !important;
    margin: 0 0 2px 0 !important;
    letter-spacing: -0.2px;
}
.rt-seal-subtitle {
    color: #93c5fd !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    margin: 0 !important;
}
.rt-seal-badge {
    background: #10b981 !important;
    color: #ffffff !important;
    font-size: 11.5px !important;
    font-weight: 800 !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    display: inline-flex;
    align-items: center;
    letter-spacing: 0.3px;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.rt-seal-body {
    padding: 22px 24px;
    background: #ffffff;
}
.rt-seal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
.rt-seal-col {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.rt-seal-border-right {
    border-right: 1px solid #e2e8f0;
    padding-right: 20px;
}
.rt-seal-field-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.rt-seal-field-val {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
}
.rt-seal-field-sub {
    font-size: 12px;
    color: #64748b;
}
.rt-seal-stamp {
    display: inline-flex;
    align-items: center;
    margin-top: 6px;
    padding: 4px 10px;
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    width: fit-content;
}
.rt-seal-obs-box {
    margin-top: 18px;
    padding: 12px 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}
.rt-seal-obs-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
    margin-bottom: 4px;
}
.rt-seal-obs-text {
    font-size: 12.5px;
    font-style: italic;
    color: #1e293b;
    line-height: 1.4;
}

@media (max-width: 768px) {
    .rt-header {
        flex-direction: column;
        gap: 14px;
    }
    .rt-map-layout-grid {
        grid-template-columns: 1fr;
    }
    .rt-map-container {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
    }
    .rt-seal-grid {
        grid-template-columns: 1fr;
    }
    .rt-seal-border-right {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        padding-right: 0;
        padding-bottom: 16px;
    }
}
</style>

{{-- Script de Inicialización del Mapa para este Resumen --}}
<script>
(function() {
    var mapContainer = document.getElementById('{{ $mapId }}');
    if (!mapContainer) return;

    var lat = parseFloat(mapContainer.getAttribute('data-lat'));
    var lng = parseFloat(mapContainer.getAttribute('data-lng'));
    var nombre = mapContainer.getAttribute('data-nombre') || 'Establecimiento';
    var complejidad = mapContainer.getAttribute('data-complejidad') || '';

    if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
        setTimeout(function() {
            if (typeof L !== 'undefined') {
                var mapInstance = L.map('{{ $mapId }}', {
                    center: [lat, lng],
                    zoom: 15,
                    zoomControl: true,
                    scrollWheelZoom: false
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapInstance);

                var customIcon = L.divIcon({
                    className: 'custom-map-pin',
                    html: '<div style="background:#0284c7; color:#fff; width:34px; height:34px; border-radius:50%; display:grid; place-items:center; border:3px solid #fff; box-shadow:0 4px 10px rgba(0,0,0,0.3); font-size:14px;"><i class="fa fa-hospital"></i></div>',
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -20]
                });

                var marker = L.marker([lat, lng], { icon: customIcon }).addTo(mapInstance);
                marker.bindPopup(`<strong>${nombre}</strong><br><small class="text-muted">${complejidad}</small>`).openPopup();

                // Forzar repintado por si está en modal
                setTimeout(function() {
                    mapInstance.invalidateSize();
                }, 400);
            }
        }, 300);
    }
})();
</script>
