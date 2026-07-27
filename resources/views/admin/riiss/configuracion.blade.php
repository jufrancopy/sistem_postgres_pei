@extends('layouts.master')
@section('title', 'Configuración RIISS')

@push('styles')
<style>
.riiss-tabs .nav-link {
    font-weight: 600;
    color: #475569;
    border-radius: 12px;
    padding: 10px 18px;
    margin-right: 8px;
    transition: all .2s;
    background: #f8fbfe;
    border: 1px solid #dbeaf4;
}
.riiss-tabs .nav-link.active {
    background: linear-gradient(135deg, #00acc1, #26c6da) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    box-shadow: 0 8px 18px rgba(6, 78, 126, .15);
}
.riiss-tabs .nav-link.active i {
    color: rgba(255,255,255,.85) !important;
}
.riiss-tabs .nav-link:hover:not(.active) {
    background: #e2f5fb;
    color: #0f172a;
    border-color: #b8e2f5;
}
.riiss-action-btn {
    background: linear-gradient(135deg, #00acc1, #26c6da);
    color: #ffffff !important;
    border-radius: 10px;
    border: none;
    box-shadow: 0 8px 18px rgba(6, 78, 126, 0.12);
    transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
}
.riiss-action-btn:hover,
.riiss-action-btn:focus {
    transform: translateY(-1px);
    box-shadow: 0 12px 22px rgba(6, 78, 126, 0.18);
    opacity: .95;
}
</style>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header card-header-info py-3" style="background: linear-gradient(135deg, #00acc1, #26c6da); border-radius: 12px; box-shadow: 0 12px 26px rgba(0, 172, 193, 0.18);">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h4 class="card-title mb-0 text-white">
                    <i class="fa fa-cog mr-2"></i>Configuración del Módulo RIISS
                </h4>
                <p class="card-category mb-0 text-white-75">Administración técnica de formularios, reglas por tipología y complejidad</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('riiss.index') }}" class="btn btn-white btn-sm font-weight-bold" style="color: #00acc1; border: 1px solid rgba(255,255,255,.35); background: rgba(255,255,255,.95);">
                    <i class="fa fa-arrow-left mr-1 text-info"></i>Volver al Centro RIISS
                </a>
            </div>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Configuración RIISS</li>
        </ol>
    </nav>
    
    <div class="card-body pb-2">
        <ul class="nav nav-pills riiss-tabs border-0" id="riissConfigTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-formularios-tab" data-toggle="tab" href="#tab-formularios" role="tab">
                    <i class="fa fa-file-alt mr-1"></i>Formularios Dinámicos & Preguntas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-complejidad-tab" data-toggle="tab" href="#tab-complejidad" role="tab">
                    <i class="fa fa-weight-hanging mr-1"></i>Ponderación & Complejidad
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="riissConfigTabsContent">

    {{-- TAB 1: Formularios Dinámicos --}}
    <div class="tab-pane fade show active" id="tab-formularios" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Gestión de Tipologías y Preguntas</h5>
                    <a href="{{ route('riiss.formularios.index') }}" class="btn btn-sm riiss-action-btn">
                        <i class="fa fa-external-link-alt mr-1"></i>Abrir Editor de Formularios
                    </a>
                </div>
                <p class="text-muted">
                    Aquí puedes configurar las secciones y preguntas aplicables para cada tipo de establecimiento de la red de salud.
                </p>
            </div>
        </div>
    </div>

    {{-- TAB 2: Complejidad --}}
    <div class="tab-pane fade" id="tab-complejidad" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Matriz de Complejidad Hospitalaria</h5>
                    <a href="{{ route('riiss.complejidad.index') }}" class="btn btn-sm riiss-action-btn">
                        <i class="fa fa-external-link-alt mr-1"></i>Abrir Matriz de Complejidad
                    </a>
                </div>
                <p class="text-muted">
                    Ajusta los ponderadores e indicadores de nivel de complejidad para los hospitales y centros de salud.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
