@extends('layouts.master')
@section('title', 'Editar Grado de Complejidad')

@section('content')

{{-- Hero --}}
<div style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);border-radius:0 0 16px 16px;padding:24px 28px 20px;color:#fff;margin-bottom:24px">
    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px">
        <div>
            <div class="d-flex align-items-center mb-1" style="gap:8px">
                <span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:{{ $tipo->color ?? '#6b7280' }};border:2px solid rgba(255,255,255,.5)"></span>
                <span style="font-size:.75rem;font-weight:700;opacity:.8;text-transform:uppercase;letter-spacing:.5px">Grado {{ $tipo->grado }}</span>
            </div>
            <div style="font-size:1.4rem;font-weight:700">{{ $tipo->nombre }}</div>
            <div style="font-size:.82rem;opacity:.75;margin-top:2px">
                <i class="fa fa-layer-group mr-1"></i>Nivel de Atención {{ $tipo->nivel_atencion }}
                &nbsp;·&nbsp;
                <i class="fa fa-circle mr-1" style="color:{{ $tipo->activo ? '#10b981' : '#ef4444' }}"></i>{{ $tipo->activo ? 'Activo' : 'Inactivo' }}
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
        <li class="breadcrumb-item"><a href="{{ route('riiss.complejidad.index') }}">Grados de Complejidad</a></li>
        <li class="breadcrumb-item active">Editar Grado {{ $tipo->grado }}</li>
    </ol>
</nav>

<form action="{{ route('riiss.complejidad.update', $tipo) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Datos principales --}}
    <div class="card shadow-sm mb-4" style="border-radius:12px;border:none">
        <div class="card-body" style="padding:24px">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:16px">
                <i class="fa fa-info-circle mr-1"></i>Datos del Grado
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="small font-weight-bold">Nombre del Grado <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $tipo->nombre) }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="small font-weight-bold">Nivel de Atención <span class="text-danger">*</span></label>
                        <select name="nivel_atencion" id="sel_nivel" class="form-control @error('nivel_atencion') is-invalid @enderror" required>
                            @foreach([1,2,3,4] as $n)
                                <option value="{{ $n }}" {{ old('nivel_atencion', $tipo->nivel_atencion) == $n ? 'selected' : '' }}>
                                    Nivel {{ $n }}
                                </option>
                            @endforeach
                        </select>
                        @error('nivel_atencion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="small font-weight-bold">Color</label>
                        <input type="color" name="color" class="form-control"
                               value="{{ old('color', $tipo->color) }}" style="height:38px;padding:2px 4px">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="small font-weight-bold">Tipo de Establecimiento</label>
                        <input type="text" name="tipo_establecimiento" class="form-control"
                               value="{{ old('tipo_establecimiento', $tipo->tipo_establecimiento) }}"
                               placeholder="Ej: Hospital General Regional">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="small font-weight-bold">Estado</label>
                        <select name="activo" id="sel_activo" class="form-control">
                            <option value="1" {{ old('activo', $tipo->activo) ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !old('activo', $tipo->activo) ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Requisitos --}}
    <div class="card shadow-sm mb-4" style="border-radius:12px;border:none">
        <div class="card-body" style="padding:24px">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:16px">
                <i class="fa fa-hospital mr-1"></i>Requisitos del Establecimiento
            </div>
            <div class="row">
                @foreach([
                    ['es_hospitalario',      'fa-h-square',       'Es Hospitalario'],
                    ['requiere_internacion', 'fa-bed',            'Requiere Internación'],
                    ['requiere_quirofano',   'fa-cut',            'Requiere Quirófano'],
                    ['requiere_uti',         'fa-heartbeat',      'Requiere UTI'],
                    ['requiere_urgencias',   'fa-ambulance',      'Requiere Urgencias'],
                ] as [$field, $icon, $label])
                <div class="col-md-4 mb-3">
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" class="custom-control-input" id="{{ $field }}"
                               name="{{ $field }}" value="1"
                               {{ old($field, $tipo->$field) ? 'checked' : '' }}>
                        <label class="custom-control-label small font-weight-bold" for="{{ $field }}">
                            <i class="fa {{ $icon }} mr-1 text-primary"></i>{{ $label }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Alerta --}}
    <div class="alert d-flex align-items-center mb-4" style="background:#fff7ed;border:1px solid #fed7aa;border-radius:10px;color:#92400e">
        <i class="fa fa-exclamation-triangle fa-lg mr-3" style="color:#f97316;flex-shrink:0"></i>
        <div style="font-size:.85rem">
            Si cambia el <strong>Nivel de Atención</strong>, se recalcularán automáticamente los
            <strong>{{ $tipo->establecimientos()->whereNull('deleted_at')->count() }}</strong> establecimientos asignados a este grado.
        </div>
    </div>

    {{-- Acciones --}}
    <div class="d-flex justify-content-between">
        <a href="{{ route('riiss.complejidad.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left mr-1"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary px-4">
            <i class="fa fa-save mr-1"></i>Guardar Cambios
        </button>
    </div>

</form>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#sel_nivel, #sel_activo').select2({ minimumResultsForSearch: Infinity });
});
</script>
@endpush
