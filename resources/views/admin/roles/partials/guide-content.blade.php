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
        <div class="role-card h-100 p-4 border rounded shadow-sm bg-white">
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
                    <li>Gestión total de usuarios, asignación de roles y permisos globales.</li>
                    <li>Acceso libre a todos los tableros, proyectos y reportes.</li>
                </ul>
            </div>
            <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
            <div class="small font-weight-bold text-dark">Solo a Directores de IT, Administradores de Sistema o Jefes de Planificación.</div>
        </div>
    </div>

    {{-- Rol Intermedio: Coordinador de Planificación --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="role-card h-100 p-4 border rounded shadow-sm bg-white" style="border-top: 4px solid #3b82f6 !important;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge badge-primary px-3 py-1 font-weight-bold" style="border-radius: 8px;">⭐ ROL INTERMEDIO</span>
                <span class="badge-no-delete"><i class="fa fa-ban mr-1"></i> Control Delegado</span>
            </div>
            <h4 class="font-weight-bold text-dark mb-2">Coordinador de Planificación</h4>
            <p class="text-muted small mb-3">Líder operativo con alcance jerárquico delegado para coordinar dependencias, grupos, usuarios, cruces FODA y proyectos.</p>
            <div class="bg-light p-3 rounded mb-3">
                <div class="font-weight-bold text-dark mb-1 small"><i class="fa fa-check-circle text-success mr-1"></i> Capacidades Principales:</div>
                <ul class="pl-3 mb-0 small text-secondary">
                    <li>Crear y gestionar usuarios en sus grupos subordinados.</li>
                    <li>Control total de Cruces de Ambiente FODA (crear, editar, eliminar cruces).</li>
                    <li>Supervisión de Proyectos Institucionales vinculados al PEI.</li>
                    <li>Consultar Certificación MEF y progreso estratégico.</li>
                    <li><strong class="text-danger">NO PUEDE ELIMINAR</strong> PEIs globales ni usuarios ajenos a su rama.</li>
                </ul>
            </div>
            <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
            <div class="small font-weight-bold text-dark">A coordinadores de área o gerencias que lideran la formulación y ejecución institucional.</div>
        </div>
    </div>

    {{-- Rol 2: Analista de Planificación --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="role-card h-100 p-4 border rounded shadow-sm bg-white">
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
        <div class="role-card h-100 p-4 border rounded shadow-sm bg-white">
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
        <div class="role-card h-100 p-4 border rounded shadow-sm bg-white">
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
                    <li>Adjuntar evidencias, comentar y participar en el chat.</li>
                </ul>
            </div>
            <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
            <div class="small font-weight-bold text-dark">A los miembros de equipo que deben ejecutar y reportar cumplimiento diario.</div>
        </div>
    </div>

    {{-- Rol 5: Analista - RIISS --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="role-card h-100 p-4 border rounded shadow-sm bg-white">
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
                    <li>Obtener insignias de evaluación médica.</li>
                </ul>
            </div>
            <div class="text-xs text-uppercase font-weight-bold text-muted">¿Cuándo asignar?</div>
            <div class="small font-weight-bold text-dark">A evaluadores y auditores de la Red Asistencial de Salud del IPS.</div>
        </div>
    </div>

    {{-- Rol 6: Analista de Monitoreo PEI --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="role-card h-100 p-4 border rounded shadow-sm bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge badge-primary px-3 py-1 font-weight-bold" style="border-radius: 8px;">🚦 MONITOREO</span>
                <span class="badge-no-delete"><i class="fa fa-ban mr-1"></i> Solo Lectura</span>
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
                    <th class="py-3 text-center">Crear Usuarios</th>
                    <th class="py-3 text-center">Reportar Tareas</th>
                    <th class="py-3 text-center">Chat de Equipo</th>
                    <th class="py-3 text-center font-weight-bold text-danger">ELIMINAR PEI / USUARIOS</th>
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
                    <td class="pl-4 font-weight-bold text-dark">⭐ Coordinador de Planificación</td>
                    <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                    <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                    <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                    <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                    <td class="text-center text-success"><i class="fa fa-check-circle fa-lg"></i></td>
                    <td class="text-center text-secondary font-weight-bold"><i class="fa fa-times-circle fa-lg text-danger"></i> DENEGADO</td>
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
                    <td class="text-center text-muted"><i class="fa fa-minus"></i></td>
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
            </tbody>
        </table>
    </div>
</div>
