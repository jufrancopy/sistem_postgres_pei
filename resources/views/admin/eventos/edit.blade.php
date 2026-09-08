@extends('layouts.master')

@section('title', 'Editar Evento Institucional')

@section('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<style>
    /* ── Estilos de Formulario Estándar y Select2 ── */
    .form-group label.font-weight-bold {
        font-size: 0.88rem !important;
        font-weight: 700 !important;
        color: #2c3e50 !important;
        margin-bottom: 6px !important;
        display: block !important;
    }
    .form-control {
        background-color: #ffffff !important;
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        height: auto !important;
        font-size: 0.92rem !important;
        color: #495057 !important;
    }
    .form-control:focus {
        border-color: #00bcd4 !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.25) !important;
        background-color: #fff !important;
    }
    .select2-container--default .select2-selection--single {
        min-height: 42px !important;
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        background: #ffffff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        color: #2c3e50 !important;
        font-size: 0.92rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
    .select2-container--default .select2-selection--multiple {
        min-height: 42px !important;
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        background: #ffffff !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e0f2fe !important;
        border: 1px solid #0284c7 !important;
        color: #0369a1 !important;
        font-weight: 600 !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
        font-size: 0.82rem !important;
    }
    .card-form-section {
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }
    .card-form-section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #00bcd4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        border-bottom: 2px solid #e0f7fa;
        padding-bottom: 6px;
    }
</style>
@endsection

@section('content')
<div class="card">
    <!-- Header Estándar de la Plataforma -->
    <div class="card-header card-header-info d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-title font-weight-bold">Modificar Evento Institucional</h4>
            <p class="card-category">{{ $evento->nombre }}</p>
        </div>
        <div>
            <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-warning btn-sm font-weight-bold">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Detalle
            </a>
        </div>
    </div>

    <!-- Breadcrumb Estándar -->
    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4 mx-3 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('eventos.index') }}">Eventos Institucionales</a></li>
            <li class="breadcrumb-item"><a href="{{ route('eventos.show', $evento->id) }}">{{ \Illuminate\Support\Str::limit($evento->nombre, 35) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>

    <div class="card-body px-4 py-2">
        <form action="{{ route('eventos.update', $evento->id) }}" method="POST" id="formEditEvento">
            @csrf
            @method('PUT')

            <!-- SECCIÓN 1: IDENTIFICACIÓN DEL EVENTO -->
            <div class="card-form-section">
                <div class="card-form-section-title">
                    <i class="fa fa-info-circle mr-1"></i> 1. Información General del Evento
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre del Evento o Hito Estratégico <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $evento->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Tipo de Evento <span class="text-danger">*</span></label>
                            <select name="tipo" class="form-control select2" style="width: 100%;">
                                @foreach(['Taller', 'Jornada', 'Reunión', 'Congreso', 'Lanzamiento', 'Evaluación', 'Otro'] as $t)
                                    <option value="{{ $t }}" {{ old('tipo', $evento->tipo) == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fa fa-bullseye text-info mr-1"></i> Plan PEI Vinculado</label>
                            <select name="pei_profile_id" class="form-control select2" style="width: 100%;">
                                <option value="">— Ninguno / Evento Institucional General —</option>
                                @foreach($peiPerfiles as $pei)
                                    <option value="{{ $pei->id }}" {{ (old('pei_profile_id', $evento->pei_profile_id) == $pei->id) ? 'selected' : '' }}>
                                        {{ $pei->name ?? $pei->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fa fa-map-marker-alt text-danger mr-1"></i> Lugar o Sede</label>
                            <input type="text" name="lugar_sede" class="form-control" 
                                   value="{{ old('lugar_sede', $evento->lugar_sede) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: VIGENCIA, FECHAS Y ESTADO -->
            <div class="card-form-section">
                <div class="card-form-section-title">
                    <i class="fa fa-calendar-alt mr-1"></i> 2. Fechas, Calendario y Estado
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="far fa-calendar-alt text-primary mr-1"></i> Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                   value="{{ old('fecha_inicio', $evento->fecha_inicio ? $evento->fecha_inicio->format('Y-m-d') : '') }}">
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="far fa-calendar-check text-success mr-1"></i> Fecha de Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                   value="{{ old('fecha_fin', $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d') : '') }}">
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold">Estado Actual</label>
                            <select name="estado" class="form-control select2" style="width: 100%;">
                                <option value="planificado" {{ old('estado', $evento->estado) == 'planificado' ? 'selected' : '' }}>Planificado</option>
                                <option value="en_curso" {{ old('estado', $evento->estado) == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="completado" {{ old('estado', $evento->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="cancelado" {{ old('estado', $evento->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold">Color en Calendario</label>
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                <input type="color" name="color" class="form-control p-1" style="height: 38px; width: 55px; cursor: pointer;" value="{{ old('color', $evento->color ?: '#00bcd4') }}">
                                <small class="text-muted">Color en agenda</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: PRESUPUESTO Y EQUIPO -->
            <div class="card-form-section">
                <div class="card-form-section-title">
                    <i class="fa fa-users-cog mr-1"></i> 3. Presupuesto y Equipo Responsable
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-coins text-warning mr-1"></i> Presupuesto Estimado (Gs.)</label>
                            <input type="number" step="0.01" name="presupuesto_estimado" class="form-control font-weight-bold" 
                                   value="{{ old('presupuesto_estimado', $evento->presupuesto_estimado) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-receipt text-secondary mr-1"></i> Presupuesto Ejecutado (Gs.)</label>
                            <input type="number" step="0.01" name="presupuesto_ejecutado" class="form-control font-weight-bold" 
                                   value="{{ old('presupuesto_ejecutado', $evento->presupuesto_ejecutado) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-users text-primary mr-1"></i> Equipo Organizador</label>
                            @php $selectedResp = $evento->responsables->pluck('id')->toArray(); @endphp
                            <select name="responsables[]" class="form-control select2" multiple style="width: 100%;">
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" {{ in_array($u->id, old('responsables', $selectedResp)) ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Descripción, Justificación y Objetivos Esperados</label>
                            <textarea name="descripcion" rows="3" class="form-control">{{ old('descripcion', $evento->descripcion) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="d-flex justify-content-end align-items-center mt-4 mb-3" style="gap: 12px;">
                <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-secondary font-weight-bold px-4">
                    <i class="fa fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success font-weight-bold px-5">
                    <i class="fa fa-save mr-1"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        placeholder: 'Seleccionar...'
    });
});
</script>
@endsection
