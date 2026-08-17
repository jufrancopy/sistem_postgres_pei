@extends('layouts.master')
@section('title', $episodio->exists ? 'Editar episodio SP10' : 'Nuevo episodio SP10')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $episodio->exists ? 'Editar episodio' : 'Nuevo episodio hospitalario' }}</h4>
        <p class="card-category">La cédula se almacena cifrada. El período se calcula por la fecha de egreso.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ $episodio->exists ? route('bioestadistica.hospitalizacion.update', $episodio) : route('bioestadistica.hospitalizacion.store') }}">
            @csrf
            @if($episodio->exists) @method('PUT') @endif
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Establecimiento *</label>
                    <select class="form-control" name="establecimiento_id" required @disabled(!auth()->user()->can('bio.hosp.manage'))>
                        <option value="">Seleccione</option>
                        @foreach($establecimientos as $establecimiento)
                            <option value="{{ $establecimiento->id }}" @selected(old('establecimiento_id', $episodio->establecimiento_id) == $establecimiento->id)>{{ $establecimiento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Cédula @can('bio.hosp.view_pii')*@else (enmascarada)@endcan</label>
                    @can('bio.hosp.view_pii')
                        <input class="form-control" name="cedula" value="{{ old('cedula', $episodio->exists ? $episodio->cedula : '') }}" maxlength="30" @disabled(!auth()->user()->can('bio.hosp.manage'))>
                    @else
                        <input class="form-control" value="{{ $episodio->cedula_visible }}" disabled>
                        <input type="hidden" name="cedula" value="">
                    @endcan
                </div>
                <div class="form-group col-md-3">
                    <label>Sexo</label>
                    <select class="form-control" name="sexo" @disabled(!auth()->user()->can('bio.hosp.manage'))>
                        <option value="">Seleccione</option>
                        <option value="M" @selected(old('sexo', $episodio->sexo) === 'M')>M</option>
                        <option value="F" @selected(old('sexo', $episodio->sexo) === 'F')>F</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2"><label>Edad</label><input class="form-control" type="number" min="0" max="130" name="edad" value="{{ old('edad', $episodio->edad) }}" @disabled(!auth()->user()->can('bio.hosp.manage'))></div>
                <div class="form-group col-md-3"><label>Seguro</label><input class="form-control" name="seguro" value="{{ old('seguro', $episodio->seguro) }}" @disabled(!auth()->user()->can('bio.hosp.manage'))></div>
                <div class="form-group col-md-3"><label>Fecha de ingreso *</label><input class="form-control" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', optional($episodio->fecha_ingreso)->format('Y-m-d')) }}" required @disabled(!auth()->user()->can('bio.hosp.manage'))></div>
                <div class="form-group col-md-4"><label>Fecha de egreso</label><input class="form-control" type="date" name="fecha_egreso" value="{{ old('fecha_egreso', optional($episodio->fecha_egreso)->format('Y-m-d')) }}" @disabled(!auth()->user()->can('bio.hosp.manage'))></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Servicio</label>
                    <select class="form-control" name="servicio" @disabled(!auth()->user()->can('bio.hosp.manage'))>
                        <option value="">Seleccione</option>
                        @foreach($servicios as $code => $label)
                            <option value="{{ $code }}" @selected(old('servicio', $episodio->servicio) === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Tipo de alta</label>
                    <select class="form-control" name="tipo_alta" @disabled(!auth()->user()->can('bio.hosp.manage'))>
                        <option value="">Seleccione</option>
                        @foreach($tiposAlta as $code => $label)
                            <option value="{{ $code }}" @selected(old('tipo_alta', $episodio->tipo_alta) === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2"><label>CIE-10</label><input class="form-control text-uppercase" name="cie10" value="{{ old('cie10', $episodio->cie10) }}" maxlength="10" placeholder="A00.0" @disabled(!auth()->user()->can('bio.hosp.manage'))></div>
                <div class="form-group col-md-4"><label>Diagnóstico</label><input class="form-control" name="diagnostico" value="{{ old('diagnostico', $episodio->diagnostico) }}" @disabled(!auth()->user()->can('bio.hosp.manage'))></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label class="d-block">Cirugía</label><label><input type="checkbox" name="cirugia" value="1" @checked(old('cirugia', $episodio->cirugia)) @disabled(!auth()->user()->can('bio.hosp.manage'))> Sí</label></div>
                <div class="form-group col-md-3">
                    <label>Tipo de cirugía</label>
                    <select class="form-control" name="tipo_cirugia" @disabled(!auth()->user()->can('bio.hosp.manage'))>
                        <option value="">—</option>
                        @foreach($tiposCirugia as $code => $label)
                            <option value="{{ $code }}" @selected(old('tipo_cirugia', $episodio->tipo_cirugia) === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3"><label class="d-block">Cesárea</label><label><input type="checkbox" name="cesarea" value="1" @checked(old('cesarea', $episodio->cesarea)) @disabled(!auth()->user()->can('bio.hosp.manage'))> Sí</label></div>
                <div class="form-group col-md-3"><label class="d-block">Recién nacido</label><label><input type="checkbox" name="recien_nacido" value="1" @checked(old('recien_nacido', $episodio->recien_nacido)) @disabled(!auth()->user()->can('bio.hosp.manage'))> Sí</label></div>
            </div>
            @can('bio.hosp.manage')
                <button class="btn btn-success">Guardar</button>
            @endcan
            <a class="btn btn-secondary" href="{{ route('bioestadistica.hospitalizacion.index') }}">Volver</a>
        </form>
        @if($episodio->exists && auth()->user()->can('bio.hosp.manage'))
            <form method="POST" action="{{ route('bioestadistica.hospitalizacion.destroy', $episodio) }}" class="mt-3" onsubmit="return confirm('¿Eliminar este episodio?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Eliminar</button>
            </form>
        @endif
    </div>
</div>
@endsection
