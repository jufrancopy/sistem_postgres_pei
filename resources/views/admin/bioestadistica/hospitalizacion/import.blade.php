@extends('layouts.master')
@section('title', 'Importar SP10 nominativo')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Importar episodios SP10</h4>
        <p class="card-category">Excel nominativo. Reimportar actualiza por huella (establecimiento, cédula, fechas, servicio y CIE-10) sin duplicar.</p>
    </div>
    <div class="card-body">
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <p>Columnas reconocidas: código de establecimiento, cédula, sexo, edad, fechas de ingreso/egreso, servicio, diagnóstico, CIE-10, tipo de alta, cirugía, cesárea y recién nacido.</p>
        <form method="POST" action="{{ route('bioestadistica.hospitalizacion.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Archivo .xls / .xlsx</label>
                <input class="form-control-file" type="file" name="archivo" accept=".xls,.xlsx" required>
            </div>
            <button class="btn btn-success">Importar</button>
            <a class="btn btn-secondary" href="{{ route('bioestadistica.hospitalizacion.index') }}">Cancelar</a>
        </form>
    </div>
</div>
@endsection
