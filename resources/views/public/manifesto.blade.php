<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>SIPLAN — El Manifiesto & Propósito Institucional</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f8fafc;--surface:#ffffff;--border:#e2e8f0;
  --blue:#2563eb;--blue-dark:#1e40af;--purple:#7c3aed;--amber:#f59e0b;--emerald:#10b981;
  --text:#0f172a;--text-secondary:#475569;--muted:#94a3b8;
  --radius:24px;--radius-sm:14px;
  --shadow-sm:0 2px 5px rgba(0,0,0,.04);
  --shadow-md:0 12px 32px rgba(0,0,0,.08);
  --shadow-lg:0 24px 60px rgba(0,0,0,.14);
}
html{scroll-behavior:smooth}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:15px;line-height:1.75;min-height:100vh}

/* Topbar Header */
.topbar{position:sticky;top:0;z-index:300;background:rgba(255,255,255,.94);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);height:72px;display:flex;align-items:center;padding:0 clamp(20px,5vw,60px);gap:16px;justify-content:space-between}
.brand-group{display:flex;align-items:center;gap:14px;text-decoration:none;color:inherit}
.brand-logo{max-height:44px;max-width:180px;object-fit:contain}
.brand-title{font-family:'Outfit',sans-serif;font-size:16px;font-weight:800;color:var(--text);letter-spacing:-.3px}
.brand-sub{font-size:11px;color:var(--muted);font-weight:600}
.topbar-nav{display:flex;align-items:center;gap:12px;margin-left:auto}
.btn-nav-action{padding:9px 20px;border-radius:100px;font-size:13.5px;font-weight:700;text-decoration:none;transition:all .25s ease;display:inline-flex;align-items:center;gap:8px}
.btn-nav-primary{background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;box-shadow:0 4px 14px rgba(15,23,42,.22)}
.btn-nav-primary:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(15,23,42,.35);color:#fff}

