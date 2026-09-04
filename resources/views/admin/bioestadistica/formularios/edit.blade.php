@extends('layouts.master')
@section('title', "Diseñar {$formulario->codigo}")

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._configuraciones_tabs')
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
                <div class="card-header bg-light d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $seccion->orden }}. {{ $seccion->titulo }}</strong>
                        <small class="text-muted ml-2">{{ $seccion->descripcion }}</small>
                    </div>
                    <div class="text-nowrap ml-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="collapse" data-target="#edit-seccion-{{ $seccion->id }}" title="Editar sección">
                            <i class="material-icons" style="font-size:16px">edit</i>
                        </button>
                        <form method="POST" action="{{ route('bioestadistica.secciones.destroy', $seccion) }}" class="d-inline" onsubmit="return confirm('¿Eliminar la sección «{{ $seccion->titulo }}» y todos sus campos?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Eliminar sección"><i class="material-icons" style="font-size:16px">delete</i></button>
                        </form>
                    </div>
                </div>
                <div class="collapse border-bottom" id="edit-seccion-{{ $seccion->id }}">
                    <form method="POST" action="{{ route('bioestadistica.secciones.update', $seccion) }}" class="bg-light p-3">
                        @csrf @method('PUT')
                        <div class="form-row align-items-end">
                            <div class="col-md-4 mb-2">
                                <label class="small text-muted mb-1">Título</label>
                                <input class="form-control" name="titulo" value="{{ $seccion->titulo }}" required>
                            </div>
                            <div class="col-md-5 mb-2">
                                <label class="small text-muted mb-1">Descripción</label>
                                <input class="form-control" name="descripcion" value="{{ $seccion->descripcion }}">
                            </div>
                            <div class="col-md-1 mb-2">
                                <label class="small text-muted mb-1">Orden</label>
                                <input class="form-control" type="number" min="0" name="orden" value="{{ $seccion->orden }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-primary btn-sm btn-block">Guardar sección</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Orden</th><th>Código</th><th>Etiqueta</th><th>Tipo</th><th>Diccionario / catálogo</th><th>Validación</th><th style="width:120px"></th></tr></thead>
                            <tbody>
                            @forelse($seccion->fields as $field)
                                @php
                                    $configJson = $field->config
                                        ? json_encode($field->config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                                        : '';
                                    $editId = 'edit-field-'.$field->id;
                                @endphp
                                <tr>
                                    <td>{{ $field->orden }}</td>
                                    <td><code>{{ $field->code }}</code></td>
                                    <td>{{ $field->label }} @if($field->required)<span class="text-danger">*</span>@endif</td>
                                    <td>{{ $fieldTypes[$field->type] ?? $field->type }}</td>
                                    <td>
                                        @if($field->detalle)
                                            {{ $field->detalle->variable->codigo }} — {{ $field->detalle->nombre }}
                                        @endif
                                    </td>
                                    <td>{{ $field->min_value !== null ? "mín. {$field->min_value}" : '' }} {{ $field->max_value !== null ? "máx. {$field->max_value}" : '' }}</td>
                                    <td class="text-nowrap">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="collapse" data-target="#{{ $editId }}" title="Editar / ver JSON">
                                            <i class="material-icons" style="font-size:16px">edit</i>
                                        </button>
                                        <form method="POST" action="{{ route('bioestadistica.fields.destroy', $field) }}" class="d-inline" onsubmit="return confirm('¿Eliminar campo?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar"><i class="material-icons" style="font-size:16px">delete</i></button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="collapse" id="{{ $editId }}">
                                    <td colspan="7" class="bg-light">
                                        <form method="POST" action="{{ route('bioestadistica.fields.update', $field) }}" class="p-3">
                                            @csrf @method('PUT')
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>Editar campo <code>{{ $field->code }}</code></strong>
                                                <small class="text-muted">Puede copiar el JSON de configuración a otro campo nuevo.</small>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3 mb-2">
                                                    <label class="small text-muted mb-1">Código</label>
                                                    <input class="form-control" name="code" value="{{ $field->code }}" required>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="small text-muted mb-1">Etiqueta</label>
                                                    <input class="form-control" name="label" value="{{ $field->label }}" required>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="small text-muted mb-1">Tipo</label>
                                                    <select class="form-control" name="type" required>
                                                        @foreach($fieldTypes as $value => $label)
                                                            <option value="{{ $value }}" @selected($field->type === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="small text-muted mb-1">Orden</label>
                                                    <input class="form-control" type="number" min="0" name="orden" value="{{ $field->orden }}">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12 mb-2">
                                                    <label class="small text-muted mb-1">Detalle del diccionario (filas)</label>
                                                    <select class="form-control" name="detalle_id">
                                                        <option value="">Sin detalle del diccionario</option>
                                                        @foreach($detalles as $detalle)
                                                            <option value="{{ $detalle->id }}" @selected((int) $field->detalle_id === (int) $detalle->id)>
                                                                {{ $detalle->variable->codigo }} — {{ $detalle->variable->nombre }} / {{ $detalle->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-2 mb-2"><input class="form-control" type="number" step="any" name="min_value" placeholder="Mínimo" value="{{ $field->min_value }}"></div>
                                                <div class="col-md-2 mb-2"><input class="form-control" type="number" step="any" name="max_value" placeholder="Máximo" value="{{ $field->max_value }}"></div>
                                                <div class="col-md-4 mb-2"><input class="form-control" name="validation_regex" placeholder="Expresión de validación" value="{{ $field->validation_regex }}"></div>
                                                <div class="col-md-4 mb-2"><input class="form-control" name="tooltip" placeholder="Tooltip" value="{{ $field->tooltip }}"></div>
                                                <div class="col-md-12 mb-2"><input class="form-control" name="help_text" placeholder="Ayuda contextual" value="{{ $field->help_text }}"></div>
                                            </div>
                                            <label class="small text-muted mb-1">Configuración JSON (columnas métricas, totales, etc.)</label>
                                            <textarea class="form-control font-monospace" name="config" rows="10" placeholder='{"columns":[{"code":"total","label":"Total","type":"integer","min":0}],"totals":true}'>{{ $configJson }}</textarea>
                                            <div class="mt-2">
                                                <label class="mr-3"><input type="checkbox" name="required" value="1" @checked($field->required)> Obligatorio</label>
                                                <button class="btn btn-primary btn-sm">Guardar campo</button>
                                            </div>
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
                                <div class="col-md-12">
                                    <select class="form-control" name="detalle_id">
                                        <option value="">Sin detalle del diccionario</option>
                                        @foreach($detalles as $detalle)
                                            <option value="{{ $detalle->id }}">{{ $detalle->variable->codigo }} — {{ $detalle->variable->nombre }} / {{ $detalle->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-2"><input class="form-control" type="number" step="any" name="min_value" placeholder="Mínimo"></div>
                                <div class="col-md-2"><input class="form-control" type="number" step="any" name="max_value" placeholder="Máximo"></div>
                                <div class="col-md-4"><input class="form-control" name="validation_regex" placeholder="Expresión de validación"></div>
                                <div class="col-md-4"><input class="form-control" name="tooltip" placeholder="Tooltip"></div>
                                <div class="col-md-4"><input class="form-control" name="help_text" placeholder="Ayuda contextual"></div>
                            </div>
                            <label class="small text-muted mb-1 mt-2 d-block">Configuración JSON (opcional; en tablas sin JSON se usa Total por defecto)</label>
                            <textarea class="form-control font-monospace" name="config" rows="6" placeholder='{"columns":[{"code":"total","label":"Total","type":"integer","min":0}],"totals":true,"row_label":"Prestación"}'></textarea>
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
