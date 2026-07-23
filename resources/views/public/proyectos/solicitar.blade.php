<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Solicitud de Proyecto — {{ $perfil->name }}</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f1f5f9;--surface:#ffffff;--border:#e2e8f0;
  --blue:#1d4ed8;--blue-50:#eff6ff;--blue-100:#dbeafe;--blue-700:#1d4ed8;
  --green:#059669;--green-50:#ecfdf5;
  --text:#0f172a;--text-secondary:#475569;--muted:#94a3b8;
  --radius:16px;--radius-sm:10px;--radius-xs:6px;
  --shadow-sm:0 1px 2px rgba(0,0,0,.04);
  --shadow-md:0 4px 16px rgba(0,0,0,.07);
  --shadow-lg:0 12px 40px rgba(0,0,0,.1);
}
html{scroll-behavior:smooth}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.6;min-height:100vh}

@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.15}}
.anim{animation:fadeUp .5s cubic-bezier(.22,1,.36,1) both}
.d1{animation-delay:.06s}.d2{animation-delay:.12s}.d3{animation-delay:.18s}.d4{animation-delay:.24s}

/* Topbar */
.topbar{position:sticky;top:0;z-index:300;background:rgba(255,255,255,.9);backdrop-filter:blur(24px);border-bottom:1px solid var(--border);height:56px;display:flex;align-items:center;padding:0 clamp(16px,4vw,40px);gap:12px}
.topbar-logo{width:34px;height:34px;border-radius:var(--radius-xs);background:linear-gradient(145deg,var(--blue-700),#7c3aed);display:grid;place-items:center;color:#fff;font-weight:900;font-size:10px;letter-spacing:-.5px;flex-shrink:0}
.topbar-title{font-size:13px;font-weight:700;color:var(--text)}
.topbar-sub{font-size:11px;color:var(--muted)}
.status-chip{margin-left:auto;display:flex;align-items:center;gap:6px;font-size:10px;font-weight:600;padding:4px 12px;border-radius:100px;background:var(--green-50);color:var(--green);border:1px solid #a7f3d0}
.status-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:blink 2s infinite}

/* Hero */
.hero{background:var(--surface);border-bottom:1px solid var(--border);padding:clamp(28px,4vw,52px) clamp(16px,4vw,40px);position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;top:-100px;right:-100px;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%);pointer-events:none}
.hero-badge{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--blue);background:var(--blue-50);border:1px solid var(--blue-100);padding:4px 14px;border-radius:100px;margin-bottom:14px}
.hero h1{font-size:clamp(1.5rem,3vw,2.2rem);font-weight:900;color:var(--text);line-height:1.15;letter-spacing:-.5px;margin-bottom:10px}
.hero h1 span{color:var(--blue)}
.hero p{font-size:13px;color:var(--text-secondary);max-width:480px;line-height:1.7}

/* Contenedor */
.page-wrap{max-width:760px;margin:0 auto;padding:clamp(20px,3vw,40px) clamp(16px,4vw,40px)}

/* Card formulario */
.form-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden}
.form-card-header{padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px}
.form-card-icon{width:38px;height:38px;border-radius:var(--radius-xs);background:var(--blue-50);display:grid;place-items:center;color:var(--blue);font-size:16px;flex-shrink:0}
.form-card-header h2{font-size:15px;font-weight:700;color:var(--text)}
.form-card-header p{font-size:12px;color:var(--muted);margin-top:1px}
.form-card-body{padding:24px}

/* Campos */
.field{margin-bottom:20px}
.field label{display:block;font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;letter-spacing:.2px}
.field label .req{color:#dc2626;margin-left:2px}
.field input,.field textarea,.field select{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:var(--radius-xs);font-family:inherit;font-size:13px;color:var(--text);background:var(--surface);transition:border-color .15s,box-shadow .15s;outline:none}
.field input:focus,.field textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(29,78,216,.08)}
.field textarea{resize:vertical;min-height:100px}
.field-hint{font-size:11px;color:var(--muted);margin-top:5px}

/* Grid */
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:560px){.grid-2{grid-template-columns:1fr}}

/* Alerta info */
.info-box{display:flex;gap:12px;align-items:flex-start;background:var(--blue-50);border:1px solid var(--blue-100);border-radius:var(--radius-xs);padding:14px 16px;margin-bottom:24px;font-size:12px;color:var(--text-secondary)}
.info-box i{color:var(--blue);margin-top:1px;flex-shrink:0}

