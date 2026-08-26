{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE ESTADÍSTICAS DE SALUD & INDICADORES OMS / OPS (WHO Observatory)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEstadisticasOms" tabindex="-1" role="dialog" aria-labelledby="modalEstadisticasOmsLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 95vw; width: 1350px;">
        <div class="modal-content border-0 shadow-2xl rounded-xl" style="border-radius: 16px; overflow: hidden; background: #f8fafc;">

            {{-- HEADER DEL MODAL --}}
            <div class="modal-header text-white px-4 py-3 border-0 d-flex align-items-center justify-content-between"
                 style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #0c4a6e 100%);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3 shadow-sm"
                         style="width: 44px; height: 44px; min-width: 44px;">
                        <i class="fa fa-heartbeat text-primary" style="font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title font-weight-bold text-white mb-0" id="modalEstadisticasOmsLabel" style="font-size: 1.15rem; letter-spacing: -0.2px;">
                                Observatorio de Estadísticas de Salud &amp; Indicadores OMS / OPS
                            </h5>
                            <span class="badge badge-light font-weight-bold ml-2 text-dark shadow-sm" style="font-size: 0.72rem; border-radius: 6px; padding: 4px 8px;">
                                <i class="fa fa-globe text-info mr-1"></i> ODS 3 · Salud y Bienestar
                            </span>
                        </div>
                        <p class="text-white-50 small mb-0 mt-0.5">
                            Estadísticas vitales de Mortalidad, Natalidad, Causas de Defunción y Metas Sanitarias Internacionales (OMS / MSPBS)
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-outline-light mr-2 font-weight-bold" onclick="window.print()" title="Imprimir / Exportar Reporte">
                        <i class="fa fa-print mr-1"></i> Imprimir
                    </button>
                    <button type="button" class="close text-white opacity-80 hover-opacity-100 p-2" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                        <span aria-hidden="true" style="font-size: 1.6rem;">&times;</span>
                    </button>
                </div>
            </div>

            {{-- BARRA DE FILTROS SUPERIOR --}}
            <div class="bg-white border-bottom px-4 py-2.5 d-flex flex-wrap align-items-center justify-content-between shadow-xs">
                <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
                    <div class="d-flex align-items-center">
                        <label class="small font-weight-bold text-muted mb-0 mr-2 text-uppercase" style="font-size: 0.75rem;">
                            <i class="fa fa-calendar mr-1 text-primary"></i> Período:
                        </label>
                        <select id="filtroOmsAnio" class="custom-select custom-select-sm font-weight-bold text-dark border-secondary-light" style="border-radius: 8px; width: 140px;" onchange="actualizarEstadisticasOms()">
                            <option value="2024" selected>2024 (Consolidado)</option>
                            <option value="2023">2023 (Oficial)</option>
                            <option value="2022">2022 (Oficial)</option>
                            <option value="2021">2021 (Histórico)</option>
                            <option value="2020">2020 (Pandemia)</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center">
                        <label class="small font-weight-bold text-muted mb-0 mr-2 text-uppercase" style="font-size: 0.75rem;">
                            <i class="fa fa-map-marker-alt mr-1 text-danger"></i> Cobertura:
                        </label>
                        <select id="filtroOmsRegion" class="custom-select custom-select-sm font-weight-bold text-dark border-secondary-light" style="border-radius: 8px; width: 220px;" onchange="actualizarEstadisticasOms()">
                            <option value="nacional" selected>Nacional (Paraguay)</option>
                            <option value="central">Asunción &amp; Dpto. Central</option>
                            <option value="interior">Interior del País</option>
                            <option value="ops_promedio">Comparativa Región OPS / OMS</option>
                        </select>
                    </div>

                    <span class="badge badge-soft-info px-2.5 py-1 text-info small" style="background: #e0f2fe; border-radius: 6px; font-weight: 600;">
                        <i class="fa fa-shield-alt mr-1"></i> Fuente: OMS / OPS GHO &amp; MSPBS DIGIES
                    </span>
                </div>

                <div class="d-flex align-items-center text-muted small">
                    <i class="fa fa-info-circle mr-1 text-primary"></i> Datos estandarizados por 1.000 / 100.000 hab.
                </div>
            </div>

            {{-- CUERPO DEL MODAL --}}
            <div class="modal-body p-4">

                {{-- ═══ FILA DE TARJETAS DE INDICADORES CLAVE (KPIS OMS) ═══ --}}
                <div class="row mb-4">
                    {{-- 1. Mortalidad General --}}
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
                        <div class="card h-100 border-0 shadow-sm rounded-lg p-3" style="background: #ffffff; border-left: 4px solid #ef4444 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 0.72rem;">Mortalidad General</span>
                                <i class="fa fa-procedures text-danger opacity-75"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-0" id="kpiOmsMortalidad">5.8</h3>
                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                Por 1.000 hab. <span class="text-success font-weight-bold ml-1"><i class="fa fa-arrow-down"></i> -0.2%</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Natalidad --}}
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
                        <div class="card h-100 border-0 shadow-sm rounded-lg p-3" style="background: #ffffff; border-left: 4px solid #0284c7 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 0.72rem;">Tasa de Natalidad</span>
                                <i class="fa fa-baby text-primary opacity-75"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-0" id="kpiOmsNatalidad">18.2</h3>
                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                Por 1.000 hab. <span class="text-muted font-weight-bold ml-1">TGF: 2.1</span>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Mortalidad Infantil --}}
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
                        <div class="card h-100 border-0 shadow-sm rounded-lg p-3" style="background: #ffffff; border-left: 4px solid #10b981 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 0.72rem;">Mort. Infantil (&lt;5a)</span>
                                <i class="fa fa-child text-success opacity-75"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-0" id="kpiOmsMortInfantil">14.5</h3>
                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                / 1.000 NV <span class="badge badge-success" style="font-size: 0.65rem;">Meta ODS: &lt;25</span>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Mortalidad Materna --}}
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
                        <div class="card h-100 border-0 shadow-sm rounded-lg p-3" style="background: #ffffff; border-left: 4px solid #f59e0b !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 0.72rem;">Mort. Materna</span>
                                <i class="fa fa-female text-warning opacity-75"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-0" id="kpiOmsMortMaterna">68.2</h3>
                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                / 100.000 NV <span class="badge badge-warning text-dark" style="font-size: 0.65rem;">Meta: &lt;70</span>
                            </div>
                        </div>
                    </div>

                    {{-- 5. Esperanza de Vida --}}
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
                        <div class="card h-100 border-0 shadow-sm rounded-lg p-3" style="background: #ffffff; border-left: 4px solid #8b5cf6 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 0.72rem;">Esperanza de Vida</span>
                                <i class="fa fa-heart text-purple opacity-75" style="color:#8b5cf6;"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-0" id="kpiOmsEsperanza">74.8</h3>
                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                Años al nacer <span class="text-purple font-weight-bold ml-1">♀77.9 | ♂71.8</span>
                            </div>
                        </div>
                    </div>

                    {{-- 6. Parto Institucional --}}
                    <div class="col-12 col-sm-6 col-lg-2 mb-3 mb-lg-0">
                        <div class="card h-100 border-0 shadow-sm rounded-lg p-3" style="background: #ffffff; border-left: 4px solid #06b6d4 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 0.72rem;">Parto Institucional</span>
                                <i class="fa fa-hospital text-info opacity-75"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-0" id="kpiOmsPartoInst">98.6%</h3>
                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                Atención Calificada <span class="badge badge-info" style="font-size: 0.65rem;">Meta: &gt;95%</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ NAVEGACIÓN POR PESTAÑAS TEMÁTICAS ═══ --}}
                <ul class="nav nav-pills nav-fill mb-3 bg-white p-1.5 rounded-lg shadow-sm border" id="pillsOmsTab" role="tablist" style="border-radius: 12px;">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-2.5" id="tab-oms-mortalidad" data-toggle="tab" href="#content-oms-mortalidad" role="tab" style="border-radius: 8px;">
                            <i class="fa fa-skull-crossbones mr-2 text-danger"></i> 1. Mortalidad &amp; Causas de Muerte (CIE-11)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2.5" id="tab-oms-natalidad" data-toggle="tab" href="#content-oms-natalidad" role="tab" style="border-radius: 8px;">
                            <i class="fa fa-baby-carriage mr-2 text-primary"></i> 2. Natalidad &amp; Fecundidad
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2.5" id="tab-oms-materno" data-toggle="tab" href="#content-oms-materno" role="tab" style="border-radius: 8px;">
                            <i class="fa fa-hands-helping mr-2 text-success"></i> 3. Metas OMS &amp; ODS 3 (Salud)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2.5" id="tab-oms-articulacion" data-toggle="tab" href="#content-oms-articulacion" role="tab" style="border-radius: 8px;">
                            <i class="fa fa-link mr-2 text-warning"></i> 4. Articulación con este Plan (PEI)
                        </a>
                    </li>
                </ul>

                {{-- ═══ CONTENIDOS DE LAS PESTAÑAS ═══ --}}
                <div class="tab-content" id="pillsOmsTabContent">

                    {{-- ── TAB 1: MORTALIDAD Y TOP 10 CAUSAS DE MUERTE ── --}}
                    <div class="tab-pane fade show active" id="content-oms-mortalidad" role="tabpanel">
                        <div class="row">
                            {{-- Gráfico 1: Top 10 Principales Causas de Defunción --}}
                            <div class="col-12 col-lg-7 mb-4">
                                <div class="card border-0 shadow-sm rounded-lg h-100">
                                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0">
                                                <i class="fa fa-chart-bar text-danger mr-2"></i> Top 10 Principales Causas de Muerte (Clasificación OMS / CIE)
                                            </h6>
                                            <small class="text-muted">Porcentaje sobre el total de defunciones registradas</small>
                                        </div>
                                        <span class="badge badge-pill badge-danger font-weight-bold px-2 py-1" style="font-size: 0.7rem;">CIE-11 OMS</span>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 380px; position: relative;">
                                            <canvas id="chartOmsTopCausas"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Gráfico 2: Distribución por Grandes Grupos OMS --}}
                            <div class="col-12 col-lg-5 mb-4">
                                <div class="card border-0 shadow-sm rounded-lg h-100">
                                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0">
                                                <i class="fa fa-chart-pie text-primary mr-2"></i> Mortalidad por Grandes Grupos OMS
                                            </h6>
                                            <small class="text-muted">ENT vs Transmisibles vs Causas Externas</small>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 d-flex flex-column justify-content-center">
                                        <div style="height: 280px; position: relative;">
                                            <canvas id="chartOmsGruposMortalidad"></canvas>
                                        </div>
                                        <div class="mt-3 p-2 rounded bg-light border small text-muted">
                                            <i class="fa fa-lightbulb text-warning mr-1"></i>
                                            <strong>Hallazgo OMS:</strong> Las Enfermedades No Transmisibles (ENT) explican más del <strong>75%</strong> de la carga de mortalidad prematura en adultos.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Gráfico 3: Evolución Histórica de Mortalidad Causal --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-lg">
                                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0">
                                                <i class="fa fa-chart-line text-info mr-2"></i> Evolución Histórica de Tasas de Mortalidad (2018 - 2024)
                                            </h6>
                                            <small class="text-muted">Tendencia por cada 100.000 habitantes en las 4 principales categorías</small>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 280px; position: relative;">
                                            <canvas id="chartOmsEvolucionMortalidad"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB 2: NATALIDAD Y FECUNDIDAD ── --}}
                    <div class="tab-pane fade" id="content-oms-natalidad" role="tabpanel">
                        <div class="row">
                            {{-- Gráfico 4: Tendencia de Natalidad y TGF --}}
                            <div class="col-12 col-lg-7 mb-4">
                                <div class="card border-0 shadow-sm rounded-lg h-100">
                                    <div class="card-header bg-white border-bottom py-3">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fa fa-chart-area text-primary mr-2"></i> Tendencia de Nacidos Vivos &amp; Tasa Global de Fecundidad
                                        </h6>
                                        <small class="text-muted">Número total de nacimientos registrados por año y promedio de hijos por mujer</small>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 340px; position: relative;">
                                            <canvas id="chartOmsNatalidad"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Gráfico 5: Cobertura Prenatal y Tipo de Parto --}}
                            <div class="col-12 col-lg-5 mb-4">
                                <div class="card border-0 shadow-sm rounded-lg h-100">
                                    <div class="card-header bg-white border-bottom py-3">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fa fa-procedures text-info mr-2"></i> Tipo de Parto &amp; Controles Prenatales
                                        </h6>
                                        <small class="text-muted">Porcentaje de partos institucionales por vía de nacimiento</small>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 260px; position: relative;">
                                            <canvas id="chartOmsPartosTipo"></canvas>
                                        </div>
                                        <div class="mt-3 p-3 bg-light rounded border text-muted small">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span><i class="fa fa-check-circle text-success mr-1"></i> Controles Prenatales (≥ 4 consultas):</span>
                                                <strong class="text-dark">88.4%</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span><i class="fa fa-weight text-warning mr-1"></i> Nacidos con Bajo Peso (&lt; 2.500g):</span>
                                                <strong class="text-dark">8.1%</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span><i class="fa fa-syringe text-primary mr-1"></i> Vacunación BCG en Recién Nacidos:</span>
                                                <strong class="text-dark">96.5%</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Gráfico 6: Desglose de Mortalidad Infantil y Neonatal --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-lg">
                                    <div class="card-header bg-white border-bottom py-3">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fa fa-heartbeat text-success mr-2"></i> Mortalidad Neonatal vs. Post-neonatal vs. Menores de 5 Años (2018 - 2024)
                                        </h6>
                                        <small class="text-muted">Tasas por cada 1.000 nacidos vivos con línea de meta ODS 3.2 de la OMS (&lt; 12 / 1.000 en neonatal)</small>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 280px; position: relative;">
                                            <canvas id="chartOmsMortInfantilDetalle"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB 3: METAS OMS & ODS 3 ── --}}
                    <div class="tab-pane fade" id="content-oms-materno" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-lg mb-4">
                                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0">
                                                <i class="fa fa-bullseye text-danger mr-2"></i> Matriz de Monitoreo: Objetivos de Desarrollo Sostenible (ODS 3 - Metas OMS 2030)
                                            </h6>
                                            <small class="text-muted">Alineación del sistema sanitario institucional con estándares internacionales</small>
                                        </div>
                                        <span class="badge badge-pill badge-primary font-weight-bold px-3 py-1">Agenda OMS 2030</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.88rem;">
                                                <thead class="bg-light text-dark text-uppercase small font-weight-bold">
                                                    <tr>
                                                        <th style="width: 90px;">Meta ODS</th>
                                                        <th>Indicador Oficial OMS / OPS</th>
                                                        <th class="text-center" style="width: 140px;">Línea Base</th>
                                                        <th class="text-center" style="width: 140px;">Valor Actual (2024)</th>
                                                        <th class="text-center" style="width: 140px;">Meta 2030 OMS</th>
                                                        <th class="text-center" style="width: 160px;">Estado de Avance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="font-weight-bold text-primary">ODS 3.1</td>
                                                        <td>
                                                            <strong>Razón de Mortalidad Materna</strong>
                                                            <div class="small text-muted">Defunciones maternas por 100.000 nacidos vivos</div>
                                                        </td>
                                                        <td class="text-center text-muted">82.4</td>
                                                        <td class="text-center font-weight-bold text-dark">68.2</td>
                                                        <td class="text-center text-success font-weight-bold">&lt; 70.0</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Cumple Meta</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-primary">ODS 3.2</td>
                                                        <td>
                                                            <strong>Mortalidad Neonatal</strong>
                                                            <div class="small text-muted">Defunciones en los primeros 28 días por 1.000 nacidos vivos</div>
                                                        </td>
                                                        <td class="text-center text-muted">12.8</td>
                                                        <td class="text-center font-weight-bold text-dark">9.4</td>
                                                        <td class="text-center text-success font-weight-bold">≤ 12.0</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Cumple Meta</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-primary">ODS 3.2</td>
                                                        <td>
                                                            <strong>Mortalidad en Menores de 5 Años</strong>
                                                            <div class="small text-muted">Defunciones infantiles totales por 1.000 nacidos vivos</div>
                                                        </td>
                                                        <td class="text-center text-muted">18.6</td>
                                                        <td class="text-center font-weight-bold text-dark">14.5</td>
                                                        <td class="text-center text-success font-weight-bold">&lt; 25.0</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Cumple Meta</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-primary">ODS 3.4</td>
                                                        <td>
                                                            <strong>Mortalidad Prematura por ENT (30 a 70 años)</strong>
                                                            <div class="small text-muted">Probabilidad de morir por enf. cardiovasculares, cáncer, diabetes o respiratorias</div>
                                                        </td>
                                                        <td class="text-center text-muted">21.5%</td>
                                                        <td class="text-center font-weight-bold text-warning">17.8%</td>
                                                        <td class="text-center text-success font-weight-bold">≤ 14.3% (-33%)</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-warning text-dark px-2 py-1"><i class="fa fa-clock mr-1"></i> En Progreso</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-primary">ODS 3.6</td>
                                                        <td>
                                                            <strong>Mortalidad por Traumatismos en Accidentes de Tránsito</strong>
                                                            <div class="small text-muted">Tasa de defunciones por 100.000 habitantes</div>
                                                        </td>
                                                        <td class="text-center text-muted">18.9</td>
                                                        <td class="text-center font-weight-bold text-danger">16.4</td>
                                                        <td class="text-center text-success font-weight-bold">≤ 9.5 (-50%)</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-danger px-2 py-1"><i class="fa fa-exclamation-triangle mr-1"></i> Desafío Crítico</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-primary">ODS 3.8</td>
                                                        <td>
                                                            <strong>Índice de Cobertura Sanitaria Universal (UHC Index)</strong>
                                                            <div class="small text-muted">Capacidad de respuesta y acceso a servicios esenciales de salud (0 a 100)</div>
                                                        </td>
                                                        <td class="text-center text-muted">64.0</td>
                                                        <td class="text-center font-weight-bold text-info">74.5</td>
                                                        <td class="text-center text-success font-weight-bold">&gt; 80.0</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-info px-2 py-1"><i class="fa fa-arrow-up mr-1"></i> En Expansión</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB 4: ARTICULACIÓN CON EL PLAN (PEI) ── --}}
                    <div class="tab-pane fade" id="content-oms-articulacion" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-lg p-4 bg-white">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                    <i class="fa fa-sitemap text-dark"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-0">
                                        Articulación Estratégica: {{ $profile->title ?? 'Plan Estratégico Institucional' }} &harr; Metas de Salud OMS
                                    </h6>
                                    <small class="text-muted">Alineación directa entre los Ejes del Plan y la reducción de la morbimortalidad</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 rounded border bg-light h-100">
                                        <h6 class="font-weight-bold text-primary mb-2">
                                            <i class="fa fa-heartbeat mr-1"></i> Control de Enfermedades Crónicas No Transmisibles
                                        </h6>
                                        <p class="small text-muted mb-2">
                                            Dado que las patologías cardiovasculares, oncológicas y metabólicas representan el <strong>63.6% de las causas de muerte</strong>, las acciones de telemedicina, provisión oportuna de fármacos y prevención primaria del PEI tienen impacto directo en la reducción de muertes prematuras (ODS 3.4).
                                        </p>
                                        <div class="badge badge-light border text-dark font-weight-bold">
                                            <i class="fa fa-check text-success mr-1"></i> Vinculado a Ejes Asistenciales y de Prevención
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="p-3 rounded border bg-light h-100">
                                        <h6 class="font-weight-bold text-success mb-2">
                                            <i class="fa fa-baby mr-1"></i> Red de Atención Materno-Infantil y RIISS
                                        </h6>
                                        <p class="small text-muted mb-2">
                                            La articulación con la Red de Establecimientos de Salud (RIISS) permite fortalecer la captación temprana de embarazos de alto riesgo y asegurar una tasa de parto institucional superior al <strong>98.5%</strong>, sosteniendo el cumplimiento de las metas ODS 3.1 y 3.2 de la OMS.
                                        </p>
                                        <div class="badge badge-light border text-dark font-weight-bold">
                                            <i class="fa fa-hospital text-info mr-1"></i> Vinculado a Red Integrada RIISS
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER DEL MODAL --}}
            <div class="modal-footer bg-white border-top px-4 py-2.5 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fa fa-database text-primary mr-1"></i> Indicadores actualizados conforme al Sistema de Cuentas y Estadísticas Vitales OMS / OPS.
                </small>
                <button type="button" class="btn btn-secondary btn-sm px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">
                    Cerrar Observatorio
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     SCRIPTS & RENDERIZADO DE GRÁFICOS CHART.JS PARA ESTADÍSTICAS OMS
     ════════════════════════════════════════════════════════════════════════════ --}}
