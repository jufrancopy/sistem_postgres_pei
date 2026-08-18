<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Acceso Asesoría Externa — Validación de Plan Estratégico</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f8fafc;--surface:#ffffff;--border:#e2e8f0;
  --blue:#2563eb;--blue-50:#eff6ff;--blue-100:#dbeafe;--blue-700:#1d4ed8;
  --amber:#f59e0b;--amber-50:#fffbeb;--amber-100:#fef3c7;--amber-700:#b45309;
  --text:#0f172a;--text-secondary:#475569;--muted:#94a3b8;
  --radius:16px;--radius-sm:10px;--radius-xs:8px;
  --shadow-sm:0 1px 2px rgba(0,0,0,.04);
  --shadow-md:0 4px 20px rgba(0,0,0,.06);
  --shadow-lg:0 12px 40px rgba(0,0,0,.1);
}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.6;min-height:100vh;display:flex;flex-direction:column}

/* Topbar */
.topbar{position:sticky;top:0;z-index:300;background:rgba(255,255,255,.9);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);height:60px;display:flex;align-items:center;padding:0 clamp(16px,4vw,40px);gap:12px}
.topbar-logo{width:36px;height:36px;border-radius:var(--radius-xs);background:linear-gradient(135deg,var(--blue),#1e293b);display:grid;place-items:center;color:#fff;font-weight:900;font-size:14px;flex-shrink:0}
.topbar-title{font-size:14px;font-weight:700;color:var(--text)}
.topbar-sub{font-size:11px;color:var(--muted)}

/* Hero Header */
.hero{background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;padding:clamp(32px,5vw,56px) clamp(16px,4vw,40px);position:relative;overflow:hidden;text-align:center}
.hero::before{content:'';position:absolute;top:-100px;right:-100px;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(245,158,11,.15) 0%,transparent 70%);pointer-events:none}
.hero-badge{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--amber);background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);padding:5px 16px;border-radius:100px;margin-bottom:16px}
.hero h1{font-size:clamp(1.6rem,3vw,2.4rem);font-weight:900;color:#fff;line-height:1.2;letter-spacing:-.5px;margin-bottom:10px}
.hero p{font-size:14px;color:#cbd5e1;max-width:540px;margin:0 auto;line-height:1.6}

/* Page Wrap & Form Card */
.page-wrap{max-width:480px;margin:-30px auto 40px;padding:0 20px;width:100%;position:relative;z-index:10}
.login-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-lg);padding:32px;overflow:hidden}
.login-card-header{margin-bottom:24px;text-align:center}
.login-card-icon{width:48px;height:48px;border-radius:50%;background:var(--amber-50);color:var(--amber-700);border:1px solid var(--amber-100);display:grid;place-items:center;font-size:20px;margin:0 auto 12px}
.login-card-header h2{font-size:18px;font-weight:800;color:var(--text)}
.login-card-header p{font-size:12px;color:var(--text-secondary);margin-top:4px}

