<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
    <title>Portal Maestro de Auditoría RIISS - Red Nacional | IPS</title>
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Bootstrap 4.5.2, DataTables & Select2 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --bg-page: #f8fafc;
            --text-dark: #0f172a;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .top-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 0.75rem 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .hero-banner {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            padding: 1.5rem 1rem;
            position: relative;
        }

        @media (min-width: 768px) {
            .top-navbar { padding: 0.85rem 1.5rem; }
            .hero-banner { padding: 2.25rem 1.5rem 2rem; }
        }

        .stat-card {
            background: white;
            border-radius: 0.85rem;
            padding: 0.85rem 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        @media (min-width: 768px) {
            .stat-card {
                padding: 1.25rem;
                border-radius: 1rem;
            }
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        @media (min-width: 768px) {
            .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 1.4rem;
                border-radius: 0.75rem;
            }
        }

        .stat-val {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        @media (min-width: 768px) {
            .stat-val { font-size: 1.5rem; }
        }

        .btn-inspect {
            background: #0284c7;
            color: white;
            font-weight: 700;
            font-size: 0.78rem;
            border-radius: 6px;
            padding: 0.35rem 0.75rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none !important;
            white-space: nowrap;
        }
        .btn-inspect:hover {
            background: #0369a1;
            color: white;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.35);
        }

        .circle-btn-pdf {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            border: 1px solid #fca5a5;
            background: #fee2e2;
            color: #b91c1c;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .circle-btn-pdf:hover {
            background: #fecaca;
            color: #991b1b;
            transform: scale(1.08);
        }

        /* DataTables responsive adjustments */
        table.dataTable tbody td {
            vertical-align: middle;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            padding: 0.35rem 0.65rem;
            outline: none;
            width: 100% !important;
            max-width: 300px;
        }
        @media (max-width: 767px) {
            .dataTables_wrapper .dataTables_filter {
                text-align: left !important;
                margin-top: 0.5rem;
            }
            .dataTables_wrapper .dataTables_filter input {
                max-width: 100%;
                width: 100% !important;
                margin-left: 0 !important;
            }
        }

        /* Select2 Bootstrap 4 Theme tweaks */
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2rem + 2px) !important;
            font-size: 0.85rem !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            display: flex;
            align-items: center;
            background-color: #ffffff;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 0.65rem !important;
            color: #1e293b !important;
            font-weight: 600;
            line-height: normal !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%);
            right: 8px !important;
        }
        .select2-dropdown {
            border-color: #cbd5e1 !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15) !important;
            font-size: 0.85rem !important;
            z-index: 1060;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.4rem !important;
            padding: 0.35rem 0.6rem !important;
            outline: none !important;
            font-size: 0.85rem !important;
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background-color: #0284c7 !important;
            color: #ffffff !important;
        }
        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
        }
    </style>
</head>
<body>

