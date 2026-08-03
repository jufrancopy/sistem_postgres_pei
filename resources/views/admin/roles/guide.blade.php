@extends('layouts.master')

@section('title', 'Guía de Roles y Permisos — SIPLAN IPS')

@push('css')
<style>
    .roles-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 2.5rem;
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
    {{-- Hero Section --}}
    <div class="roles-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge badge-primary px-3 py-2 mb-2" style="font-size: .8rem; border-radius: 20px;">
                    <i class="fa fa-shield-alt mr-1"></i> CONTROL DE ACCESO E SEGURIDAD
                </span>
                <h2 class="font-weight-bold text-white mb-2">Guía Institucional de Roles y Permisos</h2>
                <p class="text-slate-300 mb-0" style="font-size: 1.05rem; max-width: 800px;">
                    Manual de asignación de perfiles de usuario para los módulos de Planificación (PEI) y Gestión de Actividades en el IPS.
                </p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('globales.roles.index') }}" class="btn btn-light btn-lg font-weight-bold shadow-sm" style="border-radius: 10px;">
                    <i class="fa fa-users-cog mr-2 text-primary"></i> Gestionar Roles en el Sistema
                </a>
            </div>
        </div>
    </div>

    {{-- Highlight Box: Requerimiento de Edición sin Eliminación --}}
    <div class="alert alert-warning border-0 shadow-sm p-4 mb-4" style="border-radius: 14px; background: #fffbe0; border-left: 6px solid #f59e0b !important;">
        <div class="d-flex align-items-start">
            <div class="mr-3 mt-1 text-warning">
                <i class="fa fa-exclamation-triangle fa-2x"></i>
            </div>
            <div>
                <h5 class="font-weight-bold text-dark mb-1">
                    💡 ¿Qué rol debo asignar a un colaborador para que REVISE y EDITE, pero NO ELIMINE nada?
                </h5>
                <p class="mb-2 text-dark" style="font-size: 0.98rem;">
                    Para colaboradores que deben trabajar en el PEI o en las Actividades sin riesgo de borrar información:
                </p>
                <ul class="mb-0 text-dark pl-3 font-weight-bold">
                    <li>Para el módulo PEI: Asignar el rol <span class="badge badge-info px-2 py-1">Analista de Planificación</span> (o <span class="badge badge-info px-2 py-1">Analista</span>). Pueden revisar, editar textos, actualizar metas y reportar avances, pero <strong>NO tienen botón de eliminar</strong>.</li>
                    <li>Para el módulo Actividades: Asignar el rol <span class="badge badge-secondary px-2 py-1">Gestor de Actividades</span> o <span class="badge badge-secondary px-2 py-1">Colaborador de Actividades</span>. Pueden crear tareas, mover estados y subir evidencias, pero <strong>NO pueden borrar el PEI ni sus ejes principales</strong>.</li>
                </ul>
                <div class="mt-2 text-muted small">
                    <i class="fa fa-lock mr-1 text-danger"></i> La función de <strong>ELIMINAR</strong> perfiles de PEI u Objetivos Estratégicos está reservada de forma exclusiva e infranqueable para el rol <strong>Administrador</strong>.
                </div>
            </div>
        </div>
    </div>

    {{-- Roles Cards --}}
    <h4 class="font-weight-bold text-dark mb-3">
        <i class="fa fa-id-badge text-primary mr-2"></i> Perfiles de Rol Disponibles en el SIPLAN
    </h4>

    <div class="row">
        {{-- Rol 1: Administrador --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="role-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge badge-danger px-3 py-1 font-weight-bold" style="border-radius: 8px;">👑 ROL GLOBAL</span>
                    <span class="badge-can-delete"><i class="fa fa-trash-alt mr-1"></i> Puede Eliminar</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Administrador</h4>
                <p class="text-muted small mb-3">Acceso total e irrestricto a todos los módulos, configuraciones, usuarios y datos del sistema.</p>
                <div class="bg-light p-3 rounded mb-3">
                    <div class="font-weight-bold text-dark mb-1 small"><i class="fa fa-check-circle text-success mr-1"></i> Capacidades Principales:</div>
                    <ul class="pl-3 mb-0 small text-secondary">
                        <li>Crear, editar, reordenar y <strong>eliminar</strong> PEIs completos.</li>
                        <li>Gestión total de usuarios, asignación de roles y permisos.</li>
                        <li>Acceso libre a todos los tableros y reportes.</li>
                    </ul>
                </div>
                <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
                <div class="small font-weight-bold text-dark">Solo a Directores de IT, Administradores de Sistema o Jefes de Planificación.</div>
            </div>
        </div>

        {{-- Rol 2: Analista de Planificación --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="role-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge badge-info px-3 py-1 font-weight-bold" style="border-radius: 8px;">📊 MÓDULO PEI</span>
                    <span class="badge-no-delete"><i class="fa fa-ban mr-1"></i> NO Elimina</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Analista de Planificación</h4>
                <p class="text-muted small mb-3">Diseñado para técnicos y analistas encargados de la formulación y seguimiento del PEI.</p>
                <div class="bg-light p-3 rounded mb-3">
                    <div class="font-weight-bold text-dark mb-1 small"><i class="fa fa-check-circle text-success mr-1"></i> Capacidades Principales:</div>
                    <ul class="pl-3 mb-0 small text-secondary">
                        <li>Revisar y editar la estructura del PEI asignado.</li>
                        <li>Actualizar metas, indicadores y reportes de avance.</li>
                        <li>Comentar y enviar consultas contextuales en el chat.</li>
                        <li><strong class="text-danger">NO PUEDE ELIMINAR</strong> el PEI ni sus Objetivos.</li>
                    </ul>
                </div>
                <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
                <div class="small font-weight-bold text-dark">A analistas y revisores de planificación que deben editar contenido sin riesgo de borrar.</div>
            </div>
        </div>

        {{-- Rol 3: Gestor de Actividades --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="role-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge badge-success px-3 py-1 font-weight-bold" style="border-radius: 8px;">📋 ACTIVIDADES</span>
                    <span class="badge-no-delete"><i class="fa fa-ban mr-1"></i> NO Elimina PEI</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Gestor de Actividades</h4>
                <p class="text-muted small mb-3">Orientado a coordinadores de proyectos y responsables operativos de dependencias.</p>
                <div class="bg-light p-3 rounded mb-3">
                    <div class="font-weight-bold text-dark mb-1 small"><i class="fa fa-check-circle text-success mr-1"></i> Capacidades Principales:</div>
                    <ul class="pl-3 mb-0 small text-secondary">
                        <li>Crear actividades y asignar tareas a colaboradores.</li>
                        <li>Monitorear tablero Kanban y notificar avances.</li>
                        <li>Vincular consultas al chat de equipo.</li>
                        <li><strong class="text-danger">NO PUEDE ELIMINAR</strong> el PEI ni modificar la estructura base.</li>
                    </ul>
                </div>
                <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
                <div class="small font-weight-bold text-dark">A jefes de departamento o encargados de liderar proyectos y equipos.</div>
            </div>
        </div>

        {{-- Rol 4: Colaborador de Actividades --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="role-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge badge-secondary px-3 py-1 font-weight-bold" style="border-radius: 8px;">👷 EJECUTOR</span>
                    <span class="badge-no-delete"><i class="fa fa-ban mr-1"></i> NO Elimina</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Colaborador de Actividades</h4>
                <p class="text-muted small mb-3">Para funcionarios y operativos asignados a la ejecución de tareas específicas.</p>
                <div class="bg-light p-3 rounded mb-3">
                    <div class="font-weight-bold text-dark mb-1 small"><i class="fa fa-check-circle text-success mr-1"></i> Capacidades Principales:</div>
                    <ul class="pl-3 mb-0 small text-secondary">
                        <li>Ver sus tareas asignadas en "Mis Tareas".</li>
                        <li>Cambiar estado de tareas (Pendiente → Completado).</li>
                        <li>Adjuntar evidencias, comentar y donar/recibir puntos en el chat.</li>
                    </ul>
                </div>
                <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
                <div class="small font-weight-bold text-dark">A los miembros de equipo que deben ejecutar y reportar cumplimiento diario.</div>
            </div>
        </div>

        {{-- Rol 5: Analista - RIISS --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="role-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold" style="border-radius: 8px;">🩺 SALUD RIISS</span>
                    <span class="badge-no-delete"><i class="fa fa-ban mr-1"></i> NO Elimina PEI</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Analista - RIISS</h4>
                <p class="text-muted small mb-3">Especializado en la evaluación de la Red Integrada e Integral de Servicios de Salud.</p>
                <div class="bg-light p-3 rounded mb-3">
                    <div class="font-weight-bold text-dark mb-1 small"><i class="fa fa-check-circle text-success mr-1"></i> Capacidades Principales:</div>
                    <ul class="pl-3 mb-0 small text-secondary">
                        <li>Completar evaluaciones de los establecimientos de salud.</li>
                        <li>Registrar carteras de servicios sanitarios.</li>
                        <li>Obtener insignias de gamificación de evaluación médica.</li>
                    </ul>
                </div>
                <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
                <div class="small font-weight-bold text-dark">A evaluadores y auditores de la Red Asistencial de Salud del IPS.</div>
            </div>
        </div>

        {{-- Rol 6: Analista de Monitoreo PEI --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="role-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge badge-primary px-3 py-1 font-weight-bold" style="border-radius: 8px;">🚦 MONITOREO</span>
                    <span class="badge-no-delete"><i class="fa fa-ban mr-1"></i> Solo Lectura/Reporte</span>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Analista de Monitoreo PEI</h4>
                <p class="text-muted small mb-3">Perfil analítico enfocado en la supervisión de semáforos e índices de avance.</p>
                <div class="bg-light p-3 rounded mb-3">
                    <div class="font-weight-bold text-dark mb-1 small"><i class="fa fa-check-circle text-success mr-1"></i> Capacidades Principales:</div>
                    <ul class="pl-3 mb-0 small text-secondary">
                        <li>Visualizar matriz de semáforos y tableros de avance.</li>
                        <li>Generar reportes presupuestarios y de cumplimiento.</li>
                        <li>Exportar datos a PDF y matriz ejecutiva.</li>
                    </ul>
                </div>
                <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
                <div class="small font-weight-bold text-dark">A consultores, auditores o directores que requieren supervisar sin alterar datos.</div>
            </div>
        </div>
    </div>

    {{-- Matriz CRUD Table --}}
    <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="font-weight-bold text-dark mb-0">
                <i class="fa fa-table text-primary mr-2"></i> Matriz Comparativa de Permisos por Rol (CRUD)
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 perm-table">
                <thead>
                    <tr>
                        <th class="py-3 pl-4">Rol Institucional</th>
                        <th class="py-3 text-center">Ver PEI</th>
                        <th class="py-3 text-center">Editar PEI</th>
                        <th class="py-3 text-center">Crear Actividades</th>
                        <th class="py-3 text-center">Reportar Tareas</th>
                        <th class="py-3 text-center">Chat de Equipo</th>
                        <th class="py-3 text-center font-weight-bold text-danger">ELIMINAR PEI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="pl-4 font-weight-bold text-dark">👑 Administrador</td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-danger font-weight-bold"><i class="fa fa-check-circle fa-lg"></i> PERMITIDO</td>
                    </tr>
                    <tr>
                        <td class="pl-4 font-weight-bold text-dark">📊 Analista de Planificación</td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-muted"><i class="fa fa-minus"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-secondary font-weight-bold"><i class="fa fa-times-circle fa-lg text-danger"></i> DENEGADO</td>
                    </tr>
                    <tr>
                        <td class="pl-4 font-weight-bold text-dark">📋 Gestor de Actividades</td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-muted"><i class="fa fa-minus"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-secondary font-weight-bold"><i class="fa fa-times-circle fa-lg text-danger"></i> DENEGADO</td>
                    </tr>
                    <tr>
                        <td class="pl-4 font-weight-bold text-dark">👷 Colaborador de Actividades</td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-muted"><i class="fa fa-minus"></i></td>
                        <td class="text-center text-muted"><i class="fa fa-minus"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-secondary font-weight-bold"><i class="fa fa-times-circle fa-lg text-danger"></i> DENEGADO</td>
                    </tr>
                    <tr>
                        <td class="pl-4 font-weight-bold text-dark">🩺 Analista - RIISS</td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-muted"><i class="fa fa-minus"></i></td>
                        <td class="text-center text-muted"><i class="fa fa-minus"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                        <td class="text-center text-secondary font-weight-bold"><i class="fa fa-times-circle fa-lg text-danger"></i> DENEGADO</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