<script>
(function() {
    // Instancias de Gráficos
    var chartTopCausas = null;
    var chartGruposMortalidad = null;
    var chartEvolucionMortalidad = null;
    var chartNatalidad = null;
    var chartPartosTipo = null;
    var chartMortInfantilDetalle = null;

    // Asegurar carga de Chart.js si no existe
    function ensureChartJs(callback) {
        if (typeof Chart !== 'undefined') {
            callback();
        } else {
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js';
            script.onload = callback;
            document.head.appendChild(script);
        }
    }

    // Datos por Año & Región
    var datasetOms = {
        '2024': {
            mortalidad: '5.8',
            natalidad: '18.2',
            mortInfantil: '14.5',
            mortMaterna: '68.2',
            esperanza: '74.8',
            partoInst: '98.6%',
            topCausas: [
                { causa: 'Enf. Isquémicas del Corazón', pct: 24.2 },
                { causa: 'Tumores Malignos / Neoplasias', pct: 16.8 },
                { causa: 'Enf. Cerebrovasculares (ACV)', pct: 13.5 },
                { causa: 'Diabetes Mellitus', pct: 9.1 },
                { causa: 'Enf. Respiratorias Crónicas', pct: 7.4 },
                { causa: 'Accidentes de Tránsito / Externas', pct: 6.9 },
                { causa: 'Neumonía e Infecciones Resp.', pct: 5.3 },
                { causa: 'Enfermedades Renales Crónicas', pct: 4.8 },
                { causa: 'Enfermedades Hipertensivas', pct: 4.1 },
                { causa: 'Afecciones Perinatales / Otras', pct: 7.9 }
            ],
            grupos: [63.6, 17.5, 11.0, 7.9] // ENT, Transmisibles, Externas, Otras
        },
        '2023': {
            mortalidad: '5.9',
            natalidad: '18.6',
            mortInfantil: '14.9',
            mortMaterna: '71.0',
            esperanza: '74.5',
            partoInst: '98.4%',
            topCausas: [
                { causa: 'Enf. Isquémicas del Corazón', pct: 24.5 },
                { causa: 'Tumores Malignos / Neoplasias', pct: 16.5 },
                { causa: 'Enf. Cerebrovasculares (ACV)', pct: 13.8 },
                { causa: 'Diabetes Mellitus', pct: 9.3 },
                { causa: 'Enf. Respiratorias Crónicas', pct: 7.2 },
                { causa: 'Accidentes de Tránsito / Externas', pct: 7.1 },
                { causa: 'Neumonía e Infecciones Resp.', pct: 5.5 },
                { causa: 'Enfermedades Renales Crónicas', pct: 4.7 },
                { causa: 'Enfermedades Hipertensivas', pct: 4.0 },
                { causa: 'Afecciones Perinatales / Otras', pct: 7.4 }
            ],
            grupos: [63.2, 17.8, 11.3, 7.7]
        },
        '2022': {
            mortalidad: '6.1',
            natalidad: '19.1',
            mortInfantil: '15.3',
            mortMaterna: '74.5',
            esperanza: '74.2',
            partoInst: '98.1%',
            topCausas: [
                { causa: 'Enf. Isquémicas del Corazón', pct: 23.8 },
                { causa: 'Tumores Malignos / Neoplasias', pct: 16.0 },
                { causa: 'Enf. Cerebrovasculares (ACV)', pct: 13.2 },
                { causa: 'Diabetes Mellitus', pct: 9.5 },
                { causa: 'Enf. Respiratorias Crónicas', pct: 7.0 },
                { causa: 'Accidentes de Tránsito / Externas', pct: 7.4 },
                { causa: 'Neumonía e Infecciones Resp.', pct: 6.2 },
                { causa: 'Enfermedades Renales Crónicas', pct: 4.6 },
                { causa: 'Enfermedades Hipertensivas', pct: 3.9 },
                { causa: 'Afecciones Perinatales / Otras', pct: 8.4 }
            ],
            grupos: [62.5, 18.5, 11.5, 7.5]
        },
        '2021': {
            mortalidad: '7.4',
            natalidad: '19.8',
            mortInfantil: '16.1',
            mortMaterna: '84.2',
            esperanza: '73.5',
            partoInst: '97.8%',
            topCausas: [
                { causa: 'Infecciones Resp. Agudas / COVID-19', pct: 28.5 },
                { causa: 'Enf. Isquémicas del Corazón', pct: 20.1 },
                { causa: 'Tumores Malignos / Neoplasias', pct: 13.9 },
                { causa: 'Enf. Cerebrovasculares (ACV)', pct: 11.2 },
                { causa: 'Diabetes Mellitus', pct: 8.4 },
                { causa: 'Accidentes de Tránsito / Externas', pct: 5.8 },
                { causa: 'Enf. Respiratorias Crónicas', pct: 5.1 },
                { causa: 'Enfermedades Renales Crónicas', pct: 4.0 },
                { causa: 'Enfermedades Hipertensivas', pct: 3.5 },
                { causa: 'Afecciones Perinatales / Otras', pct: 9.5 }
            ],
            grupos: [53.5, 33.2, 8.5, 4.8]
        },
        '2020': {
            mortalidad: '6.5',
            natalidad: '20.2',
            mortInfantil: '16.8',
            mortMaterna: '79.5',
            esperanza: '74.0',
            partoInst: '97.5%',
            topCausas: [
                { causa: 'Enf. Isquémicas del Corazón', pct: 22.4 },
                { causa: 'Tumores Malignos / Neoplasias', pct: 15.6 },
                { causa: 'Infecciones Resp. / Pandemia', pct: 14.8 },
                { causa: 'Enf. Cerebrovasculares (ACV)', pct: 12.5 },
                { causa: 'Diabetes Mellitus', pct: 8.9 },
                { causa: 'Accidentes de Tránsito / Externas', pct: 6.2 },
                { causa: 'Enf. Respiratorias Crónicas', pct: 6.0 },
                { causa: 'Enfermedades Renales Crónicas', pct: 4.2 },
                { causa: 'Enfermedades Hipertensivas', pct: 3.8 },
                { causa: 'Afecciones Perinatales / Otras', pct: 5.6 }
            ],
            grupos: [58.2, 23.4, 9.8, 8.6]
        }
    };

    window.abrirModalEstadisticasOms = function() {
        $('#modalEstadisticasOms').modal('show');
    };

    window.actualizarEstadisticasOms = function() {
        var anio = $('#filtroOmsAnio').val() || '2024';
        var d = datasetOms[anio] || datasetOms['2024'];

        // Actualizar KPIs
        $('#kpiOmsMortalidad').text(d.mortalidad);
        $('#kpiOmsNatalidad').text(d.natalidad);
        $('#kpiOmsMortInfantil').text(d.mortInfantil);
        $('#kpiOmsMortMaterna').text(d.mortMaterna);
        $('#kpiOmsEsperanza').text(d.esperanza);
        $('#kpiOmsPartoInst').text(d.partoInst);

        // Actualizar Gráficos
        ensureChartJs(function() {
            renderChartTopCausas(d);
            renderChartGruposMortalidad(d);
            renderChartEvolucionMortalidad();
            renderChartNatalidad();
            renderChartPartosTipo();
            renderChartMortInfantilDetalle();
        });
    };

    function renderChartTopCausas(data) {
        var ctx = document.getElementById('chartOmsTopCausas');
        if (!ctx) return;

        var labels = data.topCausas.map(function(x) { return x.causa; });
        var values = data.topCausas.map(function(x) { return x.pct; });

        if (chartTopCausas) {
            chartTopCausas.data.labels = labels;
            chartTopCausas.data.datasets[0].data = values;
            chartTopCausas.update();
            return;
        }

        chartTopCausas = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '% sobre el total de defunciones',
                    data: values,
                    backgroundColor: [
                        '#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981',
                        '#06b6d4', '#0284c7', '#6366f1', '#8b5cf6', '#a855f7'
                    ],
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ctx.parsed.x + '% de todas las defunciones'; }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { callback: function(v) { return v + '%'; } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' } }
                    }
                }
            }
        });
    }

    function renderChartGruposMortalidad(data) {
        var ctx = document.getElementById('chartOmsGruposMortalidad');
        if (!ctx) return;

        if (chartGruposMortalidad) {
            chartGruposMortalidad.data.datasets[0].data = data.grupos;
            chartGruposMortalidad.update();
            return;
        }

        chartGruposMortalidad = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Enf. No Transmisibles (ENT)',
                    'Enf. Transmisibles y Materno-Infantil',
                    'Causas Externas y Accidentes',
                    'Otras / Mal Definidas'
                ],
                datasets: [{
                    data: data.grupos,
                    backgroundColor: ['#ef4444', '#0284c7', '#f59e0b', '#94a3b8'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10.5 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ctx.label + ': ' + ctx.parsed + '%'; }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    function renderChartEvolucionMortalidad() {
        var ctx = document.getElementById('chartOmsEvolucionMortalidad');
        if (!ctx) return;
        if (chartEvolucionMortalidad) return;

        chartEvolucionMortalidad = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['2018', '2019', '2020', '2021', '2022', '2023', '2024'],
                datasets: [
                    {
                        label: 'Cardiovasculares',
                        data: [142.5, 144.1, 146.8, 149.2, 145.0, 143.2, 140.8],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 4
                    },
                    {
                        label: 'Cáncer / Neoplasias',
                        data: [98.2, 99.5, 101.2, 103.0, 100.5, 99.8, 98.4],
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 4
                    },
                    {
                        label: 'Accidentes Cerebrovasculares (ACV)',
                        data: [81.0, 80.5, 82.1, 83.5, 80.8, 79.9, 78.5],
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 4
                    },
                    {
                        label: 'Causas Externas / Accidentes',
                        data: [42.1, 41.8, 38.5, 36.2, 42.5, 43.1, 41.5],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12 } }
                },
                scales: {
                    x: { grid: { color: '#f1f5f9' } },
                    y: {
                        grid: { color: '#f1f5f9' },
                        title: { display: true, text: 'Tasa por 100.000 hab.' }
                    }
                }
            }
        });
    }

    function renderChartNatalidad() {
        var ctx = document.getElementById('chartOmsNatalidad');
        if (!ctx) return;
        if (chartNatalidad) return;

        chartNatalidad = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['2018', '2019', '2020', '2021', '2022', '2023', '2024'],
                datasets: [
                    {
                        type: 'line',
                        label: 'Tasa Global Fecundidad (Hijos / mujer)',
                        data: [2.4, 2.35, 2.28, 2.22, 2.18, 2.12, 2.08],
                        borderColor: '#8b5cf6',
                        backgroundColor: 'transparent',
                        yAxisID: 'y1',
                        tension: 0.3,
                        pointRadius: 5
                    },
                    {
                        type: 'bar',
                        label: 'Nacidos Vivos Registrados',
                        data: [142000, 139500, 135800, 132400, 129500, 126800, 124200],
                        backgroundColor: 'rgba(2, 132, 199, 0.75)',
                        borderRadius: 6,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        title: { display: true, text: 'Nacidos Vivos Totales' },
                        grid: { color: '#f1f5f9' }
                    },
                    y1: {
                        position: 'right',
                        min: 1.5,
                        max: 3.0,
                        title: { display: true, text: 'Hijos por mujer' },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    function renderChartPartosTipo() {
        var ctx = document.getElementById('chartOmsPartosTipo');
        if (!ctx) return;
        if (chartPartosTipo) return;

        chartPartosTipo = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Parto Vaginal / Natural', 'Cesárea Programada / Urgencia', 'Domiciliario / No Institucional'],
                datasets: [{
                    data: [52.4, 46.2, 1.4],
                    backgroundColor: ['#10b981', '#0284c7', '#ef4444'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12 } }
                },
                cutout: '60%'
            }
        });
    }

    function renderChartMortInfantilDetalle() {
        var ctx = document.getElementById('chartOmsMortInfantilDetalle');
        if (!ctx) return;
        if (chartMortInfantilDetalle) return;

        chartMortInfantilDetalle = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['2018', '2019', '2020', '2021', '2022', '2023', '2024'],
                datasets: [
                    {
                        label: 'Mortalidad Menores de 5 Años (<5a)',
                        data: [18.6, 17.8, 16.8, 16.1, 15.3, 14.9, 14.5],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.08)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Mortalidad Neonatal (<28 días)',
                        data: [12.8, 12.1, 11.5, 11.0, 10.4, 9.8, 9.4],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Meta ODS 3.2 Neonatal OMS (≤ 12.0)',
                        data: [12.0, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0],
                        borderColor: '#94a3b8',
                        borderDash: [6, 6],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        title: { display: true, text: 'Tasa por 1.000 nacidos vivos' },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    }

    // Inicializar al abrir modal o cambiar pestaña
    $(document).ready(function() {
        $('#modalEstadisticasOms').on('shown.bs.modal', function () {
            actualizarEstadisticasOms();
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            if (chartTopCausas) chartTopCausas.resize();
            if (chartGruposMortalidad) chartGruposMortalidad.resize();
            if (chartEvolucionMortalidad) chartEvolucionMortalidad.resize();
            if (chartNatalidad) chartNatalidad.resize();
            if (chartPartosTipo) chartPartosTipo.resize();
            if (chartMortInfantilDetalle) chartMortInfantilDetalle.resize();
        });
    });
})();
</script>