<!-- Barra Superior -->
<nav class="top-navbar">
    <div class="container-fluid px-2 px-md-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.78rem; background: #0284c7;">
                    <i class="fas fa-globe-americas mr-1"></i> AUDITORÍA GLOBAL
                </span>
                <span class="font-weight-bold text-white small d-none d-sm-inline">
                    Instituto de Previsión Social (IPS) • RIISS
                </span>
            </div>
            <div>
                <span class="badge badge-warning px-2 py-1 text-dark font-weight-bold" style="border-radius: 20px; font-size: 0.78rem;">
                    <i class="fas fa-hourglass-half mr-1"></i> Quedan {{ $tokenRecord->tiempo_restante_texto }}
                </span>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="container-fluid px-2 px-md-3">
        <div class="row align-items-center m-0">
            <div class="col-12 p-0">
                <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 0.4rem;">
                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 0.75rem;">
                        <i class="fas fa-check-circle mr-1"></i> Acceso Master de Análisis
                    </span>
                    <span class="badge badge-light px-2 py-1 font-weight-bold text-dark" style="font-size: 0.75rem;">
                        <i class="fas fa-shield-alt text-primary mr-1"></i> Modo Solo Lectura
                    </span>
                </div>
                <h2 class="font-weight-800 text-white mb-1" style="font-size: 1.45rem; letter-spacing: -0.02em;">
                    Red Integrada de Servicios de Salud (RIISS)
                </h2>
                <div class="text-light small" style="opacity: 0.95; line-height: 1.4;">
                    Catálogo Nacional de Establecimientos, Cartera de Servicios Médicos y Vademécum de Medicamentos.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenedor Principal -->
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">

    <!-- KPI Cards (Grid 2x2 en móvil / 4 columnas en desktop) -->
    <div class="row mb-3 mb-md-4">
        <div class="col-6 col-lg-3 mb-2 mb-lg-0 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Establecimientos</div>
                    <div class="stat-val">{{ $totalEstablecimientos }}</div>
                    <div class="d-none d-md-block" style="font-size: 0.72rem; color: #0284c7; font-weight: 600;">Red Nacional Activa</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-2 mb-lg-0 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #dcfce7; color: #16a34a;">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Con Vademécum</div>
                    <div class="stat-val text-success">{{ $conMedicamentosCount }}</div>
                    <div class="d-none d-md-block" style="font-size: 0.72rem; color: #16a34a; font-weight: 600;">Centros cargados</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #fef3c7; color: #d97706;">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Departamentos</div>
                    <div class="stat-val text-warning">{{ count($departamentos) }}</div>
                    <div class="d-none d-md-block" style="font-size: 0.72rem; color: #d97706; font-weight: 600;">Regiones Sanitarias</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #f1f5f9; color: #475569;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Vigencia Enlace</div>
                    <div class="stat-val text-dark" style="font-size: 1rem;">{{ $tokenRecord->tiempo_restante_texto }}</div>
                    <div class="d-none d-md-block small text-muted" style="font-size: 0.7rem;">Expira: {{ $tokenRecord->expira_en->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Maestra de Establecimientos -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-white border-bottom p-3">
            <div class="row align-items-center">
                <div class="col-lg-4 mb-2 mb-lg-0">
                    <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.1rem;">
                        <i class="fas fa-list-alt text-primary mr-2"></i> Directorio de Establecimientos
                    </h5>
                    <small class="text-muted d-none d-sm-inline">Auditoría de vademécum, cartera médica y georreferencia.</small>
                </div>
                <!-- Filtros en vivo con Select2 -->
                <div class="col-sm-6 col-lg-2 mb-2 mb-lg-0">
                    <select id="filtroDeptoGlobal" class="form-control form-control-sm" style="width: 100%;">
                        <option value="">Deptos ({{ count($departamentos) }})</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto }}">{{ $depto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2 mb-2 mb-lg-0">
                    <select id="filtroComplejidadGlobal" class="form-control form-control-sm" style="width: 100%;">
                        <option value="">Complejidades</option>
                        @foreach($complejidades as $comp)
                            <option value="{{ $comp }}">{{ $comp }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2 mb-2 mb-lg-0">
                    <select id="filtroMedsGlobal" class="form-control form-control-sm" style="width: 100%;">
                        <option value="">Medicamentos</option>
                        <option value="con">Con Vademécum</option>
                        <option value="sin">Sin Vademécum</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <select id="filtroCronicosGlobal" class="form-control form-control-sm" style="width: 100%;">
                        <option value="">Patologías Crónicas</option>
                        <option value="farmacia">💙 Farmacia Crónicos</option>
                        <option value="empadronamiento">🩺 Empadronamiento SIH</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
                <table id="tblGlobalEstablecimientos" class="table table-hover table-striped w-100" style="font-size: 0.88rem;">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 4%; text-align: center;">#</th>
                            <th style="width: 34%;">Establecimiento</th>
                            <th style="width: 20%;">Ubicación</th>
                            <th style="width: 18%;">Complejidad</th>
                            <th style="width: 12%; text-align: center;">Vademécum</th>
                            <th style="width: 12%; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($establecimientos as $idx => $e)
                            @php
                                $medCount = $e->medicamentos_count ?? 0;
                                $espCount = $e->especialidades_count ?? 0;
                            @endphp
                            <tr data-depto="{{ strtolower($e->departamento ?? '') }}" data-comp="{{ strtolower($e->complejidad ?? '') }}" data-has-meds="{{ $medCount > 0 ? 'con' : 'sin' }}" data-farmacia-cronicos="{{ $e->habilita_farmacia_cronicos ? '1' : '0' }}" data-empadronamiento-cronicos="{{ $e->habilita_empadronamiento_cronicos ? '1' : '0' }}">
                                <td class="text-center font-weight-bold text-muted" style="font-size: 0.8rem;">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.92rem; line-height: 1.3;">{{ $e->nombre_oficial }}</div>
                                    <div class="d-flex align-items-center mt-1 flex-wrap" style="gap: 4px;">
                                        <span class="badge badge-light border text-muted" style="font-family: monospace; font-size: 0.72rem;">ID: {{ $e->id_establecimiento }}</span>
                                        @if($e->tipo_est)
                                            <span class="badge badge-secondary" style="font-size: 0.7rem;">{{ $e->tipo_est }}</span>
                                        @endif
                                        @if($e->habilita_farmacia_cronicos)
                                            <span class="badge badge-primary px-1 py-0" style="font-size: 0.68rem; background-color: #2563eb;" title="Farmacia Crónicos Habilitada">
                                                <i class="fas fa-prescription-bottle-alt"></i> Crónicos
                                            </span>
                                        @endif
                                        @if($e->habilita_empadronamiento_cronicos)
                                            <span class="badge badge-warning px-1 py-0 text-dark" style="font-size: 0.68rem;" title="Empadronamiento SIH Habilitado">
                                                <i class="fas fa-id-card-alt"></i> SIH
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-600 text-dark small"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $e->departamento ?? 'N/A' }}</div>
                                    @if($e->microred)
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Microred: {{ $e->microred }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($e->complejidad)
                                        <span class="badge badge-info px-2 py-1 font-weight-bold" style="font-size: 0.72rem;">{{ $e->complejidad }}</span>
                                    @else
                                        <span class="text-muted small font-italic">N/A</span>
                                    @endif
                                    @if($e->nivel_atencion)
                                        <div class="small text-muted mt-1" style="font-size: 0.72rem;">Nivel {{ $e->nivel_atencion }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($medCount > 0)
                                        <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 0.78rem; border-radius: 12px;">
                                            💊 {{ $medCount }} meds
                                        </span>
                                        <div class="small text-muted mt-1 d-none d-md-block" style="font-size: 0.72rem;">{{ $espCount }} especialidades</div>
                                    @else
                                        <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.72rem;">
                                            💊 0 meds
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 4px;">
                                        <a href="{{ route('riiss.portal-auditor.show', ['token' => $tokenRecord->token, 'est' => $e->id_establecimiento]) }}" class="btn-inspect" title="Analizar Vademécum y Ficha Técnica">
                                            <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Analizar</span>
                                        </a>
                                        @if($medCount > 0)
                                            <a href="{{ route('riiss.portal-auditor.pdf', ['token' => $tokenRecord->token, 'est_id' => $e->id_establecimiento, 'tipo' => 'consolidado']) }}" target="_blank" class="circle-btn-pdf" title="Descargar Planilla Auditoría PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="bg-white border-top py-3 mt-4 text-center text-muted small">
    <div class="container-fluid px-2">
        Instituto de Previsión Social (IPS) • Dirección de Planificación / Redes Integradas e Integrales de Servicios de Salud (RIISS)
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('#filtroDeptoGlobal').select2({
        theme: 'bootstrap4',
        placeholder: "Todos los Deptos",
        allowClear: true,
        width: '100%'
    });

    $('#filtroComplejidadGlobal').select2({
        theme: 'bootstrap4',
        placeholder: "Todas las Complejidades",
        allowClear: true,
        width: '100%'
    });

    $('#filtroMedsGlobal').select2({
        theme: 'bootstrap4',
        placeholder: "Todos los Medicamentos",
        allowClear: true,
        width: '100%'
    });

    $('#filtroCronicosGlobal').select2({
        theme: 'bootstrap4',
        placeholder: "Patologías Crónicas",
        allowClear: true,
        width: '100%'
    });

    var table = $('#tblGlobalEstablecimientos').DataTable({
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        language: {
            search: "🔍 Buscar:",
            lengthMenu: "Mostrar _MENU_ centros",
            zeroRecords: "No se encontraron establecimientos",
            info: "Mostrando _START_ a _END_ de _TOTAL_ centros",
            infoEmpty: "0 establecimientos",
            infoFiltered: "(de _MAX_ totales)",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        },
        responsive: true
    });

    // Filtros custom
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var row = $(table.row(dataIndex).node());
        var depto = ($('#filtroDeptoGlobal').val() || '').toLowerCase();
        var comp = ($('#filtroComplejidadGlobal').val() || '').toLowerCase();
        var meds = $('#filtroMedsGlobal').val() || '';
        var cron = $('#filtroCronicosGlobal').val() || '';

        var rowDepto = row.attr('data-depto') || '';
        var rowComp = row.attr('data-comp') || '';
        var rowMeds = row.attr('data-has-meds') || '';
        var rowFarmacia = row.attr('data-farmacia-cronicos') || '0';
        var rowEmpadronamiento = row.attr('data-empadronamiento-cronicos') || '0';

        if (depto && rowDepto.indexOf(depto) === -1) return false;
        if (comp && rowComp.indexOf(comp) === -1) return false;
        if (meds && rowMeds !== meds) return false;
        if (cron === 'farmacia' && rowFarmacia !== '1') return false;
        if (cron === 'empadronamiento' && rowEmpadronamiento !== '1') return false;

        return true;
    });

    $('#filtroDeptoGlobal, #filtroComplejidadGlobal, #filtroMedsGlobal, #filtroCronicosGlobal').on('change', function() {
        table.draw();
    });

    // Heartbeat ping cada 45 segundos para detectar auditor en línea
    function sendHeartbeatPing() {
        $.post('{{ route("riiss.portal-auditor.ping", ["token" => $tokenRecord->token]) }}', {
            _token: '{{ csrf_token() }}'
        });
    }
    setInterval(sendHeartbeatPing, 45000);
});
</script>

</body>
</html>
