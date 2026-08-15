@extends('layouts.master')
@section('title', "Diseñar {$formulario->codigo}")

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-title">{{ $formulario->codigo }} — {{ $formulario->nombre }}</h4>
            <p class="card-category">Constructor metadata-driven · versión {{ $formulario->version }}</p>
        </div>
        <form method="POST" action="{{ route('bioestadistica.formularios.publish', $formulario) }}">
            @csrf
            <button class="btn btn-success btn-sm"><i class="material-icons">publish</i> Publicar</button>
        </form>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif

        <form method="POST" action="{{ route('bioestadistica.formularios.update', $formulario) }}" class="mb-4">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="col-md-2"><label>Código</label><input class="form-control" name="codigo" value="{{ $formulario->codigo }}" required></div>
                <div class="col-md-4"><label>Nombre</label><input class="form-control" name="nombre" value="{{ $formulario->nombre }}" required></div>
                <div class="col-md-3">
                    <label>Periodicidad</label>
                    <select class="form-control" name="periodicidad">
                        @foreach(['diaria','semanal','mensual','trimestral','anual','ad_hoc'] as $value)
                            <option value="{{ $value }}" @selected($formulario->periodicidad === $value)>{{ ucfirst($value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Diseño</label>
                    <select class="form-control" name="layout_type">
                        @foreach(['tabular','nominativo','matriz'] as $value)
                            <option value="{{ $value }}" @selected($formulario->layout_type === $value)>{{ ucfirst($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <textarea class="form-control mt-2" name="descripcion">{{ $formulario->descripcion }}</textarea>
            <button class="btn btn-primary btn-sm mt-2">Guardar datos generales</button>
        </form>

        @foreach($formulario->secciones as $seccion)
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <strong>{{ $seccion->orden }}. {{ $seccion->titulo }}</strong>
                    <small class="text-muted ml-2">{{ $seccion->descripcion }}</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Orden</th><th>Código</th><th>Etiqueta</th><th>Tipo</th><th>Catálogo / variable</th><th>Validación</th><th></th></tr></thead>
                            <tbody>
                            @forelse($seccion->fields as $field)
                                <tr>
                                    <td>{{ $field->orden }}</td>
                                    <td><code>{{ $field->code }}</code></td>
                                    <td>{{ $field->label }} @if($field->required)<span class="text-danger">*</span>@endif</td>
                                    <td>{{ $fieldTypes[$field->type] ?? $field->type }}</td>
                                    <td>{{ $field->catalogo?->nombre ?? $field->variableDefinition?->prestacion }}</td>
                                    <td>{{ $field->min_value !== null ? "mín. {$field->min_value}" : '' }} {{ $field->max_value !== null ? "máx. {$field->max_value}" : '' }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('bioestadistica.fields.destroy', $field) }}" onsubmit="return confirm('¿Eliminar campo?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Sin campos.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <button class="btn btn-outline-info btn-sm" data-toggle="collapse" data-target="#field-{{ $seccion->id }}">Agregar campo</button>
                    <div class="collapse mt-3" id="field-{{ $seccion->id }}">
                        <form method="POST" action="{{ route('bioestadistica.secciones.fields.store', $seccion) }}" class="bg-light p-3 rounded">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-3"><input class="form-control" name="code" placeholder="codigo_campo" required></div>
                                <div class="col-md-4"><input class="form-control" name="label" placeholder="Etiqueta" required></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="type" required>
                                        @foreach($fieldTypes as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"><input class="form-control" type="number" min="0" name="orden" value="{{ $seccion->fields->count() + 1 }}"></div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-4">
                                    <select class="form-control" name="catalogo_id">
                                        <option value="">Sin catálogo</option>
                                        @foreach($catalogos as $catalogo)<option value="{{ $catalogo->id }}">{{ $catalogo->nombre }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" name="variable_definition_id">
                                        <option value="">Sin variable maestra</option>
                                        @foreach($variables as $variable)
                                            <option value="{{ $variable->id }}">{{ $variable->dominio }} / {{ $variable->prestacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"><input class="form-control" type="number" step="any" name="min_value" placeholder="Mínimo"></div>
                                <div class="col-md-2"><input class="form-control" type="number" step="any" name="max_value" placeholder="Máximo"></div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-4"><input class="form-control" name="validation_regex" placeholder="Expresión de validación"></div>
                                <div class="col-md-4"><input class="form-control" name="tooltip" placeholder="Tooltip"></div>
                                <div class="col-md-4"><input class="form-control" name="help_text" placeholder="Ayuda contextual"></div>
                            </div>
                            <textarea class="form-control mt-2" name="config" placeholder='Configuración JSON para tabla/matriz, ej. {"columns":[]}'></textarea>
                            <label class="mt-2"><input type="checkbox" name="required" value="1"> Obligatorio</label>
                            <button class="btn btn-success btn-sm ml-2">Agregar campo</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <form method="POST" action="{{ route('bioestadistica.formularios.secciones.store', $formulario) }}" class="form-row bg-light p-3 rounded">
            @csrf
            <div class="col-md-4"><input class="form-control" name="titulo" placeholder="Nueva sección" required></div>
            <div class="col-md-5"><input class="form-control" name="descripcion" placeholder="Descripción"></div>
            <div class="col-md-1"><input class="form-control" type="number" min="0" name="orden" value="{{ $formulario->secciones->count() }}"></div>
            <div class="col-md-2"><button class="btn btn-info btn-sm">Agregar sección</button></div>
        </form>
    </div>
</div>
@endsection
