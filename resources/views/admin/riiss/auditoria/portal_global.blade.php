<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal Maestro de Auditoría RIISS - Red Nacional | IPS</title>
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Bootstrap 4.5.2 & DataTables CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --bg-page: #f8fafc;
            --text-dark: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .top-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 0.85rem 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .hero-banner {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            padding: 2.25rem 1.5rem 2rem;
            position: relative;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .btn-inspect {
            background: #0284c7;
            color: white;
            font-weight: 700;
            font-size: 0.82rem;
            border-radius: 6px;
            padding: 0.4rem 0.85rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none !important;
        }
        .btn-inspect:hover {
            background: #0369a1;
            color: white;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.35);
        }

        .circle-btn-pdf {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            border: 1px solid #fca5a5;
            background: #fee2e2;
            color: #b91c1c;
            transition: all 0.2s;
        }
        .circle-btn-pdf:hover {
            background: #fecaca;
            color: #991b1b;
            transform: scale(1.08);
        }
    </style>
</head>
<body>

<!-- Barra Superior -->
<nav class="top-navbar">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <span class="badge badge-primary px-3 py-1 mr-2 text-white font-weight-bold" style="font-size: 0.82rem; background: #0284c7;">
                <i class="fas fa-globe-americas mr-1"></i> AUDITORÍA GLOBAL NACIONAL
            </span>
            <span class="font-weight-bold text-white d-none d-sm-inline" style="font-size: 0.95rem;">
                Instituto de Previsión Social (IPS) • RIISS
            </span>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge badge-warning px-3 py-2 text-dark font-weight-bold" style="border-radius: 20px; font-size: 0.82rem;">
                <i class="fas fa-hourglass-half mr-1"></i> Validez: Quedan {{ $tokenRecord->tiempo_restante_texto }}
            </span>
        </div>
    </div>
</nav>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-2" style="gap: 0.5rem;">
                    <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 0.8rem;">
                        <i class="fas fa-check-circle mr-1"></i> Acceso Master de Análisis
                    </span>
                    <span class="badge badge-light px-3 py-1 font-weight-bold text-dark" style="font-size: 0.8rem;">
                        <i class="fas fa-shield-alt text-primary mr-1"></i> Modo Solo Lectura
                    </span>
                </div>
                <h2 class="font-weight-800 text-white mb-2" style="font-size: 1.85rem; letter-spacing: -0.02em;">
                    Red Integrada e Integral de Servicios de Salud (RIISS)
                </h2>
                <div class="text-light" style="font-size: 0.95rem; opacity: 0.95;">
                    Catálogo Nacional de Establecimientos, Cartera de Servicios Médicos y Vademécum de Medicamentos.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenedor Principal -->