/* Alerta éxito */
.success-box{display:flex;gap:12px;align-items:flex-start;background:var(--green-50);border:1px solid #a7f3d0;border-radius:var(--radius-xs);padding:14px 16px;margin-bottom:24px;font-size:13px;color:#065f46}
.success-box i{color:var(--green);margin-top:1px;flex-shrink:0}

/* Alerta error */
.error-box{background:#fef2f2;border:1px solid #fecaca;border-radius:var(--radius-xs);padding:14px 16px;margin-bottom:24px;font-size:12px;color:#991b1b}
.error-box ul{padding-left:16px;margin:0}

/* Botón submit */
.btn-submit{display:inline-flex;align-items:center;gap:8px;padding:11px 28px;border-radius:var(--radius-xs);font-size:13px;font-weight:700;background:var(--blue);color:#fff;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(29,78,216,.3);transition:all .2s cubic-bezier(.22,1,.36,1)}
.btn-submit:hover{background:var(--blue-700);transform:translateY(-1px);box-shadow:0 6px 20px rgba(29,78,216,.4)}
.form-footer{display:flex;justify-content:flex-end;padding-top:8px;border-top:1px solid var(--border);margin-top:8px}

/* Select2 override */
.select2-container--default .select2-selection--single{height:40px;border:1.5px solid var(--border);border-radius:var(--radius-xs);display:flex;align-items:center;padding:0 14px}
.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:1;font-size:13px;color:var(--text);padding:0}
.select2-container--default .select2-selection--single .select2-selection__arrow{height:38px}
.select2-container--default.select2-container--focus .select2-selection--single{border-color:var(--blue);box-shadow:0 0 0 3px rgba(29,78,216,.08)}

/* Footer */
.page-footer{text-align:center;padding:24px 16px;font-size:11px;color:var(--muted)}
</style>
</head>
<body>

<div class="topbar">
    <div class="topbar-logo">IPS</div>
    <div>
        <div class="topbar-title">SIPLAN</div>
        <div class="topbar-sub">Sistema de Planificación Estratégica</div>
    </div>
    <div class="status-chip">
        <span class="status-dot"></span>
        Formulario activo
    </div>
</div>

<div class="hero anim">
    <div class="hero-badge"><i class="fa fa-project-diagram"></i> Solicitud de Proyecto</div>
    <h1>Nuevo Proyecto para<br><span>{{ $perfil->name }}</span></h1>
    <p>Completá el formulario para registrar tu solicitud. Un analista la revisará y se pondrá en contacto a la brevedad.</p>
</div>

<div class="page-wrap">

    @if(session('success'))
    <div class="success-box anim d1">
        <i class="fa fa-circle-check fa-lg"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="error-box anim d1">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="form-card anim d2">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fa fa-file-pen"></i></div>
            <div>
                <h2>Datos del Proyecto</h2>
                <p>Todos los campos marcados con <span style="color:#dc2626">*</span> son obligatorios</p>
            </div>
        </div>
        <div class="form-card-body">

            <div class="info-box">
                <i class="fa fa-circle-info fa-sm"></i>
                <span>Tu solicitud quedará en estado <strong>Solicitud</strong> hasta que un analista la revise y asigne. No es necesario que completes todos los campos ahora.</span>
            </div>

            <form action="{{ route('proyectos.solicitar.store', $perfil->id) }}" method="POST">
                @csrf

                <div class="field">
                    <label>Nombre del Proyecto <span class="req">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                           placeholder="Ej: Modernización del Sistema de Gestión de Salud">
                </div>

                <div class="field">
                    <label>Acción del Plan Estratégico <span class="req">*</span></label>
                    <select name="pei_accion_id" id="pei_accion" style="width:100%">
                        <option value="">Buscar acción del plan...</option>
                    </select>
                    <div class="field-hint">Seleccioná la acción estratégica a la que responde este proyecto.</div>
                </div>

                <div class="field">
                    <label>Descripción y Justificación</label>
                    <textarea name="descripcion" placeholder="Describí el proyecto, su justificación y el alcance esperado...">{{ old('descripcion') }}</textarea>
                    <div class="field-hint">Cuanto más detalle brindes, más rápido podrá avanzar la solicitud.</div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Dependencia Solicitante</label>
                        <select name="dependencia_solicitante_id" id="dep_solicitante" style="width:100%">
                            <option value="">Buscar dependencia...</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Fecha estimada de finalización</label>
                        <input type="date" name="fecha_fin_estimada" value="{{ old('fecha_fin_estimada') }}">
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">
                        <i class="fa fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="page-footer anim d4">
        Instituto de Previsión Social · SIPLAN &copy; {{ date('Y') }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    var depRaizId = '{{ $perfil->dependency_id }}';
    var perfilId  = '{{ $perfil->id }}';

    $('#pei_accion').select2({
        placeholder: 'Buscar acción del plan...',
        allowClear: true,
        dropdownParent: $('body'),
        ajax: {
            url: '/pei-profiles/' + perfilId + '/proyectos/acciones-publico',
            dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term }; },
            processResults: function(data) { return { results: data }; },
            cache: true
        }
    });

    $('#dep_solicitante').select2({
        placeholder: 'Buscar dependencia...',
        allowClear: true,
        dropdownParent: $('body'),
        ajax: {
            url: '{{ url("admin/globales/get-dependencies") }}/' + depRaizId,
            dataType: 'json', delay: 250,
            processResults: function(data) {
                return { results: $.map(data, function(i) { return { id: i.id, text: i.dependency }; }) };
            }, cache: true
        }
    });
});
</script>
</body>
</html>
