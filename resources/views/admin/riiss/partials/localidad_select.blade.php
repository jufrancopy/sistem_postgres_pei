{{--
    Selector encadenado con Select2: Departamento → Distrito → Barrio/Localidad
    Uso: @include('admin.riiss.partials.localidad_select', ['prefix' => 'eval', 'required' => false])
    Inicializar en tu @section('scripts'): initLocalidadSelect('eval');
    Leer valores: evalGetLocalidad() → { departamento, distrito, barrio }
--}}
@php
    $prefix   = $prefix   ?? 'loc';
    $required = $required ?? false;
@endphp

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-uppercase text-muted">
            Departamento {!! $required ? '<span class="text-danger">*</span>' : '' !!}
        </label>
        <select id="{{ $prefix }}-depto" class="form-control" style="width:100%"></select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-uppercase text-muted">
            Ciudad / Distrito {!! $required ? '<span class="text-danger">*</span>' : '' !!}
        </label>
        <select id="{{ $prefix }}-dist" class="form-control" style="width:100%"></select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-uppercase text-muted">Barrio / Localidad</label>
        <select id="{{ $prefix }}-barrio" class="form-control" style="width:100%"></select>
    </div>
</div>
