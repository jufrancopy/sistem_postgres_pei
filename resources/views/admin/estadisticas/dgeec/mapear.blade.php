@extends('layouts.master')
@section('title', 'Mapear columnas EPHC')

@section('content')
<div class="card">
    <div class="card-header card-header-warning">
        <h4 class="card-title"><i class="fa fa-columns mr-2"></i>Interpretar columnas con Diccionario EPHC</h4>
        <p class="card-category">Dataset: <strong>{{ $dataset->titulo }}</strong> ({{ $dataset->anio }}) — {{ $dataset->total_filas }} registros</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">Estadísticas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siess.eph.index') }}">EPH</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siess.eph.show', $dataset->id) }}">{{ Str::limit($dataset->titulo, 30) }}</a></li>
            <li class="breadcrumb-item active">Mapear EPHC</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Alerta FEX ── --}}
        <div class="alert alert-warning border-left-warning">
            <h6><i class="fa fa-exclamation-triangle mr-2"></i>Regla crítica: Factor de Expansión (FEX)</h6>
            <p class="mb-0 small">
                La EPHC es una <strong>muestra</strong>, no un censo. Cada persona tiene un peso estadístico (FEX).
                El sistema usará <code>SUM(FEX)</code> para proyectar los números a nivel nacional.
                <strong>Nunca se hace COUNT() de registros.</strong>
                @if(isset($mapeoSugerido['fex']))
                    <span class="badge badge-success ml-2"><i class="fa fa-check mr-1"></i>FEX detectado: {{ $mapeoSugerido['fex'] }}</span>
                @else
                    <span class="badge badge-danger ml-2"><i class="fa fa-times mr-1"></i>FEX no detectado — seleccionalo manualmente abajo</span>
                @endif
            </p>
        </div>

        {{-- ── Muestra de datos ── --}}
        <div class="card mb-4">
            <div class="card-header py-2 bg-light">
                <h6 class="mb-0 small font-weight-bold">Vista previa del dataset (primeras 5 filas)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0" style="font-size:.75rem">
                        <thead class="thead-dark">
                            <tr>
                                @foreach(array_keys((array)($datosMuestra[0] ?? [])) as $col)
                                <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datosMuestra as $fila)
                            <tr>
                                @foreach((array)$fila as $val)
                                <td>{{ $val }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Formulario de mapeo ── --}}
        <form action="{{ route('siess.dgeec.procesar', $dataset->id) }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="font-weight-bold">Año de los datos <span class="text-danger">*</span></label>
                    <input type="number" name="anio" class="form-control" value="{{ $dataset->anio }}" required>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Período de referencia</label>
                    <input type="text" name="periodo_referencia" class="form-control" placeholder="Ej: 2024-III" value="{{ $dataset->anio }}">
                    <small class="text-muted">Trimestre o período de la encuesta</small>
                </div>
            </div>

            <hr>
            <h6 class="font-weight-bold text-uppercase text-muted mb-3" style="font-size:.75rem; letter-spacing:.05em">
                Mapeo de columnas del dataset → Variables del diccionario EPHC
            </h6>

            @foreach($variablesEphc as $grupo => $variables)
            <div class="card mb-3">
                <div class="card-header py-2 bg-light">
                    <h6 class="mb-0 small font-weight-bold">
                        @switch($grupo)
                            @case('GEOGRAFIA') <i class="fa fa-map-marker mr-1 text-info"></i> Geografía @break
                            @case('POBLACION') <i class="fa fa-users mr-1 text-primary"></i> Población (PEA) @break
                            @case('INFORMALIDAD') <i class="fa fa-exclamation-circle mr-1 text-danger"></i> Informalidad Laboral @break
                            @case('CATEGORIA_OCUP') <i class="fa fa-briefcase mr-1 text-warning"></i> Categoría Ocupacional @break
                            @case('INGRESOS') <i class="fa fa-money-bill mr-1 text-success"></i> Ingresos @break
                            @case('POBREZA') <i class="fa fa-home mr-1 text-secondary"></i> Pobreza @break
                            @case('JUBILACION') <i class="fa fa-user-clock mr-1 text-muted"></i> Pre-Jubilación @break
                        @endswitch
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($variables as $varCode => $varInfo)
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold">
                                <code>{{ $varCode }}</code> — {{ $varInfo['nombre'] }}
                                @if($varInfo['requerido'] ?? false)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <select name="{{ strtolower($varCode) }}_column"
                                    class="form-control form-control-sm {{ ($varInfo['requerido'] ?? false) ? 'border-warning' : '' }}">
                                <option value="">— No disponible en este dataset —</option>
                                @foreach($columnasDetectadas as $col)
                                <option value="{{ $col }}"
                                    {{ isset($mapeoSugerido[strtolower($varCode)]) && $mapeoSugerido[strtolower($varCode)] === $col ? 'selected' : '' }}>
                                    {{ $col }}
                                    @if(strtoupper($col) === $varCode) ✓ @endif
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">{{ $varInfo['descripcion'] }}</small>
                            @if(isset($varInfo['valores']))
                            <small class="text-info d-block">
                                Valores: {{ collect($varInfo['valores'])->take(4)->map(fn($v,$k) => "{$k}={$v}")->implode(', ') }}
                                {{ count($varInfo['valores']) > 4 ? '...' : '' }}
                            </small>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            {{-- ── Nota sobre categorías 7 y 8 ── --}}
            <div class="alert alert-info small">
                <i class="fa fa-info-circle mr-1"></i>
                <strong>Recodificación A15:</strong> Las categorías 7 y 8 (trabajadores en el extranjero) serán
                <strong>excluidas automáticamente</strong> del análisis de cobertura nacional del IPS,
                según las reglas de recodificación EPHC 2018+.
            </div>

            <div class="text-right">
                <a href="{{ route('siess.eph.show', $dataset->id) }}" class="btn btn-secondary mr-2">
                    <i class="fa fa-arrow-left mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-cogs mr-1"></i> Procesar y calcular KPIs
                </button>
            </div>
        </form>

    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    // Resaltar columnas con detección automática
    $('select option:selected').each(function() {
        if ($(this).text().includes('✓')) {
            $(this).closest('select').addClass('border-success');
        }
    });
});
</script>
@stop
