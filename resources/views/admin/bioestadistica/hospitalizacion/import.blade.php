@extends('layouts.master')
@section('title', 'Importar SP10 nominativo')

@section('content')
@include('admin.bioestadistica._siplan-styles')
<style>
    .bio-file-picker {
        border: 1px dashed #94a3b8;
        border-radius: 8px;
        background: #fff;
        padding: 0.85rem 1rem;
    }
    .bio-file-picker input[type=file] {
        display: block !important;
        width: 100% !important;
        opacity: 1 !important;
        position: static !important;
        height: auto !important;
        z-index: auto !important;
        font-size: 0.9rem;
        padding: 0.35rem 0;
    }
</style>
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title">Importar episodios SP10</h4>
        <p class="card-category">Excel nominativo. Reimportar actualiza por huella (establecimiento, cédula, fechas, servicio y CIE-10) sin duplicar.</p>
    </div>
    <div class="card-body">
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <p>Columnas reconocidas: código de establecimiento, cédula, sexo, edad, fechas de ingreso/egreso, servicio, diagnóstico, CIE-10, tipo de alta, cirugía, cesárea y recién nacido.</p>
        <form method="POST" action="{{ route('bioestadistica.hospitalizacion.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="small text-muted mb-1" for="bio-hosp-import-archivo">Archivo .xls / .xlsx</label>
                <div class="bio-file-picker">
                    <input id="bio-hosp-import-archivo" type="file" name="archivo" accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                </div>
            </div>
            <button class="btn btn-success" type="submit">Importar</button>
            <a class="btn btn-secondary" href="{{ route('bioestadistica.hospitalizacion.index') }}">Cancelar</a>
        </form>
    </div>
</div>
@endsection
