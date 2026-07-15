<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ strip_tags($profile->name) }}</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#f0f2f8;font-family:'Inter',sans-serif;color:#1e293b;min-height:100vh}

/* ── Header hero ── */
.hero{background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 50%,#1a237e 100%);position:relative;overflow:hidden;padding:2.5rem 0 0}
.hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")}
.hero-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem}
.hero-badge{display:inline-flex;align-items:center;gap:.4rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:20px;padding:.3rem .8rem;font-size:.68rem;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.08em;margin-bottom:1rem}
.hero-title{font-size:clamp(1.3rem,3vw,2rem);font-weight:800;color:#fff;line-height:1.25;margin-bottom:.75rem;max-width:700px}
.hero-meta{font-size:.8rem;color:rgba(255,255,255,.55);display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem}
.hero-meta span{display:flex;align-items:center;gap:.3rem}

/* Stat cards en el hero */
.hero-stats{display:flex;gap:1px;background:rgba(255,255,255,.08);border-radius:.75rem .75rem 0 0;overflow:hidden;margin-top:1rem}
.hero-stat{flex:1;padding:.9rem 1rem;text-align:center;background:rgba(0,0,0,.15)}
.hero-stat .stat-num{font-size:1.8rem;font-weight:800;line-height:1}
.hero-stat .stat-lbl{font-size:.65rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.06em;margin-top:.2rem}
.stat-verde{color:#4ade80}
.stat-amarillo{color:#fbbf24}
.stat-rojo{color:#f87171}
.stat-total{color:#fff}

/* ── Nav tabs ── */
.pub-nav{background:#fff;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:200;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.pub-nav-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;gap:.25rem}
.pub-tab{display:flex;align-items:center;gap:.4rem;padding:.8rem 1.1rem;font-size:.82rem;font-weight:600;color:#64748b;border:none;background:transparent;cursor:pointer;border-bottom:3px solid transparent;transition:all .15s;white-space:nowrap}
.pub-tab:hover{color:#1e3a8a}
.pub-tab.active{color:#1e3a8a;border-bottom-color:#1e3a8a}
.pub-tab i{font-size:.75rem}

/* ── Main content ── */
.pub-content{max-width:1200px;margin:0 auto;padding:1.75rem 1.5rem}
.tab-section{display:none}.tab-section.active{display:block}

/* ── BSC ── */
.bsc-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(480px,1fr));gap:1.25rem}
.bsc-perspectiva{background:#fff;border-radius:1rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.07),0 4px 12px rgba(0,0,0,.04)}
.bsc-persp-header{padding:.9rem 1.2rem;display:flex;align-items:center;justify-content:space-between}
.bsc-persp-header h3{font-size:.8rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:.06em;display:flex;align-items:center;gap:.5rem;margin:0}
.bsc-persp-badges{display:flex;gap:.3rem}
.bsc-persp-badge{background:rgba(255,255,255,.2);color:#fff;border-radius:20px;padding:.15rem .5rem;font-size:.65rem;font-weight:600}
.bsc-eje-item{padding:.85rem 1.2rem;border-bottom:1px solid #f1f5f9;transition:background .1s}
.bsc-eje-item:last-child{border-bottom:none}
.bsc-eje-item:hover{background:#fafbff}
.bsc-eje-name{font-size:.83rem;font-weight:600;color:#1e293b;line-height:1.35;margin-bottom:.4rem}
.bsc-eje-ri{font-size:.73rem;color:#64748b;font-style:italic;margin-bottom:.5rem}
.bsc-sem-row{display:flex;align-items:center;gap:.5rem}
.sem-pill{display:inline-flex;align-items:center;gap:.25rem;border-radius:20px;padding:.2rem .5rem;font-size:.65rem;font-weight:600}
.sem-pill-verde{background:#dcfce7;color:#15803d}
.sem-pill-amarillo{background:#fef9c3;color:#a16207}
.sem-pill-rojo{background:#fee2e2;color:#b91c1c}
.sem-pill-sin{background:#f1f5f9;color:#64748b}
.sem-bar{flex:1;height:5px;background:#f1f5f9;border-radius:3px;overflow:hidden}
.sem-bar-fill{height:100%;border-radius:3px;transition:width .4s}
.bsc-acciones-list{margin-top:.5rem;padding-left:.75rem;border-left:2px solid #e2e8f0}
.bsc-accion-row{display:flex;align-items:center;gap:.4rem;padding:.25rem 0;font-size:.75rem;color:#475569;border-bottom:1px dashed #f1f5f9}
.bsc-accion-row:last-child{border-bottom:none}
.bsc-obj-label{font-size:.72rem;font-weight:600;color:#334155;margin:.4rem 0 .2rem;display:flex;align-items:center;gap:.3rem}

/* Semáforo dot */
.sdot{width:9px;height:9px;border-radius:50%;flex-shrink:0;display:inline-block}
.sdot-verde{background:#22c55e}.sdot-amarillo{background:#eab308}.sdot-rojo{background:#ef4444}.sdot-sin{background:#cbd5e1}

/* ── Matriz ── */
.mat-wrap{background:#fff;border-radius:1rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.07)}
.mat-wrap table{font-size:.8rem}
.mat-wrap thead th{background:#f8faff;color:#475569;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;padding:.6rem .75rem;border-bottom:2px solid #e2e8f0}
.mat-wrap tbody td{padding:.55rem .75rem;vertical-align:middle;border-color:#f1f5f9}
.td-axi{background:#eff6ff;font-weight:700;color:#1e3a8a;font-size:.78rem}
.td-obj{background:#fafbff;color:#334155;font-size:.78rem}
.td-accion{color:#1e293b}
.td-ind{color:#64748b;font-size:.73rem}

/* ── MECIP ── */
.mecip-wrap{display:flex;flex-direction:column;gap:.75rem}
.mecip-eje{background:#fff;border-radius:.75rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.mecip-eje-header{padding:.8rem 1rem;background:linear-gradient(135deg,#1e3a8a,#1a237e);display:flex;align-items:center;justify-content:space-between}
.mecip-eje-name{font-size:.82rem;font-weight:700;color:#fff;flex:1;min-width:0;margin-right:.5rem}
.mecip-eje-body{padding:.5rem .75rem}
.mecip-goal{padding:.4rem .5rem;margin:.25rem 0}
.mecip-goal-name{font-size:.75rem;font-weight:600;color:#475569;margin-bottom:.25rem;display:flex;align-items:center;gap:.3rem}
.mecip-action-row{display:flex;align-items:center;gap:.5rem;padding:.3rem .5rem .3rem 1rem;border-bottom:1px solid #f8fafc;font-size:.78rem;color:#334155}
.mecip-action-row:last-child{border-bottom:none}
.mecip-ind-badge{margin-left:auto;flex-shrink:0;background:#f1f5f9;color:#475569;border-radius:.3rem;padding:.1rem .4rem;font-size:.62rem;font-weight:600}

/* ── Footer ── */
.pub-footer{text-align:center;padding:2rem 1rem;color:#94a3b8;font-size:.72rem;border-top:1px solid #e2e8f0;margin-top:2rem}
.pub-footer strong{color:#64748b}
</style>
</head>
<body>

@php
$todasAcciones = $profile->descendants()->where('level','action')->get();
$gVerde    = $todasAcciones->where('semaforo','verde')->count();
$gAmarillo = $todasAcciones->where('semaforo','amarillo')->count();
$gRojo     = $todasAcciones->where('semaforo','rojo')->count();
$gTotal    = $todasAcciones->count();
$gPct      = $gTotal > 0 ? round(($gVerde / $gTotal) * 100) : 0;
@endphp

{{-- ══ HERO ══════════════════════════════════════════════════════════════════ --}}
<div class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <i class="fa fa-eye"></i> Vista pública · Solo lectura
        </div>
        <h1 class="hero-title">{!! strip_tags($profile->name) !!}</h1>
        <div class="hero-meta">
            <span><i class="fa fa-calendar-alt"></i>
                {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }} –
                {{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
            </span>
            @if($profile->dependency)
            <span><i class="fa fa-building"></i> {{ $profile->dependency->dependency }}</span>
            @endif
            <span><i class="fa fa-lock"></i> SIPLAN</span>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="stat-num stat-verde">{{ $gVerde }}</div>
                <div class="stat-lbl">En meta</div>
            </div>
            <div class="hero-stat">
                <div class="stat-num stat-amarillo">{{ $gAmarillo }}</div>
                <div class="stat-lbl">En proceso</div>
            </div>
            <div class="hero-stat">
                <div class="stat-num stat-rojo">{{ $gRojo }}</div>
                <div class="stat-lbl">Crítico</div>
            </div>
            <div class="hero-stat">
                <div class="stat-num stat-total">{{ $gTotal }}</div>
                <div class="stat-lbl">Total acciones</div>
            </div>
            <div class="hero-stat" style="flex:2">
                <div style="font-size:.65rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.4rem">Avance general</div>
                <div style="background:rgba(255,255,255,.12);border-radius:4px;height:8px;overflow:hidden">
                    <div style="width:{{ $gPct }}%;height:100%;background:{{ $gPct >= 75 ? '#4ade80' : ($gPct >= 50 ? '#fbbf24' : '#f87171') }};border-radius:4px;transition:width .6s"></div>
                </div>
                <div style="font-size:.72rem;color:rgba(255,255,255,.7);margin-top:.3rem;font-weight:700">{{ $gPct }}%</div>
            </div>
        </div>
    </div>
</div>

{{-- ══ NAV TABS ════════════════════════════════════════════════════════════════ --}}
<div class="pub-nav">
    <div class="pub-nav-inner">
        <button class="pub-tab active" data-tab="bsc">
            <i class="fa fa-th-large"></i> Balanced Scorecard
        </button>
        <button class="pub-tab" data-tab="matriz">
            <i class="fa fa-table"></i> Matriz Estratégica
        </button>
        <button class="pub-tab" data-tab="mecip">
            <i class="fa fa-shield-alt"></i> Vista Jerárquica
        </button>
    </div>
</div>

{{-- ══ CONTENIDO ══════════════════════════════════════════════════════════════ --}}
<div class="pub-content">

{{-- ── TAB BSC ──────────────────────────────────────────────────────────────── --}}
<div class="tab-section active" id="tab-bsc">
<div class="bsc-grid">
@foreach($perspectivas as $key => $persp)
<div class="bsc-perspectiva">
    <div class="bsc-persp-header" style="background:{{ $persp['color'] }}">
        <h3><i class="fa {{ $persp['icon'] }}"></i> {{ $persp['label'] }}</h3>
        <div class="bsc-persp-badges">
            @php $totEjes = $persp['ejes']->count(); $totAcc = $persp['ejes']->sum('total'); @endphp
            <span class="bsc-persp-badge">{{ $totEjes }} {{ $totEjes == 1 ? 'eje' : 'ejes' }}</span>
            <span class="bsc-persp-badge">{{ $totAcc }} acciones</span>
        </div>
    </div>
    @foreach($persp['ejes'] as $eje)
    @php
        $ejePct = $eje['total'] > 0 ? round(($eje['verde'] / $eje['total']) * 100) : 0;
        $ejeColor = $eje['semaforo'] === 'verde' ? '#22c55e' : ($eje['semaforo'] === 'amarillo' ? '#eab308' : ($eje['semaforo'] === 'rojo' ? '#ef4444' : '#cbd5e1'));
    @endphp
    <div class="bsc-eje-item">
        <div class="bsc-eje-name">{{ $eje['name'] }}</div>
        @if($eje['ri'])
        <div class="bsc-eje-ri"><i class="fa fa-flag" style="font-size:.65rem;margin-right:.2rem"></i>{{ $eje['ri'] }}</div>
        @endif
        <div class="bsc-sem-row">
            <span class="sem-pill sem-pill-verde"><i class="fa fa-circle" style="font-size:.5rem"></i>{{ $eje['verde'] }}</span>
            <span class="sem-pill sem-pill-amarillo"><i class="fa fa-circle" style="font-size:.5rem"></i>{{ $eje['amarillo'] }}</span>
            <span class="sem-pill sem-pill-rojo"><i class="fa fa-circle" style="font-size:.5rem"></i>{{ $eje['rojo'] }}</span>
            <div class="sem-bar">
                <div class="sem-bar-fill" style="width:{{ $ejePct }}%;background:{{ $ejeColor }}"></div>
            </div>
            <span style="font-size:.7rem;font-weight:700;color:{{ $ejeColor }};min-width:28px;text-align:right">{{ $ejePct }}%</span>
        </div>
        {{-- Objetivos y acciones --}}
        <div class="bsc-acciones-list mt-2">
            @foreach($eje['objetivos'] as $obj)
            <div class="bsc-obj-label">
                <i class="fa fa-angle-right" style="color:#94a3b8"></i>
                {{ $obj['name'] }}
            </div>
            @foreach($obj['acciones'] as $acc)
            @php $as = $acc['semaforo'] === 'sin-datos' ? 'sin' : $acc['semaforo']; @endphp
            <div class="bsc-accion-row">
                <span class="sdot sdot-{{ $as }}"></span>
                <span>{{ $acc['name'] }}</span>
                @if($acc['indicador'])
                <span style="margin-left:auto;font-size:.67rem;color:#94a3b8;flex-shrink:0">{{ \Illuminate\Support\Str::limit($acc['indicador'], 30) }}</span>
                @endif
            </div>
            @endforeach
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endforeach
</div>
</div>

{{-- ── TAB MATRIZ ───────────────────────────────────────────────────────────── --}}
<div class="tab-section" id="tab-matriz">
<div class="mat-wrap">
<div class="table-responsive">
<table class="table table-hover mb-0">
    <thead>
        <tr>
            <th style="width:18%">{{ $niveles['axi'] ?? 'Eje' }}</th>
            <th style="width:22%">{{ $niveles['goal'] ?? 'Objetivo' }}</th>
            <th>{{ $niveles['action'] ?? 'Acción' }}</th>
            <th style="width:18%">Indicador</th>
            <th style="width:90px;text-align:center">Estado</th>
        </tr>
    </thead>
    <tbody>
    @foreach($profile->children->sortBy('order_item') as $axi)
        @php $axiRows = $axi->descendants()->where('level','action')->count(); @endphp
        @foreach($axi->children->sortBy('order_item') as $goal)
            @php $goalRows = $goal->children->count(); @endphp
            @foreach($goal->children->sortBy('order_item') as $action)
            @php $sem = $action->semaforo ?? 'sin-datos'; $as = $sem === 'sin-datos' ? 'sin' : $sem; @endphp
            <tr>
                @if($loop->parent->first && $loop->first)
                <td class="td-axi" rowspan="{{ $axiRows }}">{{ strip_tags($axi->name) }}</td>
                @endif
                @if($loop->first)
                <td class="td-obj" rowspan="{{ $goalRows }}">{{ strip_tags($goal->name) }}</td>
                @endif
                <td class="td-accion">{{ strip_tags($action->name) }}</td>
                <td class="td-ind">{{ $action->indicador ? $action->indicador->nombre : '—' }}</td>
                <td style="text-align:center">
                    @php
                        $semLabel = ['verde'=>'En meta','amarillo'=>'En proceso','rojo'=>'Crítico','sin-datos'=>'Sin datos'];
                        $semClass = ['verde'=>'sem-pill-verde','amarillo'=>'sem-pill-amarillo','rojo'=>'sem-pill-rojo','sin-datos'=>'sem-pill-sin'];
                    @endphp
                    <span class="sem-pill {{ $semClass[$sem] ?? 'sem-pill-sin' }}" style="white-space:nowrap">
                        <span class="sdot sdot-{{ $as }}"></span>
                        {{ $semLabel[$sem] ?? $sem }}
                    </span>
                </td>
            </tr>
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>
</div>
</div>
</div>

{{-- ── TAB MECIP / JERÁRQUICO ───────────────────────────────────────────────── --}}
<div class="tab-section" id="tab-mecip">
<div class="mecip-wrap">
@foreach($profile->children->sortBy('order_item') as $axi)
@php
    $accAxi = $axi->descendants()->where('level','action')->get();
    $vA = $accAxi->where('semaforo','verde')->count();
    $aA = $accAxi->where('semaforo','amarillo')->count();
    $rA = $accAxi->where('semaforo','rojo')->count();
    $tA = $accAxi->count();
    $pA = $tA > 0 ? round(($vA/$tA)*100) : 0;
    $cA = $pA >= 75 ? '#4ade80' : ($pA >= 50 ? '#fbbf24' : '#f87171');
@endphp
<div class="mecip-eje">
    <div class="mecip-eje-header">
        <div class="mecip-eje-name">{{ strip_tags($axi->name) }}</div>
        <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
            <span class="sem-pill sem-pill-verde"><span class="sdot sdot-verde"></span>{{ $vA }}</span>
            <span class="sem-pill sem-pill-amarillo"><span class="sdot sdot-amarillo"></span>{{ $aA }}</span>
            <span class="sem-pill sem-pill-rojo"><span class="sdot sdot-rojo"></span>{{ $rA }}</span>
            <span style="color:{{ $cA }};font-weight:800;font-size:.85rem;min-width:36px;text-align:right">{{ $pA }}%</span>
        </div>
    </div>
    <div class="mecip-eje-body">
        @foreach($axi->children->sortBy('order_item') as $goal)
        <div class="mecip-goal">
            <div class="mecip-goal-name">
                <i class="fa fa-angle-right" style="color:#94a3b8"></i>
                {{ strip_tags($goal->name) }}
            </div>
            @foreach($goal->children->sortBy('order_item') as $action)
            @php $sem = $action->semaforo ?? 'sin-datos'; $as = $sem === 'sin-datos' ? 'sin' : $sem; @endphp
            <div class="mecip-action-row">
                <span class="sdot sdot-{{ $as }}"></span>
                <span>{{ strip_tags($action->name) }}</span>
                @if($action->indicador)
                <span class="mecip-ind-badge">{{ $action->indicador->codigoCompleto() }}</span>
                @endif
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
@endforeach
</div>
</div>

</div>{{-- /pub-content --}}

<div class="pub-footer">
    <strong>SIPLAN</strong> · Sistema de Planificación Estratégica Institucional ·
    Vista pública generada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.pub-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.pub-tab').forEach(function(b){b.classList.remove('active')});
        document.querySelectorAll('.tab-section').forEach(function(s){s.classList.remove('active')});
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        window.location.hash = this.dataset.tab;
    });
});
var hash = window.location.hash.replace('#','');
if(hash){
    var btn = document.querySelector('.pub-tab[data-tab="'+hash+'"]');
    if(btn) btn.click();
}
</script>
</body></html>
