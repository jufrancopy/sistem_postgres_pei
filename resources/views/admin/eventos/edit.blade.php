@extends('layouts.master')

@section('title', 'Editar Evento Institucional')

@section('content')
<div class="content">
    <div class="container-fluid">
        <!-- Breadcrumb & Header -->
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('eventos.index') }}">Eventos Institucionales</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('eventos.show', $evento->id) }}">{{ $evento->nombre }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                    </ol>
                </nav>
                <h3 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-edit text-warning mr-2"></i> Editar Evento Institucional
                </h3>
                <p class="text-muted mb-0">Actualiza los datos del evento, sedes, responsables o fechas de ejecución.</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-outline-secondary btn-round">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Detalle
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-info-circle text-primary mr-2"></i> Modificación del Evento
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('eventos.update', $evento->id) }}" method="POST" id="formEditEvento">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Nombre del Evento -->
                                <div class="col-md-8 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        Nombre del Evento o Hito <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nombre" class="form-control form-control-lg @error('nombre') is-invalid @enderror" 
                                           value="{{ old('nombre', $evento->nombre) }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tipo de Evento -->
                                <div class="col-md-4 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">Tipo de Evento <span class="text-danger">*</span></label>
                                    <select name="tipo" class="form-control select2" style="width: 100%;">
                                        @foreach(['Taller', 'Jornada', 'Reunión', 'Congreso', 'Lanzamiento', 'Evaluación', 'Otro'] as $t)
                                            <option value="{{ $t }}" {{ old('tipo', $evento->tipo) == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Plan PEI Vinculado -->
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="fa fa-bullseye text-info mr-1"></i> Vincular a Plan PEI (Opcional)
                                    </label>
                                    <select name="pei_profile_id" class="form-control select2" style="width: 100%;">
                                        <option value="">— Ninguno / Evento Institucional General —</option>
                                        @foreach($peiPerfiles as $pei)
                                            <option value="{{ $pei->id }}" {{ (old('pei_profile_id', $evento->pei_profile_id) == $pei->id) ? 'selected' : '' }}>
                                                {{ $pei->nombre ?? $pei->name }} ({{ $pei->periodo_texto ?? 'PEI' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Lugar o Sede -->
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="fa fa-map-marker-alt text-danger mr-1"></i> Lugar o Sede
                                    </label>
                                    <input type="text" name="lugar_sede" class="form-control" 
                                           value="{{ old('lugar_sede', $evento->lugar_sede) }}">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Fecha Inicio -->
                                <div class="col-md-3 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="far fa-calendar-alt text-primary mr-1"></i> Fecha Inicio
                                    </label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                           value="{{ old('fecha_inicio', $evento->fecha_inicio ? $evento->fecha_inicio->format('Y-m-d') : '') }}">
                                    @error('fecha_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fecha Fin -->
                                <div class="col-md-3 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="far fa-calendar-check text-success mr-1"></i> Fecha Fin
                                    </label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                           value="{{ old('fecha_fin', $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d') : '') }}">
                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Estado -->
                                <div class="col-md-3 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">Estado</label>
                                    <select name="estado" class="form-control select2" style="width: 100%;">
                                        <option value="planificado" {{ old('estado', $evento->estado) == 'planificado' ? 'selected' : '' }}>Planificado</option>
                                        <option value="en_curso" {{ old('estado', $evento->estado) == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                        <option value="completado" {{ old('estado', $evento->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                                        <option value="cancelado" {{ old('estado', $evento->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>

                                <!-- Color Identificador -->
                                <div class="col-md-3 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">Color en Calendario</label>
                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                        <input type="color" name="color" class="form-control p-1" style="height: 38px; width: 60px; cursor: pointer;" value="{{ old('color', $evento->color ?: '#4f46e5') }}">
                                        <small class="text-muted">Color en agenda</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Presupuesto Estimado -->
                                <div class="col-md-4 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="fas fa-coins text-warning mr-1"></i> Presupuesto Estimado (Gs.)
                                    </label>
                                    <input type="number" step="0.01" name="presupuesto_estimado" class="form-control" 
                                           value="{{ old('presupuesto_estimado', $evento->presupuesto_estimado) }}">
                                </div>

                                <!-- Presupuesto Ejecutado -->
                                <div class="col-md-4 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="fas fa-receipt text-secondary mr-1"></i> Presupuesto Ejecutado (Gs.)
                                    </label>
                                    <input type="number" step="0.01" name="presupuesto_ejecutado" class="form-control" 
                                           value="{{ old('presupuesto_ejecutado', $evento->presupuesto_ejecutado) }}">
                                </div>

                                <!-- Responsables -->
                                <div class="col-md-4 form-group mb-4">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="fas fa-users text-primary mr-1"></i> Equipo Organizador
                                    </label>
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

                            <!-- Descripción y Objetivos -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark small mb-1">Descripción y Objetivos Esperados</label>
                                <textarea name="descripcion" rows="4" class="form-control">{{ old('descripcion', $evento->descripcion) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end align-items-center pt-3 border-top" style="gap: 10px;">
                                <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-secondary btn-round px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary btn-round px-5 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4' });
});
</script>
@endsection
