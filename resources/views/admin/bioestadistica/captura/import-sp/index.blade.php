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
    .bio-import-checklist {
        border-left: 3px solid #26c6da;
        background: #f8fafc;
        padding: 0.75rem 1rem;
        border-radius: 0 6px 6px 0;
    }
    .bio-import-checklist li + li { margin-top: 0.25rem; }
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
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <p class="mb-0 mt-2 small">
                    Si el archivo es válido pero el layout no se reconoce, analice de nuevo y use
                    <strong>Ajustar mapeo</strong> en el resumen de hojas (SP, fila de encabezado y columnas).
                </p>
            </div>
        @endif

        <div class="bio-import-checklist mb-3">
            <strong class="d-block mb-1">Checklist mínimo antes de subir</strong>
            <ul class="mb-0 pl-3 small">
                <li>Archivo <code>.xls</code> / <code>.xlsx</code> de la planilla mensual (máx. 20&nbsp;MB).</li>
                <li>Código de establecimiento y <strong>mes/año del período estadístico</strong> visibles en la planilla (no se usa la fecha de subida).</li>
                <li>Hojas con nombre reconocible (<em>Consultas médicas</em>, <em>Enfermería</em>, <em>SP1 - …</em>, etc.) o con «TABLA SP N» en el contenido.</li>
                <li>Fila de encabezado con columnas de prestación y totales (p.&nbsp;ej. COD / ESPECIALIDAD / TOTAL, o ID / ITEMS / TOTAL).</li>
                <li>Si una hoja falla: en el resumen abra <strong>Ajustar mapeo</strong>; no hace falta rearmar el Excel.</li>
            </ul>
        </div>

        <div class="alert alert-info">
            <ul class="mb-0 pl-3">
                <li>Tras analizar verá un <strong>resumen de hojas</strong> con calidad y matching.</li>
                <li>Puede importar en lote lo auto-seguro o revisar el detalle de cada SP.</li>
                <li>Los valores quedan en estado <strong>borrador</strong> para revisión en Captura.</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('bioestadistica.captura.import.analyze') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="bio-import-archivo">Archivo Excel (.xls / .xlsx)</label>
                <div class="bio-file-picker">
                    <input id="bio-import-archivo" type="file" name="archivo" accept=".xls,.xlsx" required>
                </div>
                <small class="text-muted">Máximo 20 MB. El archivo no se almacena de forma permanente.</small>
            </div>
            <button type="submit" class="btn btn-info">Analizar</button>
            <a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-secondary">Volver a carga</a>
        </form>
    </div>
</div>
@endsection