/* Inputs */
.field{margin-bottom:20px}
.field label{display:block;font-size:12px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;letter-spacing:.2px}
.field label .req{color:#dc2626;margin-left:2px}
.field-input-wrap{position:relative;display:flex;align-items:center}
.field-input-wrap i{position:absolute;left:14px;color:var(--muted);font-size:14px;pointer-events:none}
.field input{width:100%;padding:12px 14px 12px 40px;border:1.5px solid var(--border);border-radius:var(--radius-xs);font-family:inherit;font-size:14px;color:var(--text);background:var(--surface);transition:all .15s ease;outline:none}
.field input:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.field input::placeholder{color:var(--muted)}

/* Alert */
.error-alert{background:#fef2f2;border:1px solid #fecaca;border-radius:var(--radius-xs);padding:12px 16px;margin-bottom:20px;font-size:12px;color:#991b1b;display:flex;align-items:flex-start;gap:10px}
.error-alert i{margin-top:2px;font-size:14px;flex-shrink:0}

.info-alert{background:#eff6ff;border:1px solid #dbeafe;border-radius:var(--radius-xs);padding:12px 16px;margin-bottom:20px;font-size:12px;color:#1e40af;display:flex;align-items:flex-start;gap:10px}

/* Submit Button */
.btn-submit{width:100%;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:13px;border-radius:var(--radius-xs);font-size:14px;font-weight:700;background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(15,23,42,.3);transition:all .2s ease}
.btn-submit:hover{background:linear-gradient(135deg,#1e293b 0%,#334155 100%);transform:translateY(-1px);box-shadow:0 6px 20px rgba(15,23,42,.4)}

/* Footer */
.page-footer{margin-top:auto;text-align:center;padding:24px 16px;font-size:11px;color:var(--muted)}
</style>
</head>
<body>

@php
  $sysLogoRaw  = \App\Models\HomeConfiguration::getSetting('logo_url');
  $sysSiteName = \App\Models\HomeConfiguration::getSetting('site_name', 'Sistema de Planificación Institucional');
  if (!empty($sysLogoRaw)) {
      $sysLogoUrl = (str_starts_with($sysLogoRaw, 'http://') || str_starts_with($sysLogoRaw, 'https://')) ? $sysLogoRaw : url($sysLogoRaw);
  } else {
      $sysLogoUrl = asset('material/img/new_logo.png');
  }
@endphp

<div class="topbar">
  @if(!empty($sysLogoUrl))
    <img src="{{ $sysLogoUrl }}" alt="{{ $sysSiteName }}" style="max-height: 38px; max-width: 160px; object-fit: contain; flex-shrink:0;">
  @else
    <div class="topbar-logo"><i class="fa fa-user-shield"></i></div>
  @endif
  <div>
    <div class="topbar-title">{{ $sysSiteName }}</div>
    <div class="topbar-sub">Módulo de Asesoría y Validación Externa</div>
  </div>
</div>

<div class="hero">
  <div class="hero-badge"><i class="fa fa-key"></i> Validación Remota por Asesor</div>
  <h1>Portal de Asesoría Externa PEI</h1>
  <p>Ingresá las credenciales provistas por la institución para revisar el plan estratégico y redactar tus recomendaciones técnicas.</p>
</div>

<div class="page-wrap">
  <div class="login-card">
    <div class="login-card-header">
      <div class="login-card-icon"><i class="fa fa-user-check"></i></div>
      <h2>Acceso del Asesor Validador</h2>
      <p>Completá tu correo electrónico y tu código único de asesoría.</p>
    </div>

    @if(session('info'))
      <div class="info-alert">
        <i class="fa fa-info-circle"></i>
        <div>{{ session('info') }}</div>
      </div>
    @endif

    @if($errors->has('credentials'))
      <div class="error-alert">
        <i class="fa fa-exclamation-triangle"></i>
        <div>{{ $errors->first('credentials') }}</div>
      </div>
    @endif

    <form action="{{ route('asesoria.public.login.submit') }}" method="POST">
      @csrf

      <div class="field">
        <label for="email">Correo Electrónico del Asesor <span class="req">*</span></label>
        <div class="field-input-wrap">
          <i class="fa fa-envelope"></i>
          <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="ejemplo@asesoria.gov.py" required autofocus>
        </div>
      </div>

      <div class="field">
        <label for="code">Código Único de Asesoría <span class="req">*</span></label>
        <div class="field-input-wrap">
          <i class="fa fa-key"></i>
          <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="Ej: ASESOR-8F3K9" style="text-transform: uppercase; font-weight: 700; letter-spacing: 1px;" required>
        </div>
      </div>

      <button type="submit" class="btn-submit">
        <span>Ingresar al Portal de Validación</span>
        <i class="fa fa-arrow-right"></i>
      </button>
    </form>
  </div>
</div>

<div class="page-footer">
  &copy; {{ date('Y') }} Sistema de Planificación Estratégica Institucional. Todos los derechos reservados.
</div>

</body>
</html>
