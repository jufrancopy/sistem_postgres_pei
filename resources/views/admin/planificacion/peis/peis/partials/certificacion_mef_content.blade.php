{{-- ── Cabecera / Resumen del Plan ── --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%); border-radius: 12px;">
    <div class="card-body p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h5 class="font-weight-bold text-dark mb-1">
                    <i class="fa fa-certificate text-warning mr-2"></i>{{ strip_tags($profile->name) }}
                </h5>
                <div class="text-muted small">
                    <i class="fa fa-calendar-alt mr-1"></i>
                    Vigencia: {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }} - {{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
                    @if($profile->group)
                        <span class="mx-2">•</span>
                        <i class="fa fa-users mr-1"></i>{{ $profile->group->name }}
                    @endif
                </div>
            </div>
            <div class="mt-2 mt-md-0 text-md-right">
                <span class="badge {{ $pct == 100 ? 'badge-success' : ($pct >= 60 ? 'badge-warning' : 'badge-danger') }} p-2 px-3 shadow-sm" style="font-size: 0.95rem; border-radius: 20px;">
                    <i class="fa {{ $pct == 100 ? 'fa-check-circle' : 'fa-hourglass-half' }} mr-1"></i>
                    {{ $completados }}/{{ $total }} Matrices ({{ $pct }}%)
                </span>
            </div>
        </div>

        {{-- Barra de Progreso --}}
        <div class="mt-3">
            <div class="progress" style="height: 10px; border-radius: 6px; background-color: #e2e8f0;">
                <div class="progress-bar {{ $pct == 100 ? 'bg-success' : ($pct >= 60 ? 'bg-warning' : 'bg-danger') }}"
                     role="progressbar"
                     style="width: {{ $pct }}%; transition: width 0.6s ease;"
                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        @if($pct == 100)
            <div class="alert alert-success d-flex align-items-center mb-0 mt-3 p-2 px-3" style="border-radius: 8px;">
                <i class="fa fa-check-circle fa-lg mr-2"></i>
                <div><strong>¡PEI listo para certificación MEF!</strong> Todas las matrices y marcos requeridos están completos y conformes.</div>
            </div>
        @else
            <div class="alert alert-warning d-flex align-items-center mb-0 mt-3 p-2 px-3" style="border-radius: 8px;">
                <i class="fa fa-exclamation-triangle mr-2 text-warning"></i>
                <div>Faltan <strong>{{ $total - $completados }}</strong> de las {{ $total }} matrices obligatorias para completar la certificación según el estándar MEF.</div>
            </div>
        @endif
    </div>
</div>

{{-- ── Tabla de Verificación de Matrices ── --}}
<div class="table-responsive shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <table class="table table-hover table-striped align-middle mb-0" style="background-color: #ffffff;">
        <thead style="background: #1e293b; color: #ffffff;">
            <tr>
                <th style="width: 50px;" class="text-center">#</th>
                <th style="width: 32%;">Matriz / Estándar MEF</th>
                <th style="width: 16%;" class="text-center">Estado</th>
                <th style="width: 37%;">Detalle y Cobertura</th>
                <th style="width: 15%; text-align: center;">Acceso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($checklist as $item)
            <tr class="{{ $item['ok'] ? '' : ($item['pendiente'] ? 'table-secondary' : '') }}">
                <td class="text-center font-weight-bold align-middle">
                    <span class="badge {{ $item['ok'] ? 'badge-light text-success' : 'badge-light text-muted' }}" style="font-size: 0.85rem; border: 1px solid #e2e8f0;">
                        {{ $item['num'] }}
                    </span>
                </td>
                <td class="align-middle font-weight-bold text-dark">
                    {{ $item['label'] }}
                </td>
                <td class="text-center align-middle">
                    @if($item['pendiente'])
                        <span class="badge badge-secondary p-1 px-2" style="border-radius: 12px;"><i class="fa fa-wrench mr-1"></i>En desarrollo</span>
                    @elseif($item['ok'])
                        <span class="badge badge-success p-1 px-2" style="border-radius: 12px;"><i class="fa fa-check mr-1"></i>Completo</span>
                    @else
                        <span class="badge badge-warning p-1 px-2 text-dark font-weight-bold" style="border-radius: 12px; background-color: #fef08a;"><i class="fa fa-clock mr-1"></i>Pendiente</span>
                    @endif
                </td>
                <td class="align-middle small text-secondary">
                    {{ $item['valor'] }}
                </td>
                <td class="text-center align-middle">
                    @if($item['url'])
                        <a href="{{ $item['url'] }}" class="btn btn-sm btn-outline-primary shadow-xs px-2 py-1" target="_blank" title="Abrir módulo en nueva pestaña" style="border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fa fa-external-link-alt mr-1"></i> Ir a Matriz
                        </a>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
