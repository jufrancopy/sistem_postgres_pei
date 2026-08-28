@extends('layouts.master')
@section('title', 'Importar planilla')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [
    ['label' => 'Carga de datos', 'url' => route('bioestadistica.captura.index')],
    ['label' => 'Importar'],
]])
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
    }
</style>
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">upload_file</i> Importar planilla</h4>
        <p class="card-category">El Excel se analiza en memoria y <strong>no se guarda</strong> en el servidor.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('warning'))<div class="alert alert-warning">{{ session('warning') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="alert alert-info">
            <ul class="mb-0 pl-3">
                <li>Suba la planilla Excel mensual con una o más hojas SP (consultas, enfermería, urgencias, etc.).</li>
                <li>Verá un <strong>resumen de hojas</strong> y podrá revisar el matching de cada SP importable.</li>
                <li>Los valores se cargan en estado <strong>borrador</strong> para revisión en Captura.</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('bioestadistica.captura.import.analyze') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="bio-import-archivo">Archivo Excel (.xls / .xlsx)</label>
                <div class="bio-file-picker">
                    <input id="bio-import-archivo" type="file" name="archivo" accept=".xls,.xlsx" required>
                </div>
                <small class="text-muted">Máximo 20 MB.</small>
            </div>
            <button type="submit" class="btn btn-info">Analizar</button>
            <a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-secondary">Volver a carga</a>
        </form>
    </div>
</div>
@endsection