/* Hero Section */
.hero{background:linear-gradient(135deg,#0f172a 0%,#1e293b 45%,#09101d 100%);color:#fff;padding:clamp(60px,8vw,110px) clamp(20px,5vw,60px);position:relative;overflow:hidden;text-align:center}
.hero::before{content:'';position:absolute;top:-180px;left:50%;transform:translateX(-50%);width:800px;height:800px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,.2) 0%,rgba(124,58,237,.12) 45%,transparent 70%);pointer-events:none}
.hero-badge{display:inline-flex;align-items:center;gap:8px;font-size:11.5px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#38bdf8;background:rgba(56,189,248,.12);border:1px solid rgba(56,189,248,.3);padding:7px 20px;border-radius:100px;margin-bottom:24px}
.hero h1{font-family:'Outfit',sans-serif;font-size:clamp(2.2rem,5vw,3.8rem);font-weight:900;color:#fff;line-height:1.12;letter-spacing:-1.2px;max-width:960px;margin:0 auto 22px}
.hero h1 span{background:linear-gradient(135deg,#60a5fa 0%,#c084fc 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p.lead{font-size:clamp(1.05rem,2vw,1.3rem);color:#cbd5e1;max-width:800px;margin:0 auto 34px;font-weight:400;line-height:1.65}

/* Container */
.page-container{max-width:1140px;margin:0 auto;padding:clamp(30px,5vw,70px) clamp(20px,4vw,40px)}

/* Quote Card */
.quote-card{background:linear-gradient(135deg,#ffffff 0%,#f8fafc 100%);border:1.5px solid var(--border);border-radius:var(--radius);padding:clamp(32px,5vw,50px);margin-top:-70px;position:relative;z-index:10;box-shadow:var(--shadow-lg);border-left:8px solid var(--blue)}
.quote-card p{font-size:clamp(1.1rem,2.2vw,1.35rem);font-weight:600;color:var(--text);line-height:1.65;font-style:italic;margin-bottom:20px}
.quote-author{display:flex;align-items:center;gap:14px}
.quote-author-avatar{width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--purple));display:grid;place-items:center;color:#fff;font-weight:900;font-size:20px;box-shadow:0 4px 12px rgba(37,99,235,.3)}
.quote-author-info strong{display:block;font-size:15px;color:var(--text);font-weight:800}
.quote-author-info span{font-size:12.5px;color:var(--text-secondary)}

/* Grid Features */
.section-title{text-align:center;margin:70px 0 40px}
.section-title h2{font-family:'Outfit',sans-serif;font-size:clamp(1.7rem,3.2vw,2.5rem);font-weight:800;color:var(--text);letter-spacing:-.6px}
.section-title p{font-size:14.5px;color:var(--text-secondary);max-width:640px;margin:10px auto 0}

.cards-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:28px}
.feature-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:36px;box-shadow:var(--shadow-sm);transition:all .28s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column}
.feature-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-md);border-color:#cbd5e1}
.icon-box{width:58px;height:58px;border-radius:var(--radius-sm);display:grid;place-items:center;font-size:24px;margin-bottom:22px}
.icon-blue{background:#eff6ff;color:var(--blue);border:1px solid #dbeafe}
.icon-purple{background:#f5f3ff;color:var(--purple);border:1px solid #ddd6fe}
.icon-amber{background:#fffbeb;color:var(--amber);border:1px solid #fef3c7}
.icon-emerald{background:#ecfdf5;color:var(--emerald);border:1px solid #a7f3d0}
.feature-card h3{font-family:'Outfit',sans-serif;font-size:19px;font-weight:800;color:var(--text);margin-bottom:12px}
.feature-card p{font-size:14px;color:var(--text-secondary);line-height:1.65}

/* Target Vision Banner */
.vision-banner{background:linear-gradient(135deg,#0284c7 0%,#0369a1 100%);color:#fff;border-radius:var(--radius);padding:clamp(36px,6vw,60px);margin-top:60px;box-shadow:var(--shadow-lg);position:relative;overflow:hidden}
.vision-banner h2{font-family:'Outfit',sans-serif;font-size:clamp(1.7rem,3.2vw,2.6rem);font-weight:900;margin-bottom:16px;color:#fff}
.vision-banner p.desc{font-size:15.5px;color:#e0f2fe;max-width:850px;line-height:1.75;margin-bottom:30px}
.vision-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px}
.vision-item{background:rgba(255,255,255,.12);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.22);border-radius:var(--radius-sm);padding:22px;display:flex;align-items:flex-start;gap:14px}
.vision-item i{font-size:22px;color:#7dd3fc;margin-top:2px}
.vision-item-text strong{display:block;font-size:15px;color:#fff;font-weight:800;margin-bottom:4px}
.vision-item-text span{font-size:12.5px;color:#bae6fd;line-height:1.55}

/* Team Pride Banner */
.pride-card{background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;border-radius:var(--radius);padding:clamp(32px,5vw,50px);margin-top:40px;display:flex;flex-wrap:wrap;align-items:center;gap:30px;box-shadow:var(--shadow-md)}
.pride-text{flex:1;min-width:280px}
.pride-text h3{font-family:'Outfit',sans-serif;font-size:22px;font-weight:800;color:#fff;margin-bottom:10px}
.pride-text p{font-size:14px;color:#94a3b8;line-height:1.6}
.pride-action{display:flex;gap:12px}

/* Footer */
.page-footer{border-top:1px solid var(--border);background:#fff;padding:40px 20px;text-align:center;font-size:12.5px;color:var(--muted);margin-top:70px}
</style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
  <a href="{{ url('/') }}" class="brand-group">
    @if(!empty($sysLogoUrl))
      <img src="{{ $sysLogoUrl }}" alt="SIPLAN GO Logo" class="brand-logo" style="max-height: 52px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
    @else
      <div style="width:44px;height:44px;border-radius:12px;background:#0f172a;color:#fff;display:grid;place-items:center;font-weight:900;font-size:16px;">GO</div>
    @endif
    <div>
      <div class="brand-title" style="font-size: 1.15rem; font-weight: 900;">SIPLAN <span style="color:#2563eb;">GO</span></div>
      <div class="brand-sub" style="font-weight: 700; color: #2563eb;">Planificar con Propósito</div>
    </div>
  </a>

  <div class="topbar-nav">
    <a href="{{ route('home') }}" class="btn-nav-action btn-nav-primary">
      <i class="fa fa-tachometer-alt"></i> Ir al Sistema
    </a>
  </div>
</div>

{{-- HERO --}}
<div class="hero">
  <div class="hero-badge"><i class="fa fa-flag-checkered"></i> Funcionarios del IPS cambiando la historia</div>
  <h1>SIPLAN <span style="color:#60a5fa;">GO</span> · <span>Planificar con Propósito</span></h1>
  <p class="lead">Somos funcionarios del IPS que sumamos nuestros esfuerzos para transformar la seguridad social de nuestro país, asegurando que la tecnología y el trabajo mancomunado se traduzcan en atención digna, medicamentos a tiempo y valor para nuestros asegurados.</p>
</div>

<div class="page-container">

  {{-- QUOTE MANIFESTO --}}
  <div class="quote-card">
    <p>“No nacimos en escritorios distantes ni en burocracia aislada. Somos funcionarios del IPS que sumamos nuestros esfuerzos para transformar la seguridad social de nuestro país. SIPLAN GO es una Red Social y Laboral donde profesionales, analistas, médicos y coordinadores nos conectamos para aportar nuestro talento, colaborar en tiempo real y cambiar la vida de nuestros asegurados con tecnología y trabajo mancomunado.”</p>
    <div class="quote-author">
      <div class="quote-author-avatar"><i class="fa fa-heart"></i></div>
      <div class="quote-author-info">
        <strong>Equipo Técnico & Funcionarios de Planificación</strong>
        <span>Instituto de Previsión Social (IPS) — República del Paraguay</span>
      </div>
    </div>
  </div>

  {{-- SECCIÓN ¿QUÉ ES SIPLAN GO? --}}
  <div class="section-title">
    <h2>Una Red Social & Laboral para Transformar la Gestión</h2>
    <p>SIPLAN GO es el punto de encuentro donde el talento humano de la institución colabora, comparte ideas e impulsa cambios reales con transparencia y reconocimiento.</p>
  </div>

  <div class="cards-grid">
    <div class="feature-card">
      <div class="icon-box icon-blue"><i class="fa fa-users"></i></div>
      <h3>Red Laboral & Inteligencia Colectiva</h3>
      <p>Un espacio donde todos los profesionales, médicos, analistas y coordinadores aportamos ideas desde nuestro lugar, superando la burocracia con colaboración activa.</p>
    </div>

    <div class="feature-card">
      <div class="icon-box icon-purple"><i class="fa fa-trophy"></i></div>
      <h3>Reconocimiento al Talento Humano</h3>
      <p>Premia la excelencia mediante un sistema de gamificación con puntos, medallas de mérito y ranking público de colaboradores destacados en la institución.</p>
    </div>

    <div class="feature-card">
      <div class="icon-box icon-amber"><i class="fa fa-comments"></i></div>
      <h3>Interacción en Tiempo Real</h3>
      <p>Debates contextuales, chat de colaboración directa por objetivo estratégicos y portal abierto para asesoría técnica independiente.</p>
    </div>

    <div class="feature-card">
      <div class="icon-box icon-emerald"><i class="fa fa-bullseye"></i></div>
      <h3>Planificación con Propósito (PEI + PGN)</h3>
      <p>Asegura que cada meta física del PEI y cada guaraní asignado en el Presupuesto se traduzcan en soluciones reales para los asegurados.</p>
    </div>
  </div>

  {{-- BANNER DE VISIÓN A FUTURO --}}
  <div class="vision-banner">
    <h2><i class="fa fa-hospital-user mr-2"></i> Nuestra Meta Final: El Asegurado del IPS</h2>
    <p class="desc">El fin de SIPLAN no es solo un sistema informático bonito. Nuestra verdadera meta es que la eficiencia técnica del software se transforme en un impacto tangible y cálido en la vida diaria de cada cotizante, jubilado y familia paraguaya.</p>

    <div class="vision-list">
      <div class="vision-item">
        <i class="fa fa-pills"></i>
        <div class="vision-item-text">
          <strong>Medicamentos Oportunos</strong>
          <span>Evitar el desabastecimiento hospitalario mediante análisis de datos predictivos e insumos a tiempo.</span>
        </div>
      </div>

      <div class="vision-item">
        <i class="fa fa-clock"></i>
        <div class="vision-item-text">
          <strong>Reducción de Tiempos de Espera</strong>
          <span>Acelerar la programación de consultas, estudios tomográficos y cirugías mediante planificación ágil.</span>
        </div>
      </div>

      <div class="vision-item">
        <i class="fa fa-heartbeat"></i>
        <div class="vision-item-text">
          <strong>Atención Humana y Digna</strong>
          <span>Asegurar que nuestros padres y abuelos que aportaron toda su vida reciban el trato respetuoso y oportuno que merecen.</span>
        </div>
      </div>

      <div class="vision-item">
        <i class="fa fa-award"></i>
        <div class="vision-item-text">
          <strong>Orgullo e Integridad Institucional</strong>
          <span>Convertir al IPS en un referente regional de gestión transparente, modernidad y efectividad pública.</span>
        </div>
      </div>
    </div>
  </div>

  {{-- PRIDE CARD --}}
  <div class="pride-card">
    <div class="pride-text">
      <h3>Hecho en Paraguay, por Paraguayos</h3>
      <p>Cada línea de código, cada tablero de control y cada algoritmo en SIPLAN fue creado con esfuerzo local para transformar el futuro de nuestra Patria.</p>
    </div>
    <div class="pride-action">
      <a href="{{ route('home') }}" class="btn-nav-action btn-nav-primary">
        <i class="fa fa-arrow-right"></i> Acceder a la Plataforma
      </a>
    </div>
  </div>

</div>

{{-- FOOTER --}}
<div class="page-footer">
  <strong>{{ $sysSiteName }}</strong> — Dirección de Planificación y Evaluación Institucional.
  <div>© {{ date('Y') }} Instituto de Previsión Social (IPS) — República del Paraguay. Todos los derechos reservados.</div>
</div>

@include('layouts.includes.ticket_modal')

</body>
</html>
