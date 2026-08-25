<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Comprobante de Solicitud {{ $solicitud->codigo }} — SIPLAN IPS</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f8fafc;--surface:#ffffff;--border:#e2e8f0;
  --primary:#0284c7;--primary-50:#f0f9ff;--primary-100:#e0f2fe;--primary-700:#0369a1;
  --text:#0f172a;--text-secondary:#475569;--muted:#94a3b8;
  --radius:16px;--radius-sm:10px;--radius-xs:6px;
  --shadow-md:0 4px 20px rgba(0,0,0,.08);
}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:13.5px;line-height:1.6;min-height:100vh}

.topbar{position:sticky;top:0;z-index:300;background:rgba(255,255,255,.92);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);height:60px;display:flex;align-items:center;padding:0 clamp(16px,4vw,40px);gap:14px}
.topbar-logo{width:36px;height:36px;border-radius:var(--radius-xs);background:linear-gradient(135deg,#0284c7,#2563eb);display:grid;place-items:center;color:#fff;font-weight:900;font-size:11px}

.page-wrap{max-width:960px;margin:0 auto;padding:24px clamp(14px,3vw,30px)}

.card-voucher{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:24px}
.voucher-header{background:#0284c7;color:#fff;padding:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.voucher-body{padding:28px}

.badge-estado{padding:6px 16px;border-radius:100px;font-weight:800;font-size:12px;display:inline-flex;align-items:center;gap:6px;text-transform:uppercase}
.bg-solicitud{background:#e0f2fe;color:#0369a1}
.bg-en_analisis{background:#fef3c7;color:#b45309}
.bg-observado{background:#ffedd5;color:#c2410c}
.bg-aprobado{background:#dcfce7;color:#15803d}
.bg-rechazado{background:#fee2e2;color:#b91c1c}

.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:28px;background:#f8fafc;padding:20px;border-radius:var(--radius-sm);border:1px solid var(--border)}
.info-label{font-size:11px;font-weight:800;text-transform:uppercase;color:var(--muted);letter-spacing:.3px;margin-bottom:2px}
.info-val{font-size:13.5px;font-weight:700;color:var(--text)}

.table-renglones{width:100%;border-collapse:collapse;margin-top:14px;font-size:12.5px}
.table-renglones th{background:#f1f5f9;color:#334155;font-weight:800;text-transform:uppercase;font-size:11px;padding:10px 12px;border:1px solid #cbd5e1;text-align:left}
.table-renglones td{padding:10px 12px;border:1px solid var(--border);vertical-align:top;background:#fff}

.qr-box{text-align:center;padding:20px;border:1px dashed var(--border);border-radius:var(--radius-sm);background:#fff;display:inline-block}

.btn-action{padding:10px 20px;border-radius:var(--radius-xs);font-weight:700;font-size:13px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;text-decoration:none}
.btn-print{background:#0f172a;color:#fff}
.btn-print:hover{background:#334155}
.btn-new{background:#0284c7;color:#fff}
.btn-new:hover{background:#0369a1}

@media print{
  .topbar,.btn-action,.no-print{display:none!important}
  body{background:#fff}
  .card-voucher{box-shadow:none;border:1px solid #000}
  .voucher-header{background:#0284c7!important;-webkit-print-color-adjust:exact}
}
</style>
</head>
<body>

<header class="topbar no-print">
  <div class="topbar-logo">IPS</div>
  <div>
    <div style="font-size:14px;font-weight:800">SIPLAN — Comprobante Oficial de Solicitud</div>
    <div style="font-size:11px;color:var(--muted)">Sistema de Planificación Institucional</div>
  </div>
  <div style="margin-left:auto;display:flex;gap:10px;">
    <button onclick="window.print()" class="btn-action btn-print"><i class="fa fa-print"></i> Imprimir / PDF</button>
  </div>
</header>

<main class="page-wrap">

  @if(session('success'))
    <div class="no-print" style="background:#dcfce7;border:1.5px solid #86efac;color:#166534;padding:16px 20px;border-radius:var(--radius-sm);margin-bottom:20px;display:flex;align-items:center;gap:12px;">
      <i class="fa fa-check-circle fa-2x text-success"></i>
      <div>
        <h4 style="font-weight:800;margin-bottom:2px">¡Solicitud Radicada Correctamente!</h4>
        <p style="margin:0;font-size:13px">{{ session('success') }}</p>
      </div>
    </div>
  @endif

  <div class="card-voucher">
    {{-- Cabecera del Comprobante --}}
    <div class="voucher-header">
      <div>
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;opacity:0.9">INSTITUTO DE PREVISIÓN SOCIAL</div>
        <h1 style="font-size:1.5rem;font-weight:900;letter-spacing:-.5px;margin-top:2px">Solicitud de Ajuste de Estructura Organizacional</h1>
        <div style="font-size:12px;opacity:0.9">Radicación Oficial: <strong>{{ $solicitud->codigo }}</strong></div>
      </div>
      <div>
        <span class="badge-estado bg-{{ $solicitud->estado }}">
          <i class="fa fa-circle" style="font-size:8px"></i> {{ \App\Models\Estructura\SolicitudAjusteEstructura::estadoLabel($solicitud->estado) }}
        </span>
      </div>
    </div>

    <div class="voucher-body">
      {{-- Información General en Grid --}}
      <div class="info-grid">
        <div>
          <div class="info-label">Código de Seguimiento</div>
          <div class="info-val" style="color:#0284c7">{{ $solicitud->codigo }}</div>
        </div>
        <div>
          <div class="info-label">Fecha de Solicitud</div>
          <div class="info-val">{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</div>
        </div>
        <div>
          <div class="info-label">Dependencia Solicitante</div>
          <div class="info-val">{{ $solicitud->dependenciaSolicitante?->dependency ?: ($solicitud->dependencia_solicitante_texto ?: 'No especificada') }}</div>
        </div>
        <div>
          <div class="info-label">Funcionario Solicitante</div>
          <div class="info-val">{{ $solicitud->solicitante_nombre }} {{ $solicitud->solicitante_cargo ? '('.$solicitud->solicitante_cargo.')' : '' }}</div>
        </div>
        <div style="grid-column: 1 / -1">
          <div class="info-label">Alineación Estratégica con el Plan Institucional (PEI)</div>
          <div class="info-val" style="color:#15803d">
            <i class="fa fa-bullseye mr-1"></i> {{ $solicitud->peiProfile ? strip_tags($solicitud->peiProfile->name) : 'Sin vinculación directa' }}
          </div>
        </div>
      </div>

      {{-- Tabla de Renglones de Reorganización --}}
      <h3 style="font-size:15px;font-weight:800;color:#1e293b;margin-bottom:8px">
        <i class="fa fa-sitemap text-primary mr-1"></i> Modificaciones Estructurales Solicitadas ({{ $solicitud->items->count() }})
      </h3>
      <div style="overflow-x:auto;">
        <table class="table-renglones">
          <thead>
            <tr>
              <th>Tipo de Reorganización</th>
              <th>Denominación Actual</th>
              <th>Denominación Propuesta</th>
              <th>Objetivo Propuesto</th>
              <th>Motivos de la Reorganización</th>
              <th>Observaciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($solicitud->items as $it)
              <tr>
                <td><span class="badge badge-light border font-weight-bold">{{ $it->tipo_label }}</span></td>
                <td>{{ $it->denominacion_actual ?: '—' }}</td>
                <td><strong style="color:#0284c7">{{ $it->denominacion_propuesta }}</strong></td>
                <td>{{ $it->objetivo_dependencia_propuesta }}</td>
                <td>{{ $it->descripcion_motivos }}</td>
                <td>{{ $it->observaciones ?: '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Fundamentación General --}}
      @if($solicitud->fundamentacion_general)
        <div style="margin-top:24px;background:#f8fafc;padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border)">
          <div class="info-label">Fundamentación General / Justificación de Impacto</div>
          <p style="margin:0;font-size:13px;color:#334155">{{ $solicitud->fundamentacion_general }}</p>
        </div>
      @endif

      {{-- Dictamen Técnico (si ya fue evaluado) --}}
      @if($solicitud->dictamen_tecnico)
        <div style="margin-top:20px;background:#f0fdf4;border:1.5px solid #86efac;padding:18px;border-radius:var(--radius-sm)">
          <div style="font-size:11px;font-weight:800;text-transform:uppercase;color:#166534;margin-bottom:4px">
            <i class="fa fa-clipboard-check mr-1"></i> Dictamen Técnico de la Dirección de Planificación
          </div>
          <p style="margin:0;font-size:13px;color:#14532d;font-weight:600">{{ $solicitud->dictamen_tecnico }}</p>
          <div style="font-size:11px;color:#15803d;margin-top:6px">
            Evaluado por: <strong>{{ $solicitud->analista?->name ?: 'Dirección de Planificación' }}</strong>
            el {{ $solicitud->fecha_dictamen?->format('d/m/Y H:i') }}
          </div>
        </div>
      @endif

      {{-- QR de Verificación --}}
      <div style="margin-top:32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;border-top:1px dashed var(--border);padding-top:24px">
        <div>
          <h4 style="font-weight:800;font-size:14px;color:#1e293b;margin-bottom:4px">Código QR de Verificación Oficial</h4>
          <p style="margin:0;font-size:12px;color:var(--text-secondary);max-width:420px">
            Escaneá este código QR con cualquier dispositivo móvil para verificar la autenticidad y el estado en tiempo real de este expediente.
          </p>
          <div style="margin-top:12px;font-family:monospace;font-size:11px;color:var(--muted)">
            URL: {{ $solicitud->url_qr }}
          </div>
        </div>
        <div class="qr-box">
          {!! $qrSvg !!}
          <div style="font-size:10px;font-weight:800;color:var(--muted);margin-top:6px">{{ $solicitud->codigo }}</div>
        </div>
      </div>

    </div>
  </div>

  <div class="text-center no-print" style="margin-top:20px;">
    <a href="{{ route('proyectos.solicitar.ajuste-estructura.form', $solicitud->pei_profile_id) }}" class="btn-action btn-new">
      <i class="fa fa-plus"></i> Nueva Solicitud de Ajuste Estructural
    </a>
  </div>

</main>

</body>
</html>
