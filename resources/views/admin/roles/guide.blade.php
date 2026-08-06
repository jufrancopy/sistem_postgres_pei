@extends('layouts.master')

@section('title', 'Guía Institucional de Roles y Permisos — SIPLAN IPS')

@push('css')
<style>
    .roles-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        color: #ffffff;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);
    }
    .role-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.25s ease;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .role-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -5px rgba(0,0,0,0.1);
        border-color: #3b82f6;
    }
    .badge-no-delete {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        border-radius: 20px;
    }
    .badge-can-delete {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        border-radius: 20px;
    }
    .perm-table th {
        background: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Main Container Card with Header --}}
    <div class="card mb-4">
        <div class="card-header card-header-info d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0 font-weight-bold">
                    <i class="fa fa-book-open mr-2"></i>Guía Institucional de Roles y Permisos
                </h4>
                <small class="text-white" style="opacity:.9">Manual de asignación de perfiles, matriz CRUD y reglas de control de acceso</small>
            </div>
            <div>
                <a href="{{ route('globales.roles.index') }}" class="btn btn-sm btn-light font-weight-bold shadow-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Volver a Roles
                </a>
            </div>
        </div>

        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-0">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('globales.roles.index') }}">Roles y Permisos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Guía de Roles</li>
            </ol>
        </nav>

        <div class="card-body p-4">
            {{-- Hero Section --}}
            <div class="roles-hero mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <span class="badge badge-primary px-3 py-2 mb-2" style="font-size: .8rem; border-radius: 20px;">
                            <i class="fa fa-shield-alt mr-1"></i> CONTROL DE ACCESO Y SEGURIDAD
                        </span>
                        <h2 class="font-weight-bold text-white mb-2">Manual de Perfiles y Control de Acceso</h2>
                        <p class="text-slate-300 mb-0" style="font-size: 1.05rem; max-width: 800px;">
                            Guía para administradores y coordinadores sobre el alcance de cada rol en los módulos de Planificación (PEI) y Gestión de Actividades en el IPS.
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('globales.roles.index') }}" class="btn btn-light btn-lg font-weight-bold shadow-sm" style="border-radius: 10px;">
                            <i class="fa fa-users-cog mr-2 text-primary"></i> Gestionar Roles en el Sistema
                        </a>
                    </div>
                </div>
            </div>

            @include('admin.roles.partials.guide-content')
        </div>
    </div>
</div>
@endsection
