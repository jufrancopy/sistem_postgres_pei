@extends('layouts.master')

@section('title', 'Crear Nuevo Evento Institucional')

@section('head')
<style>
    .form-group label.control-label,
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
    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
        min-height: 40px !important;
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: 30px !important;
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
            <h4 class="card-title font-weight-bold">Crear Nuevo Evento Institucional o Hito</h4>
            <p class="card-category">Definición de datos generales, objetivos, responsables y presupuesto</p>
        </div>
        <a href="{{ route('eventos.index') }}" class="btn btn-warning btn-sm font-weight-bold">
            <i class="fa fa-arrow-left mr-1"></i> Volver a Eventos
        </a>
    </div>

    <!-- Breadcrumb Estándar -->
    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4 mx-3 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('eventos.index') }}">Eventos Institucionales</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo Evento</li>
        </ol>
    </nav>

    <div class="card-body px-4 py-2">
        <form action="{{ route('eventos.store') }}" method="POST" id="formCreateEvento">
            @csrf

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
                                   placeholder="Ej: Taller de Socialización del Plan Estratégico Institucional 2026–2028" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Tipo de Evento <span class="text-danger">*</span></label>
                            <select name="tipo" class="form-control select2" style="width: 100%;">
                                <option value="Taller" {{ old('tipo') == 'Taller' ? 'selected' : '' }}>Taller</option>
                                <option value="Jornada" {{ old('tipo') == 'Jornada' ? 'selected' : '' }}>Jornada de Trabajo</option>
                                <option value="Reunión" {{ old('tipo') == 'Reunión' ? 'selected' : '' }}>Reunión de Alta Gerencia</option>
                                <option value="Congreso" {{ old('tipo') == 'Congreso' ? 'selected' : '' }}>Congreso / Simposio</option>
                                <option value="Lanzamiento" {{ old('tipo') == 'Lanzamiento' ? 'selected' : '' }}>Lanzamiento Institucional</option>
                                <option value="Evaluación" {{ old('tipo') == 'Evaluación' ? 'selected' : '' }}>Evaluación de Metas</option>
                                <option value="Otro" {{ old('tipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fa fa-bullseye text-info mr-1"></i> Vincular a Plan PEI (Opcional)</label>
                            <select name="pei_profile_id" class="form-control select2" style="width: 100%;">
                                <option value="">— Ninguno / Evento Institucional General —</option>
                                @foreach($peiPerfiles as $pei)
                                    <option value="{{ $pei->id }}" {{ (old('pei_profile_id', $selectedPeiId) == $pei->id) ? 'selected' : '' }}>
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
                                   placeholder="Ej: Centro de Convenciones Ykua Satí / Edificio Central IPS" 
                                   value="{{ old('lugar_sede') }}">
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
                                   value="{{ old('fecha_inicio') }}">
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="far fa-calendar-check text-success mr-1"></i> Fecha de Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                   value="{{ old('fecha_fin') }}">
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold">Estado Inicial</label>
                            <select name="estado" class="form-control select2" style="width: 100%;">
                                <option value="planificado" {{ old('estado') == 'planificado' ? 'selected' : '' }}>Planificado</option>
                                <option value="en_curso" {{ old('estado') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold">Color en Calendario</label>
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                <input type="color" name="color" class="form-control p-1" style="height: 38px; width: 55px; cursor: pointer;" value="{{ old('color', '#00bcd4') }}">
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
                                   placeholder="0" value="{{ old('presupuesto_estimado', 0) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-receipt text-secondary mr-1"></i> Presupuesto Ejecutado (Gs.)</label>
                            <input type="number" step="0.01" name="presupuesto_ejecutado" class="form-control font-weight-bold" 
                                   placeholder="0" value="{{ old('presupuesto_ejecutado', 0) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-users text-primary mr-1"></i> Equipo Organizador</label>
                            <select name="responsables[]" class="form-control select2" multiple style="width: 100%;">
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" {{ (is_array(old('responsables')) && in_array($u->id, old('responsables'))) ? 'selected' : '' }}>
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
                            <textarea name="descripcion" rows="3" class="form-control" 
                                      placeholder="Describe los objetivos, alcance esperado, insumos y dinámicas del evento...">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="d-flex justify-content-end align-items-center mt-4 mb-3" style="gap: 12px;">
                <a href="{{ route('eventos.index') }}" class="btn btn-secondary font-weight-bold px-4">
                    <i class="fa fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success font-weight-bold px-5">
                    <i class="fa fa-save mr-1"></i> Guardar Evento e Ir a Fases
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
        theme: 'bootstrap4',
        language: 'es'
    });
});
</script>
@endsection
