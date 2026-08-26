<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Solicitud de Ajuste de Estructura Organizacional — SIPLAN</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f8fafc;--surface:#ffffff;--border:#e2e8f0;
  --primary:#0284c7;--primary-50:#f0f9ff;--primary-100:#e0f2fe;--primary-700:#0369a1;
  --amber:#d97706;--amber-50:#fffbeb;--amber-100:#fef3c7;
  --green:#059669;--green-50:#ecfdf5;
  --text:#0f172a;--text-secondary:#475569;--muted:#94a3b8;
  --radius:16px;--radius-sm:10px;--radius-xs:6px;
  --shadow-sm:0 1px 3px rgba(0,0,0,.05);
  --shadow-md:0 4px 20px rgba(0,0,0,.08);
  --shadow-lg:0 12px 40px rgba(0,0,0,.12);
}
html{scroll-behavior:smooth}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:13.5px;line-height:1.6;min-height:100vh}

/* Topbar */
.topbar{position:sticky;top:0;z-index:300;background:rgba(255,255,255,.92);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);height:60px;display:flex;align-items:center;padding:0 clamp(16px,4vw,40px);gap:14px}
.topbar-logo{width:38px;height:38px;border-radius:var(--radius-xs);background:linear-gradient(135deg,#0284c7,#2563eb);display:grid;place-items:center;color:#fff;font-weight:900;font-size:12px;flex-shrink:0;box-shadow:0 4px 10px rgba(2,132,199,.25)}
.topbar-title{font-size:14px;font-weight:800;color:var(--text);letter-spacing:-.2px}
.topbar-sub{font-size:11px;color:var(--muted)}
.status-badge{margin-left:auto;display:flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 14px;border-radius:100px;background:var(--primary-50);color:var(--primary-700);border:1px solid var(--primary-100)}

/* Header Banner Oficial IPS */
.ips-header-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:24px;box-shadow:var(--shadow-sm);border-top:4px solid #0284c7}
.ips-brand{display:flex;align-items:center;gap:18px;border-bottom:1px solid var(--border);padding-bottom:18px;margin-bottom:18px}
.ips-logo-img{height:54px;width:auto;object-fit:contain}
.ips-header-title{font-size:1.25rem;font-weight:900;color:#1e293b;text-transform:uppercase;letter-spacing:.5px}
.ips-header-sub{font-size:12px;color:var(--text-secondary);font-weight:600}

/* Contenedor */
.page-wrap{max-width:1100px;margin:0 auto;padding:24px clamp(14px,3vw,30px)}

/* Formulario Card */
.form-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:28px}
.form-card-header{padding:18px 24px;background:#f8fafc;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.form-card-header h2{font-size:15px;font-weight:800;color:var(--text);display:flex;align-items:center;gap:8px}
.form-card-body{padding:24px}

/* Campos */
.field{margin-bottom:18px}
.field label{display:block;font-size:12px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;text-transform:uppercase;letter-spacing:.3px}
.field label .req{color:#dc2626;margin-left:2px}
.field input,.field textarea,.field select{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:var(--radius-xs);font-family:inherit;font-size:13px;color:var(--text);background:var(--surface);transition:all .15s}
.field input:focus,.field textarea:focus,.field select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(2,132,199,.12);outline:none}
.field textarea{resize:vertical;min-height:85px}
.field-hint{font-size:11px;color:var(--muted);margin-top:4px}

/* Grid */
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px}
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
@media(max-width:768px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr}}

/* Alerta PEI Requisito Obligatorio */
.alert-pei{background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:1.5px solid #7dd3fc;border-radius:var(--radius-sm);padding:18px;margin-bottom:24px;display:flex;align-items:flex-start;gap:14px}
.alert-pei-icon{width:36px;height:36px;border-radius:50%;background:#0284c7;color:#fff;display:grid;place-items:center;font-size:16px;flex-shrink:0}
.alert-pei h3{font-size:14px;font-weight:800;color:#0369a1;margin-bottom:3px}
.alert-pei p{font-size:12px;color:#334155;line-height:1.5}

/* Tabla dinámica de renglones */
.table-renglones{width:100%;border-collapse:collapse;margin-top:12px;font-size:12.5px}
.table-renglones th{background:#f1f5f9;color:#334155;font-weight:800;text-transform:uppercase;font-size:11px;letter-spacing:.3px;padding:12px 10px;border:1px solid #cbd5e1;text-align:left}
.table-renglones td{padding:10px 8px;border:1px solid var(--border);vertical-align:top;background:#fff}
.table-renglones tbody tr:hover td{background:#fafafa}
.table-renglones input,.table-renglones select,.table-renglones textarea{width:100%;padding:7px 10px;border:1px solid #cbd5e1;border-radius:4px;font-size:12px;background:#fff}
.table-renglones textarea{min-height:55px;resize:vertical}
.btn-del-row{background:#fee2e2;color:#dc2626;border:none;border-radius:4px;width:30px;height:30px;display:grid;place-items:center;cursor:pointer;transition:all .15s}
.btn-del-row:hover{background:#fca5a5;transform:scale(1.05)}
.btn-add-row{background:#f0fdf4;color:#16a34a;border:1.5px dashed #86efac;border-radius:var(--radius-xs);padding:10px 16px;font-weight:700;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;margin-top:12px;transition:all .15s}
.btn-add-row:hover{background:#dcfce7;border-color:#4ade80}

/* Botón Submit */
.btn-submit-main{background:linear-gradient(135deg,#0284c7,#0369a1);color:#fff;border:none;border-radius:var(--radius-xs);padding:14px 34px;font-size:14px;font-weight:800;letter-spacing:.3px;cursor:pointer;box-shadow:0 6px 18px rgba(2,132,199,.3);transition:all .2s;display:inline-flex;align-items:center;gap:10px}
.btn-submit-main:hover{background:linear-gradient(135deg,#0369a1,#075985);transform:translateY(-2px);box-shadow:0 10px 24px rgba(2,132,199,.4)}

/* Select2 Custom */
.select2-container--default .select2-selection--single{height:42px;border:1.5px solid var(--border);border-radius:var(--radius-xs);display:flex;align-items:center;padding:0 12px}
.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:1;font-size:13px;color:var(--text)}
.select2-container--default .select2-selection--single .select2-selection__arrow{height:40px}
</style>
</head>
<body>

<header class="topbar">
  <div class="topbar-logo">IPS</div>
  <div>
    <div class="topbar-title">SIPLAN — Gestión de Estructura Organizacional</div>
    <div class="topbar-sub">Instituto de Previsión Social</div>
  </div>
  <div class="status-badge">
    <i class="fa fa-sitemap mr-1"></i> Formulario Oficial
  </div>
</header>

<main class="page-wrap">

  {{-- Header Institucional IPS --}}
  <div class="ips-header-card">
    <div class="ips-brand">
      <img src="{{ asset('material/img/favicon.png') }}" alt="IPS Logo" class="ips-logo-img" onerror="this.style.display='none'">
      <div>
        <h1 class="ips-header-title">SOLICITUD DE AJUSTE DE LA ESTRUCTURA ORGANIZACIONAL</h1>
        <div class="ips-header-sub">DIRECCIÓN DE PLANIFICACIÓN — INSTITUTO DE PREVISIÓN SOCIAL</div>
      </div>
    </div>
    <div class="row text-muted small">
      <div class="col-md-6">
        <strong>Plan Institucional:</strong> {{ $perfil->name }}
      </div>
      <div class="col-md-6 text-md-right">
        <strong>Fecha:</strong> {{ now()->format('d/m/Y') }}
      </div>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#dcfce7;border:1.5px solid #86efac;color:#166534;padding:16px 20px;border-radius:var(--radius-sm);margin-bottom:24px;display:flex;align-items:center;gap:12px;">
      <i class="fa fa-check-circle fa-2x text-success"></i>
      <div>
        <h4 style="font-weight:800;margin-bottom:2px">¡Solicitud Enviada con Éxito!</h4>
        <p style="margin:0;font-size:13px">{{ session('success') }}</p>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fee2e2;border:1.5px solid #fca5a5;color:#991b1b;padding:16px 20px;border-radius:var(--radius-sm);margin-bottom:24px;">
      <h4 style="font-weight:800;margin-bottom:4px"><i class="fa fa-exclamation-triangle mr-2"></i>Por favor verifique los siguientes errores:</h4>
      <ul style="margin-left:20px;font-size:12.5px;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Banner de Requisito PEI --}}
  <div class="alert-pei">
    <div class="alert-pei-icon"><i class="fa fa-bullseye"></i></div>
    <div>
      <h3>Alineación Estratégica Obligatoria (PEI)</h3>
      <p>Todo ajuste o reorganización de dependencias (creación, fusión, cambio de denominación o supresión) debe estar estrictamente vinculado y fundamentado en una <strong>Acción u Objetivo Estratégico</strong> del Plan Estratégico Institucional vigente para ser evaluado por la Dirección de Organización y Calidad.</p>
    </div>
  </div>

  <form action="{{ route('proyectos.solicitar.ajuste-estructura.store', $perfil->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Sección 1: Datos de Radicación y Dependencia Solicitante --}}
    <div class="form-card">
      <div class="form-card-header">
        <h2><i class="fa fa-building text-primary"></i> 1. Dependencia Solicitante & Vinculación Estratégica</h2>
        <span class="badge badge-light border text-muted">Datos Generales</span>
      </div>
      <div class="form-card-body">
        <div class="grid-2">
          <div class="field">
            <label>Dependencia Solicitante (Gerencia o Dirección) <span class="req">*</span></label>
            <select name="dependencia_solicitante_id" id="dependencia_solicitante_id" class="select2" style="width:100%">
              <option value="">-- Seleccione del Organigrama --</option>
              @foreach($dependencias as $dep)
                <option value="{{ $dep->id }}" {{ old('dependencia_solicitante_id') == $dep->id ? 'selected' : '' }}>
                  {{ $dep->dependency }}
                </option>
                @foreach($dep->children ?? [] as $sub)
                  <option value="{{ $sub->id }}" {{ old('dependencia_solicitante_id') == $sub->id ? 'selected' : '' }}>
                    &nbsp;&nbsp;↳ {{ $sub->dependency }}
                  </option>
                @endforeach
              @endforeach
            </select>
            <div class="field-hint">O escriba la denominación exacta abajo si no figura en el selector.</div>
          </div>
          <div class="field">
            <label>Otra Dependencia / Denominación Texto</label>
            <input type="text" name="dependencia_solicitante_texto" value="{{ old('dependencia_solicitante_texto') }}" placeholder="Ej: Gerencia de Salud / Dirección de Hospitales">
          </div>
        </div>

        <div class="field mt-2">
          <label>Acción / Objetivo del Plan Estratégico (PEI) que respalda la solicitud <span class="req">*</span></label>
          <select name="pei_profile_id" id="pei_profile_id" class="select2" style="width:100%" required>
            <option value="">-- Buscar y seleccionar Acción Estratégica PEI --</option>
          </select>
          <div class="field-hint">Escriba para buscar por palabra clave dentro del catálogo del PEI.</div>
        </div>
      </div>
    </div>

    {{-- Sección 2: Datos del Funcionario Solicitante / Responsable --}}
    <div class="form-card">
      <div class="form-card-header">
        <h2><i class="fa fa-user-tie text-primary"></i> 2. Funcionario Solicitante / Contacto</h2>
      </div>
      <div class="form-card-body">
        <div class="grid-4">
          <div class="field">
            <label>Nombre y Apellido <span class="req">*</span></label>
            <input type="text" name="solicitante_nombre" value="{{ old('solicitante_nombre', Auth::user()?->name) }}" required placeholder="Ej: Dr. Roberto Benítez">
          </div>
          <div class="field">
            <label>Cargo / Función</label>
            <input type="text" name="solicitante_cargo" value="{{ old('solicitante_cargo') }}" placeholder="Ej: Director de Hospital">
          </div>
          <div class="field">
            <label>Correo Electrónico <span class="req">*</span></label>
            <input type="email" name="solicitante_email" value="{{ old('solicitante_email', Auth::user()?->email) }}" required placeholder="correo@ips.gov.py">
          </div>
          <div class="field">
            <label>Teléfono / Interno</label>
            <input type="text" name="solicitante_telefono" value="{{ old('solicitante_telefono') }}" placeholder="021-xxxxxx / Int. 123">
          </div>
        </div>
      </div>
    </div>

    {{-- Sección 3: Tabla Oficial de Ajuste Estructural (Fiel al Formulario IPS) --}}
    <div class="form-card">
      <div class="form-card-header">
        <h2><i class="fa fa-table text-primary"></i> 3. Detalle de Reorganización Estructural</h2>
        <span class="badge badge-primary">Formato Oficial IPS</span>
      </div>
      <div class="form-card-body p-3">
        <p class="text-muted small mb-2">
          Indique en cada renglón los movimientos solicitados. Puede agregar varios cambios dentro de la misma solicitud utilizando el botón <strong>"+ Agregar Renglón"</strong>.
        </p>

        <div style="overflow-x:auto;">
          <table class="table-renglones" id="tablaRenglones">
            <thead>
              <tr>
                <th style="width:160px;">Indica tipo de reorganización <span class="req">*</span></th>
                <th style="width:170px;">Denominación actual (si modifica)</th>
                <th style="width:180px;">Denominación de dependencia propuesta <span class="req">*</span></th>
                <th style="width:200px;">Objetivo de la dependencia propuesta <span class="req">*</span></th>
                <th style="width:220px;">Descripción de reorganización (Motivos) <span class="req">*</span></th>
                <th style="width:150px;">Observaciones (Opcional)</th>
                <th style="width:40px;text-align:center;"></th>
              </tr>
            </thead>
            <tbody id="tbodyRenglones">
              {{-- Fila 1 inicial --}}
              <tr class="fila-renglon">
                <td>
                  <select name="items[0][tipo_reorganizacion]" required>
                    @foreach($tiposReorganizacion as $val => $lbl)
                      <option value="{{ $val }}">{{ $lbl }}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="text" name="items[0][denominacion_actual]" placeholder="Ej: Sección Archivo Médico">
                </td>
                <td>
                  <input type="text" name="items[0][denominacion_propuesta]" required placeholder="Ej: Dpto. de Gestión Documental">
                </td>
                <td>
                  <textarea name="items[0][objetivo_dependencia_propuesta]" required placeholder="Describa el objetivo institucional propuesto..."></textarea>
                </td>
                <td>
                  <textarea name="items[0][descripcion_motivos]" required placeholder="Justifique los motivos de la reorganización y necesidad..."></textarea>
                </td>
                <td>
                  <input type="text" name="items[0][observaciones]" placeholder="Notas adicionales">
                </td>
                <td style="text-align:center;">
                  <button type="button" class="btn-del-row" onclick="eliminarFila(this)" title="Eliminar renglón"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <button type="button" class="btn-add-row" onclick="agregarFila()">
          <i class="fa fa-plus-circle"></i> + Agregar Renglón de Reorganización
        </button>
      </div>
    </div>

    {{-- Sección 4: Fundamentación General y Adjuntos --}}
    <div class="form-card">
      <div class="form-card-header">
        <h2><i class="fa fa-paperclip text-primary"></i> 4. Fundamentación General & Documentación Respaldatoria</h2>
      </div>
      <div class="form-card-body">
        <div class="field">
          <label>Fundamentación General / Justificación de Impacto</label>
          <textarea name="fundamentacion_general" rows="3" placeholder="Exponga el impacto institucional, beneficios esperados y adecuación normativa de la reestructuración planteada...">{{ old('fundamentacion_general') }}</textarea>
        </div>

        <div class="grid-2">
          <div class="field">
            <label>Dictamen Técnico / Nota de Solicitud (PDF, DOCX)</label>
            <input type="file" name="documento_respaldo" accept=".pdf,.docx,.zip">
            <div class="field-hint">Adjunte nota formal firmada por la Gerencia o Dirección solicitante (Máx. 10MB).</div>
          </div>
          <div class="field">
            <label>Organigrama Gráfico Propuesto (PDF, PNG, JPG)</label>
            <input type="file" name="organigrama_adjunto" accept=".pdf,.png,.jpg,.jpeg,.zip">
            <div class="field-hint">Esquema visual o árbol jerárquico propuesto para la nueva estructura.</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Footer Submit --}}
    <div class="text-right pb-5">
      <button type="submit" class="btn-submit-main" id="btnEnviarSolicitud">
        <i class="fa fa-paper-plane"></i> ENVIAR SOLICITUD & GENERAR CÓDIGO QR
      </button>
    </div>

  </form>

</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
var rowIndex = 1;
var tiposReorgOptions = @json($tiposReorganizacion);

$(document).ready(function() {
    $('#dependencia_solicitante_id').select2({
        placeholder: "-- Seleccione del Organigrama --",
        allowClear: true
    });

    $('#pei_profile_id').select2({
        placeholder: "-- Buscar y seleccionar Acción Estratégica PEI --",
        allowClear: true,
        ajax: {
            url: "{{ route('proyectos.solicitar.acciones', $perfil->id) }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        }
    });
});

function agregarFila() {
    var optionsHtml = '';
    $.each(tiposReorgOptions, function(key, label) {
        optionsHtml += '<option value="' + key + '">' + label + '</option>';
    });

    var tr = `
      <tr class="fila-renglon">
        <td>
          <select name="items[${rowIndex}][tipo_reorganizacion]" required>
            ${optionsHtml}
          </select>
        </td>
        <td>
          <input type="text" name="items[${rowIndex}][denominacion_actual]" placeholder="Ej: Sección Archivo Médico">
        </td>
        <td>
          <input type="text" name="items[${rowIndex}][denominacion_propuesta]" required placeholder="Ej: Dpto. de Gestión Documental">
        </td>
        <td>
          <textarea name="items[${rowIndex}][objetivo_dependencia_propuesta]" required placeholder="Describa el objetivo institucional propuesto..."></textarea>
        </td>
        <td>
          <textarea name="items[${rowIndex}][descripcion_motivos]" required placeholder="Justifique los motivos de la reorganización..."></textarea>
        </td>
        <td>
          <input type="text" name="items[${rowIndex}][observaciones]" placeholder="Notas adicionales">
        </td>
        <td style="text-align:center;">
          <button type="button" class="btn-del-row" onclick="eliminarFila(this)" title="Eliminar renglón"><i class="fa fa-trash"></i></button>
        </td>
      </tr>
    `;

    $('#tbodyRenglones').append(tr);
    rowIndex++;
}

function eliminarFila(btn) {
    if ($('#tbodyRenglones tr').length > 1) {
        $(btn).closest('tr').remove();
    } else {
        alert('Debe conservar al menos un renglón de reorganización en la solicitud.');
    }
}
</script>
</body>
</html>
