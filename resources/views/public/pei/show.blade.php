<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strip_tags($profile->name) }} — Vista Pública</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background:#f4f6fb; font-family:'Segoe UI',sans-serif; }

        .pub-header {
            background: linear-gradient(135deg,#1a237e 0%,#283593 100%);
            color: #fff;
            padding: 1.5rem 2rem;
        }
        .pub-header .plan-name { font-size:1.15rem; font-weight:700; line-height:1.3; }
        .pub-header .plan-meta { font-size:.78rem; opacity:.7; margin-top:.3rem; }
        .pub-header .watermark {
            font-size:.65rem; opacity:.4; text-transform:uppercase;
            letter-spacing:.08em; margin-top:.75rem;
        }

        /* Tabs */
        .pub-tabs { background:#fff; border-bottom:2px solid #e0e0e0; padding:0 1.5rem; position:sticky; top:0; z-index:100; }
        .pub-tabs .nav-link {
            color:#555; font-weight:600; font-size:.82rem;
            padding:.75rem 1.25rem; border:none; border-bottom:3px solid transparent;
            border-radius:0; transition:all .15s;
        }
        .pub-tabs .nav-link:hover { color:#1a237e; }
        .pub-tabs .nav-link.active { color:#1a237e; border-bottom-color:#1a237e; background:transparent; }

        /* Semáforo */
        .sem-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
        .sem-verde    { background:#28a745; }
        .sem-amarillo { background:#ffc107; }
        .sem-rojo     { background:#dc3545; }
        .sem-sin      { background:#adb5bd; }

        /* BSC card */
        .bsc-card {
            border-radius:.6rem; overflow:hidden;
            box-shadow:0 1px 6px rgba(0,0,0,.08);
            margin-bottom:1.25rem;
        }
        .bsc-card-header {
            padding:.6rem 1rem; font-weight:700; font-size:.78rem;
            text-transform:uppercase; letter-spacing:.05em; color:#fff;
        }
        .bsc-eje {
            padding:.6rem 1rem; border-bottom:1px solid #f0f0f0;
            font-size:.82rem;
        }
        .bsc-eje:last-child { border-bottom:none; }

        /* Matriz */
        .mat-table th { font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; background:#f8f9ff; }
        .mat-table td { font-size:.8rem; vertical-align:middle; }

        /* MECIP card */
        .mecip-obj { border-left:4px solid #1a237e; padding:.5rem .75rem; background:#fff; margin-bottom:.5rem; border-radius:0 .4rem .4rem 0; }
        .mecip-action { padding:.3rem .5rem .3rem 1.25rem; font-size:.8rem; color:#444; border-bottom:1px solid #f5f5f5; }
        .mecip-action:last-child { border-bottom:none; }

        footer { text-align:center; padding:1.5rem; font-size:.72rem; color:#aaa; margin-top:2rem; }
    </style>
</head>
<body>

{{-- CABECERA --}}
<div class="pub-header">
    <div class="container-fluid" style="max-width:1100px">
        <div class="d-flex align-items-start flex-wrap" style="gap:1rem">
            <div style="flex:1;min-width:0">
                <div style="font-size:.65rem;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem">
                    <i class="fa fa-eye mr-1"></i>Vista pública — solo lectura
                </div>
                <div class="plan-name">{!! strip_tags($profile->name) !!}</div>
                <div class="plan-meta">
                    <i class="fa fa-calendar-alt mr-1"></i>
                    {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }} –
                    {{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
                    @if($profile->dependency)
                    &nbsp;·&nbsp;<i class="fa fa-building mr-1"></i>{{ $profile->dependency->dependency }}
                    @endif
                </div>
            </div>
            {{-- Semáforo global --}}
            @php
                $todasAcciones = $profile->descendants()->where('level','action')->get();
                $gVerde    = $todasAcciones->where('semaforo','verde')->count();
                $gAmarillo = $todasAcciones->where('semaforo','amarillo')->count();
                $gRojo     = $todasAcciones->where('semaforo','rojo')->count();
                $gTotal    = $todasAcciones->count();
            @endphp
            <div class="d-flex text-center" style="gap:1.25rem;flex-shrink:0">
                <div>
                    <div style="font-size:1.5rem;font-weight:700;color:#69f0ae">{{ $gVerde }}</div>
                    <div style="font-size:.65rem;opacity:.7">Verde</div>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:700;color:#ffd54f">{{ $gAmarillo }}</div>
                    <div style="font-size:.65rem;opacity:.7">Amarillo</div>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:700;color:#ff5252">{{ $gRojo }}</div>
                    <div style="font-size:.65rem;opacity:.7">Rojo</div>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:700">{{ $gTotal }}</div>
                    <div style="font-size:.65rem;opacity:.7">Total</div>
                </div>
            </div>
        </div>
        <div class="watermark"><i class="fa fa-lock mr-1"></i>Acceso mediante enlace autorizado · SIPLAN</div>
    </div>
</div>

{{-- TABS --}}
<div class="pub-tabs">
    <div class="container-fluid" style="max-width:1100px">
        <ul class="nav" id="pubTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#tab-bsc" data-toggle="tab">
                    <i class="fa fa-th-large mr-1"></i>BSC
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab-matriz" data-toggle="tab">
                    <i class="fa fa-table mr-1"></i>Matriz Estratégica
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab-mecip" data-toggle="tab">
                    <i class="fa fa-shield-alt mr-1"></i>MECIP
                </a>
            </li>
        </ul>
    </div>
</div>

{{-- CONTENIDO --}}
<div class="container-fluid py-4" style="max-width:1100px">
<div class="tab-content">

    {{-- ══ TAB BSC ═══════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade show active" id="tab-bsc">
        <div class="row">
        @foreach($perspectivas as $key => $persp)
        <div class="col-md-6 mb-3">
            <div class="bsc-card">
                <div class="bsc-card-header" style="background:{{ $persp['color'] }}">
                    <i class="fa {{ $persp['icon'] }} mr-2"></i>{{ $persp['label'] }}
                    <span class="float-right" style="font-weight:400;font-size:.72rem;opacity:.85">
                        {{ $persp['ejes']->count() }} {{ $persp['ejes']->count() == 1 ? 'eje' : 'ejes' }}
                    </span>
                </div>
                @foreach($persp['ejes'] as $eje)
                <div class="bsc-eje">
                    <div class="d-flex align-items-center justify-content-between">
                        <div style="flex:1;min-width:0">
                            <div class="font-weight-bold" style="color:#333">{{ $eje['name'] }}</div>
                            @if($eje['ri'])
                            <div class="text-muted mt-1" style="font-size:.73rem;font-style:italic">
                                {{ $eje['ri'] }}
                            </div>
                            @endif
                        </div>
                        <div class="d-flex flex-shrink-0 ml-2" style="gap:.35rem;align-items:center">
                            <span class="badge badge-success" style="font-size:.65rem">{{ $eje['verde'] }}</span>
                            <span class="badge badge-warning" style="font-size:.65rem">{{ $eje['amarillo'] }}</span>
                            <span class="badge badge-danger" style="font-size:.65rem">{{ $eje['rojo'] }}</span>
                            <span class="sem-dot sem-{{ $eje['semaforo'] === 'sin-datos' ? 'sin' : $eje['semaforo'] }} ml-1"></span>
                        </div>
                    </div>
                    {{-- Objetivos desplegables --}}
                    @foreach($eje['objetivos'] as $obj)
                    <div class="mt-1 pl-2" style="border-left:2px solid #e0e0e0">
                        <div style="font-size:.76rem;color:#555;font-weight:600">{{ $obj['name'] }}</div>
                        @foreach($obj['acciones'] as $acc)
                        <div class="d-flex align-items-center py-1" style="gap:.4rem;border-bottom:1px dashed #f0f0f0">
                            <span class="sem-dot sem-{{ $acc['semaforo'] === 'sin-datos' ? 'sin' : $acc['semaforo'] }} flex-shrink-0"></span>
                            <span style="font-size:.74rem;color:#444">{{ $acc['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        </div>
    </div>

    {{-- ══ TAB MATRIZ ═════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="tab-matriz">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-hover table-sm mat-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:200px">{{ $niveles['axi'] ?? 'Eje' }}</th>
                            <th style="width:220px">{{ $niveles['goal'] ?? 'Objetivo' }}</th>
                            <th>{{ $niveles['action'] ?? 'Acción' }}</th>
                            <th style="width:150px">Indicador</th>
                            <th style="width:80px" class="text-center">Semáforo</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($profile->children->sortBy('order_item') as $axi)
                        @php $axiCount = $axi->descendants()->where('level','action')->count(); @endphp
                        @foreach($axi->children->sortBy('order_item') as $goal)
                            @php $goalCount = $goal->children->count(); @endphp
                            @foreach($goal->children->sortBy('order_item') as $idx => $action)
                            <tr>
                                @if($loop->parent->first && $loop->first)
                                <td rowspan="{{ $axiCount }}" style="background:#f0f4ff;font-weight:700;color:#1a237e;font-size:.78rem;vertical-align:top;padding:.6rem">
                                    {{ strip_tags($axi->name) }}
                                </td>
                                @endif
                                @if($loop->first)
                                <td rowspan="{{ $goalCount }}" style="font-size:.78rem;vertical-align:top;padding:.6rem;color:#333">
                                    {{ strip_tags($goal->name) }}
                                </td>
                                @endif
                                <td style="font-size:.78rem">{{ strip_tags($action->name) }}</td>
                                <td style="font-size:.72rem;color:#555">
                                    {{ $action->indicador ? $action->indicador->nombre : '—' }}
                                </td>
                                <td class="text-center">
                                    @php $sem = $action->semaforo ?? 'sin-datos'; @endphp
                                    <span class="sem-dot sem-{{ $sem === 'sin-datos' ? 'sin' : $sem }}"
                                          title="{{ ucfirst($sem) }}" style="width:14px;height:14px"></span>
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
    </div>

    {{-- ══ TAB MECIP ══════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="tab-mecip">
        <div class="alert alert-info border-0 shadow-sm" style="font-size:.82rem">
            <i class="fa fa-info-circle mr-2"></i>
            Vista de alineación al <strong>MECIP 2015</strong> — Modelo Estándar de Control Interno Paraguayo.
            Los objetivos estratégicos se organizan según los componentes del sistema de control interno.
        </div>

        @foreach($profile->children->sortBy('order_item') as $axi)
        <div class="mecip-obj mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge mr-2" style="background:#1a237e;color:#fff;font-size:.65rem">
                        {{ $niveles['axi'] ?? 'OE' }}
                    </span>
                    <strong style="font-size:.85rem;color:#1a237e">{{ strip_tags($axi->name) }}</strong>
                </div>
                @php
                    $accAxi = $axi->descendants()->where('level','action')->get();
                    $vAxi   = $accAxi->where('semaforo','verde')->count();
                    $aAxi   = $accAxi->where('semaforo','amarillo')->count();
                    $rAxi   = $accAxi->where('semaforo','rojo')->count();
                @endphp
                <div class="d-flex" style="gap:.3rem">
                    <span class="badge badge-success" style="font-size:.65rem">{{ $vAxi }}</span>
                    <span class="badge badge-warning" style="font-size:.65rem">{{ $aAxi }}</span>
                    <span class="badge badge-danger" style="font-size:.65rem">{{ $rAxi }}</span>
                </div>
            </div>
            @foreach($axi->children->sortBy('order_item') as $goal)
            <div class="mt-2 pl-2">
                <div style="font-size:.75rem;color:#555;font-weight:600;margin-bottom:.3rem">
                    <i class="fa fa-angle-right mr-1 text-muted"></i>{{ strip_tags($goal->name) }}
                </div>
                @foreach($goal->children->sortBy('order_item') as $action)
                @php $sem = $action->semaforo ?? 'sin-datos'; @endphp
                <div class="mecip-action d-flex align-items-center" style="gap:.5rem">
                    <span class="sem-dot sem-{{ $sem === 'sin-datos' ? 'sin' : $sem }} flex-shrink-0"></span>
                    <span>{{ strip_tags($action->name) }}</span>
                    @if($action->indicador)
                    <span class="badge badge-light border ml-auto flex-shrink-0" style="font-size:.65rem">
                        {{ $action->indicador->codigo }}
                    </span>
                    @endif
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

</div>{{-- /tab-content --}}
</div>{{-- /container --}}

<footer>
    <i class="fa fa-lock mr-1"></i>
    Acceso público autorizado · SIPLAN — Sistema de Planificación Estratégica Institucional ·
    Generado {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script>
// Activar tab desde URL hash
$(function() {
    var hash = window.location.hash;
    if (hash) {
        $('#pubTabs a[href="' + hash + '"]').tab('show');
    }
    $('#pubTabs a').on('shown.bs.tab', function(e) {
        window.location.hash = $(e.target).attr('href');
    });
});
</script>
</body>
</html>