<div class="container-fluid py-4">

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-3" style="background-color: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-hospital"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: #64748b;">Establecimientos</div>
                    <div class="font-weight-bold" style="font-size: 1.5rem; color: #0f172a;">{{ $totalEstablecimientos }}</div>
                    <div style="font-size: 0.75rem; color: #0284c7; font-weight: 600;">Red Nacional Activa</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-3" style="background-color: #dcfce7; color: #16a34a;">
                    <i class="fas fa-pills"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: #64748b;">Con Vademécum</div>
                    <div class="font-weight-bold" style="font-size: 1.5rem; color: #0f172a;">{{ $conMedicamentosCount }}</div>
                    <div style="font-size: 0.75rem; color: #16a34a; font-weight: 600;">Centros con medicamentos</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3 mb-sm-0">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-3" style="background-color: #fef3c7; color: #d97706;">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: #64748b;">Departamentos</div>
                    <div class="font-weight-bold" style="font-size: 1.5rem; color: #0f172a;">{{ count($departamentos) }}</div>
                    <div style="font-size: 0.75rem; color: #d97706; font-weight: 600;">Regiones Sanitarias</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-3" style="background-color: #f1f5f9; color: #475569;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: #64748b;">Vigencia del Enlace</div>
                    <div class="font-weight-bold" style="font-size: 1.15rem; color: #0f172a;">{{ $tokenRecord->tiempo_restante_texto }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">Expira: {{ $tokenRecord->expira_en->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Maestra de Establecimientos -->
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-white border-bottom p-3">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-list-alt text-primary mr-2"></i> Directorio de Establecimientos
                    </h5>
                    <small class="text-muted">Haga clic en cualquier centro para auditar su vademécum, cartera médica y mapa.</small>
                </div>
                <!-- Filtros en vivo -->
                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <select id="filtroDeptoGlobal" class="form-control form-control-sm">
                        <option value="">Todos los Departamentos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto }}">{{ $depto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <select id="filtroComplejidadGlobal" class="form-control form-control-sm">
                        <option value="">Todas las Complejidades</option>
                        @foreach($complejidades as $comp)
                            <option value="{{ $comp }}">{{ $comp }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-md-right">
                    <select id="filtroMedsGlobal" class="form-control form-control-sm">
                        <option value="">Medicamentos (Todos)</option>
                        <option value="con">Con Medicamentos</option>
                        <option value="sin">Sin Medicamentos</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tblGlobalEstablecimientos" class="table table-hover table-striped w-100" style="font-size: 0.88rem;">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 4%; text-align: center;">#</th>
                            <th style="width: 32%;">Establecimiento</th>
                            <th style="width: 20%;">Ubicación / Región</th>
                            <th style="width: 18%;">Complejidad / Nivel</th>
                            <th style="width: 12%; text-align: center;">Vademécum</th>
                            <th style="width: 14%; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($establecimientos as $idx => $e)
                            @php
                                $medCount = $e->medicamentos_count ?? 0;
                                $espCount = $e->especialidades_count ?? 0;
                            @endphp
                            <tr data-depto="{{ strtolower($e->departamento ?? '') }}" data-comp="{{ strtolower($e->complejidad ?? '') }}" data-has-meds="{{ $medCount > 0 ? 'con' : 'sin' }}">
                                <td class="text-center font-weight-bold text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $e->nombre_oficial }}</div>
                                    <div class="d-flex align-items-center mt-1" style="gap: 4px;">
                                        <span class="badge badge-light border text-muted" style="font-family: monospace; font-size: 0.75rem;">ID: {{ $e->id_establecimiento }}</span>
                                        @if($e->tipo_est)
                                            <span class="badge badge-secondary" style="font-size: 0.72rem;">{{ $e->tipo_est }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-600 text-dark"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $e->departamento ?? 'No especificado' }}</div>
                                    @if($e->microred)
                                        <small class="text-muted">Microred: {{ $e->microred }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($e->complejidad)
                                        <span class="badge badge-info px-2 py-1 font-weight-bold">{{ $e->complejidad }}</span>
                                    @else
                                        <span class="text-muted small font-italic">No asignada</span>
                                    @endif
                                    @if($e->nivel_atencion)
                                        <div class="small text-muted mt-1">Nivel {{ $e->nivel_atencion }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($medCount > 0)
                                        <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 0.8rem; border-radius: 12px;">
                                            💊 {{ $medCount }} meds
                                        </span>
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">{{ $espCount }} especialidades</div>
                                    @else
                                        <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.75rem;">
                                            💊 0 meds
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <a href="{{ route('riiss.portal-auditor.show', ['token' => $tokenRecord->token, 'est' => $e->id_establecimiento]) }}" class="btn-inspect" title="Analizar Vademécum y Ficha Técnica">
                                            <i class="fas fa-search-plus"></i> Analizar
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
<footer class="bg-white border-top py-3 mt-5 text-center text-muted small">
    <div class="container-fluid">
        Instituto de Previsión Social (IPS) • Dirección de Planificación / Redes Integradas e Integrales de Servicios de Salud (RIISS)
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#tblGlobalEstablecimientos').DataTable({
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        language: {
            search: "🔍 Buscar por nombre, código o departamento:",
            lengthMenu: "Mostrar _MENU_ centros por página",
            zeroRecords: "No se encontraron establecimientos con ese criterio",
            info: "Mostrando _START_ a _END_ de _TOTAL_ establecimientos",
            infoEmpty: "Mostrando 0 a 0 de 0 establecimientos",
            infoFiltered: "(filtrado de _MAX_ totales)",
            paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
        },
        responsive: true
    });

    // Filtros custom
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var row = $(table.row(dataIndex).node());
        var depto = $('#filtroDeptoGlobal').val().toLowerCase();
        var comp = $('#filtroComplejidadGlobal').val().toLowerCase();
        var meds = $('#filtroMedsGlobal').val();

        var rowDepto = row.attr('data-depto') || '';
        var rowComp = row.attr('data-comp') || '';
        var rowMeds = row.attr('data-has-meds') || '';

        if (depto && rowDepto.indexOf(depto) === -1) return false;
        if (comp && rowComp.indexOf(comp) === -1) return false;
        if (meds && rowMeds !== meds) return false;

        return true;
    });

    $('#filtroDeptoGlobal, #filtroComplejidadGlobal, #filtroMedsGlobal').on('change', function() {
        table.draw();
    });
});
</script>

</body>
</html>
