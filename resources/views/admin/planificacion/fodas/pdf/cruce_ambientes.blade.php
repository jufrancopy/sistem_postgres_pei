<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Cruce de Ambientes — {{ strip_tags($perfil->name) }}</title>
<style>
  /* ── Márgenes de página: espacio para header y footer fijos ── */
  @page {
    margin-top: 18px;
    margin-bottom: 30px;
    margin-left: 0;
    margin-right: 0;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10px;
    color: #1a1a2e;
    background: #fff;
    line-height: 1.5;
  }

  /* ── HEADER repetido en cada página ── */
  #page-header {
    position: fixed;
    top: -18px;
    left: 0;
    right: 0;
    height: 18px;
    background: #1a1a2e;
    border-bottom: 2px solid #4a5fa0;
    padding: 0 28px;
  }
  #page-header table {
    width: 100%;
    height: 18px;
  }
  #page-header td {
    vertical-align: middle;
    font-size: 7px;
    color: #7a8299;
    letter-spacing: 1px;
    text-transform: uppercase;
  }
  #page-header .right { text-align: right; }

  /* ── FOOTER repetido en cada página ── */
  #page-footer {
    position: fixed;
    bottom: -30px;
    left: 0;
    right: 0;
    height: 30px;
    background: #1a1a2e;
    border-top: 3px solid #4a5fa0;
    padding: 0 28px;
  }
  #page-footer table {
    width: 100%;
    height: 30px;
  }
  #page-footer td {
    vertical-align: middle;
    font-size: 7.5px;
    color: #7a8299;
  }
  #page-footer .center {
    text-align: center;
    font-size: 8.5px;
    font-weight: bold;
    color: #a0aec0;
    letter-spacing: 2px;
    text-transform: uppercase;
  }
  #page-footer .right { text-align: right; }

  /* ── PORTADA / HEADER ── */
  .header {
    background: #1a1a2e;
    color: #fff;
    padding: 28px 32px 22px;
  }
  .header-label {
    font-size: 8px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #7a8299;
    margin-bottom: 8px;
  }
  .header-title {
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    margin-bottom: 4px;
  }
  .header-sub {
    font-size: 10px;
    color: #a0aec0;
  }

  /* ── BANDA DE COLORES ── */
  .color-band {
    height: 5px;
    background: #1a7a4a;
    border-right: 1px solid #fff;
  }
  .color-band-wrap {
    width: 100%;
    height: 5px;
    overflow: hidden;
  }
  .color-band-fo { display: inline-block; width: 25%; height: 5px; background: #1a7a4a; }
  .color-band-do { display: inline-block; width: 25%; height: 5px; background: #1a5fa0; }
  .color-band-fa { display: inline-block; width: 25%; height: 5px; background: #b45309; }
  .color-band-da { display: inline-block; width: 25%; height: 5px; background: #9b1c1c; }

  /* ── META INFO ── */
  .meta-box {
    background: #f8f9fc;
    border-bottom: 1px solid #e2e8f0;
    padding: 14px 32px;
  }
  .meta-box table { width: 100%; }
  .meta-box td { width: 33%; padding-right: 16px; vertical-align: top; }
  .meta-label {
    font-size: 7.5px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #718096;
    margin-bottom: 3px;
  }
  .meta-value {
    font-size: 10px;
    font-weight: bold;
    color: #2d3748;
  }

  /* ── RESUMEN ── */
  .resumen {
    padding: 14px 32px;
    border-bottom: 1px solid #e2e8f0;
  }
  .resumen table { width: 100%; }
  .resumen td {
    text-align: center;
    padding: 8px 12px;
    border-right: 1px solid #e2e8f0;
  }
  .resumen td:last-child { border-right: none; }
  .resumen-num { font-size: 20px; font-weight: bold; }
  .resumen-label {
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #718096;
    margin-top: 2px;
  }

  /* ── CONTENIDO ── */
  .content { padding: 20px 32px; }

  /* ── SECCIÓN POR TIPO ── */
  .tipo-section {
    margin-bottom: 22px;
    page-break-inside: avoid;
  }
  .tipo-header {
    padding: 10px 14px;
    border-radius: 4px 4px 0 0;
  }
  .tipo-header table { width: 100%; }
  .tipo-header td { vertical-align: middle; }
  .tipo-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    text-align: center;
    font-size: 13px;
    font-weight: bold;
    color: #fff;
    line-height: 32px;
    padding: 0;
  }
  .tipo-info { padding-left: 10px; }
  .tipo-title { font-size: 12px; font-weight: bold; color: #fff; }
  .tipo-sub { font-size: 8px; color: rgba(255,255,255,0.75); margin-top: 2px; }
  .tipo-count { text-align: right; color: rgba(255,255,255,0.85); font-size: 9px; }

  /* ── LISTA DE ESTRATEGIAS ── */
  .estrategias-list {
    border: 1px solid #e2e8f0;
    border-top: none;
    border-radius: 0 0 4px 4px;
  }
  .estrategias-list table { width: 100%; border-collapse: collapse; }
  .estrategias-list tr { border-bottom: 1px solid #f0f4f8; }
  .estrategias-list tr:last-child { border-bottom: none; }
  .estrategias-list tr.par td { background: #f8fafc; }
  .estrategia-num {
    width: 28px;
    text-align: center;
    vertical-align: top;
    padding: 9px 6px;
    font-size: 8.5px;
    font-weight: bold;
    color: #a0aec0;
    border-right: 1px solid #e2e8f0;
  }
  .estrategia-texto {
    padding: 9px 14px;
    font-size: 9.5px;
    color: #2d3748;
    vertical-align: top;
    line-height: 1.6;
  }
  .sin-estrategias {
    padding: 14px;
    text-align: center;
    color: #a0aec0;
    font-size: 9px;
    font-style: italic;
    border: 1px solid #e2e8f0;
    border-top: none;
    border-radius: 0 0 4px 4px;
  }

  /* ── INTRO ── */
  .intro-box {
    background: #fffbeb;
    border-left: 3px solid #f59e0b;
    padding: 10px 14px;
    margin-bottom: 20px;
  }
  .intro-box p { font-size: 9px; color: #78350f; line-height: 1.6; }
</style>
</head>
<body>

{{-- ══ HEADER fijo — se repite en cada página ══ --}}
<div id="page-header">
  <table>
    <tr>
      <td>SIPLAN &middot; Análisis FODA &mdash; Cruce de Ambientes</td>
      <td class="right">{{ strip_tags($perfil->name) }}</td>
    </tr>
  </table>
</div>

{{-- ══ FOOTER fijo — se repite en cada página ══ --}}
<div id="page-footer">
  <table>
    <tr>
      <td style="width:30%">Generado: {{ $generadoEn }}</td>
      <td class="center" style="width:40%">SIPLAN &mdash; Sistema de Planificación Estratégica</td>
      <td class="right" style="width:30%">{{ $perfil->group->name ?? '' }}</td>
    </tr>
  </table>
</div>

{{-- ══ HEADER de portada ══ --}}
<div class="header">
  <div class="header-label">// SIPLAN &middot; Análisis FODA</div>
  <div class="header-title">Cruce de Ambientes &mdash; Matriz Estratégica</div>
  <div class="header-sub">{{ strip_tags($perfil->name) }}</div>
</div>

{{-- Banda de colores FO/DO/FA/DA --}}
<div class="color-band-wrap">
  <span class="color-band-fo"></span><span class="color-band-do"></span><span class="color-band-fa"></span><span class="color-band-da"></span>
</div>

{{-- ══ META INFO ══ --}}
<div class="meta-box">
  <table>
    <tr>
      <td>
        <div class="meta-label">Perfil de Análisis</div>
        <div class="meta-value">{{ strip_tags($perfil->name) }}</div>
      </td>
      <td>
        <div class="meta-label">Modelo</div>
        <div class="meta-value">{{ $perfil->model->name ?? '&mdash;' }}</div>
      </td>
      <td>
        <div class="meta-label">Grupo / Dependencia</div>
        <div class="meta-value">{{ $perfil->group->name ?? '&mdash;' }}</div>
      </td>
    </tr>
  </table>
</div>

{{-- ══ RESUMEN ══ --}}
<div class="resumen">
  <table>
    <tr>
      @foreach($tipos as $tipo => $info)
      <td>
        <div class="resumen-num" style="color:{{ $info['color'] }}">{{ $estrategiasPorTipo[$tipo]->count() }}</div>
        <div class="resumen-label">{{ $tipo }}</div>
      </td>
      @endforeach
      <td>
        <div class="resumen-num" style="color:#4a5568">{{ $totalEstrategias }}</div>
        <div class="resumen-label">Total</div>
      </td>
    </tr>
  </table>
</div>

{{-- ══ CONTENIDO ══ --}}
<div class="content">

  @if($perfil->context)
  <div class="intro-box">
    <p><strong>Contexto:</strong> {{ strip_tags($perfil->context) }}</p>
  </div>
  @endif

  @foreach($tipos as $tipo => $info)
  @php $estrategias = $estrategiasPorTipo[$tipo]; @endphp

  <div class="tipo-section">

    <div class="tipo-header" style="background-color:{{ $info['color'] }}">
      <table>
        <tr>
          <td class="tipo-badge" style="width:32px">{{ $tipo }}</td>
          <td class="tipo-info">
            <div class="tipo-title">{{ $info['label'] }}</div>
            <div class="tipo-sub">{{ $info['sub'] }}</div>
          </td>
          <td class="tipo-count">{{ $estrategias->count() }} estrategia(s)</td>
        </tr>
      </table>
    </div>

    @if($estrategias->isNotEmpty())
    <div class="estrategias-list">
      <table>
        @foreach($estrategias as $i => $e)
        <tr class="{{ $i % 2 === 1 ? 'par' : '' }}">
          <td class="estrategia-num">{{ $i + 1 }}</td>
          <td class="estrategia-texto">{{ strip_tags($e->estrategia) }}</td>
        </tr>
        @endforeach
      </table>
    </div>
    @else
    <div class="sin-estrategias">Sin estrategias registradas para este cuadrante</div>
    @endif

  </div>
  @endforeach

</div>

</body>
</html>
