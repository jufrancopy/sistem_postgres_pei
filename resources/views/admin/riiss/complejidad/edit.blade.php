@extends('layouts.master')
@section('title', 'Editar Grado de Complejidad')

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title">
            <i class="fa fa-edit mr-2"></i>Editar — Grado {{ $tipo->grado }}
        </h4>
        <p class="card-category">{{ $tipo->nombre }}</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">RIISS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('riiss.complejidad.index') }}">Grados de Complejidad</a></li>
            <li class="breadcrumb-item active">Editar Grado {{ $tipo->grado }}</li>
        </ol>
    </nav>

    <div class="card-body">
        <form action="{{ route('riiss.complejidad.update', $tipo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre del Grado <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $tipo->nombre) }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-bold">Nivel de Atención <span class="text-danger">*</span></label>
                        <select name="nivel_atencion" class="form-control @error('nivel_atencion') is-invalid @enderror" required>
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
                        <label class="font-weight-bold">Color</label>
                        <input type="color" name="color" class="form-control"
                               value="{{ old('color', $tipo->color) }}" style="height:38px; padding:2px 4px">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Establecimiento</label>
                        <input type="text" name="tipo_establecimiento" class="form-control"
                               value="{{ old('tipo_establecimiento', $tipo->tipo_establecimiento) }}"
                               placeholder="Ej: Hospital General Regional">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Estado</label>
                        <select name="activo" class="form-control">
                            <option value="1" {{ old('activo', $tipo->activo) ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !old('activo', $tipo->activo) ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <h6 class="font-weight-bold mb-3"><i class="fa fa-hospital-o mr-2"></i>Requisitos del Establecimiento</h6>

            <div class="row">
                @foreach([
                    ['es_hospitalario',     'Es Hospitalario'],
                    ['requiere_internacion','Requiere Internación'],
                    ['requiere_quirofano',  'Requiere Quirófano'],
                    ['requiere_uti',        'Requiere UTI'],
                    ['requiere_urgencias',  'Requiere Urgencias'],
                ] as [$field, $label])
                <div class="col-md-4 mb-3">
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" class="custom-control-input" id="{{ $field }}"
                               name="{{ $field }}" value="1"
                               {{ old($field, $tipo->$field) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="{{ $field }}">{{ $label }}</label>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="alert alert-warning mt-3">
                <i class="fa fa-exclamation-triangle mr-2"></i>
                <strong>Atención:</strong> Si cambia el Nivel de Atención, se recalcularán automáticamente
                los <strong>{{ $tipo->establecimientos()->whereNull('deleted_at')->count() }}</strong> establecimientos asignados a este grado.
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('riiss.complejidad.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-save mr-1"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
