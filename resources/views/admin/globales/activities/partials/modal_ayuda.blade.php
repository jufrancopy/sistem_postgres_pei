<style>
.ayuda-section { border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
.ayuda-step    { display: flex; gap: 14px; align-items: flex-start; margin-bottom: 14px; }
.step-num      { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; color: #fff; }
.estado-pill   { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600; color: #fff; margin: 2px; }
.tip-card      { background: #f8fafc; border-left: 3px solid #3b82f6; border-radius: 0 8px 8px 0; padding: 10px 14px; margin-bottom: 8px; font-size: .82rem; }
.tip-card i    { color: #3b82f6; }
</style>

<div class="modal fade" id="modalAyuda" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0f172a, #1e40af); border:none">
                <div>
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-book-open mr-2"></i>Guía del Tablero
                        <span class="badge badge-light ml-2" style="font-size:.7rem">
                            {{ $isScrumActivity ? 'Scrum' : 'Kanban' }}
                        </span>
                    </h5>
                    <small style="color:rgba(255,255,255,.6)">Todo lo que necesitás saber para gestionar tus tareas</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body" style="padding: 24px">

                {{-- Qué tipo de tablero es --}}
                <div class="ayuda-section" style="background: linear-gradient(135deg, #eff6ff, #dbeafe)">
                    <h6 class="font-weight-bold mb-2" style="color:#1e40af">
                        <i class="fa {{ $isScrumActivity ? 'fa-sync-alt' : 'fa-stream' }} mr-2"></i>
                        Este es un tablero {{ $isScrumActivity ? 'Scrum' : 'Kanban' }}
                    </h6>
                    @if($isScrumActivity)
                    <p class="mb-0 small" style="color:#1e3a8a">
                        El tablero <strong>Scrum</strong> está organizado por <strong>sprints</strong> (ciclos de trabajo).
                        Cada tarea avanza por etapas hasta completarse. Ideal para proyectos con fechas definidas
                        y equipos que trabajan en ciclos cortos de 1 a 4 semanas.
                    </p>
                    @else
                    <p class="mb-0 small" style="color:#1e3a8a">
                        El tablero <strong>Kanban</strong> tiene <strong>flujo continuo</strong> — no hay ciclos fijos.
                        Las tareas entran al backlog y avanzan según disponibilidad del equipo.
                        Ideal para trabajo operativo continuo con prioridades cambiantes.
                    </p>
                    @endif
                </div>

                {{-- Estados del tablero --}}
                <h6 class="font-weight-bold mb-3">
                    <i class="fa fa-columns mr-2 text-muted"></i>Las columnas del tablero
                </h6>

                @if($isScrumActivity)
                <div class="d-flex flex-wrap mb-4" style="gap:8px">
                    <span class="estado-pill" style="background:#f59e0b"><i class="fa fa-inbox"></i> Backlog / Por hacer</span>
                    <span style="color:#94a3b8;font-size:1.2rem;line-height:2">→</span>
                    <span class="estado-pill" style="background:#3b82f6"><i class="fa fa-spinner"></i> En Progreso</span>
                    <span style="color:#94a3b8;font-size:1.2rem;line-height:2">→</span>
                    <span class="estado-pill" style="background:#8b5cf6"><i class="fa fa-search"></i> En Revisión</span>
                    <span style="color:#94a3b8;font-size:1.2rem;line-height:2">→</span>
                    <span class="estado-pill" style="background:#10b981"><i class="fa fa-check-circle"></i> Hecho</span>
                </div>
                @else
                <div class="d-flex flex-wrap mb-4" style="gap:8px">
                    <span class="estado-pill" style="background:#94a3b8"><i class="fa fa-inbox"></i> Backlog</span>
                    <span style="color:#94a3b8;font-size:1.2rem;line-height:2">→</span>
                    <span class="estado-pill" style="background:#f59e0b"><i class="fa fa-sort-amount-up"></i> Priorizado</span>
                    <span style="color:#94a3b8;font-size:1.2rem;line-height:2">→</span>
                    <span class="estado-pill" style="background:#3b82f6"><i class="fa fa-spinner"></i> En Ejecución</span>
                    <span style="color:#94a3b8;font-size:1.2rem;line-height:2">→</span>
                    <span class="estado-pill" style="background:#8b5cf6"><i class="fa fa-search"></i> En Revisión</span>
                    <span style="color:#94a3b8;font-size:1.2rem;line-height:2">→</span>
                    <span class="estado-pill" style="background:#10b981"><i class="fa fa-check-circle"></i> Finalizado</span>
                </div>
                @endif

                {{-- Cómo crear una tarea --}}
                <h6 class="font-weight-bold mb-3">
                    <i class="fa fa-plus-circle mr-2 text-muted"></i>¿Cómo crear una tarea?
                </h6>
                <div class="ayuda-step">
                    <div class="step-num" style="background:#3b82f6">1</div>
                    <div class="small">
                        <strong>Hacé clic en "Nueva Tarea"</strong> en el botón del encabezado.
                        Se abre un formulario con los campos necesarios.
                    </div>
                </div>
                <div class="ayuda-step">
                    <div class="step-num" style="background:#8b5cf6">2</div>
                    <div class="small">
                        <strong>Completá el título y la descripción</strong> de la tarea.
                        Sé específico para que el responsable entienda exactamente qué hacer.
                    </div>
                </div>
                <div class="ayuda-step">
                    <div class="step-num" style="background:#f59e0b">3</div>
                    <div class="small">
                        <strong>Asigná una etiqueta y color</strong> para agrupar visualmente las tareas
                        por departamento, categoría o proyecto. Podés reutilizar etiquetas existentes haciendo
                        clic en los chips de color que aparecen debajo del campo.
                    </div>
                </div>
                <div class="ayuda-step">
                    <div class="step-num" style="background:#10b981">4</div>
                    <div class="small">
                        <strong>Seleccioná el responsable</strong> — la persona que va a ejecutar la tarea.
                        Luego podés notificarle por email cuando estés listo.
                    </div>
                </div>

                {{-- Acciones de cada tarea --}}
                <h6 class="font-weight-bold mb-3 mt-3">
                    <i class="fa fa-mouse-pointer mr-2 text-muted"></i>Acciones disponibles en cada tarea
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="tip-card">
                            <i class="fa fa-arrow-right mr-2"></i>
                            <strong>Avanzar</strong> — mueve la tarea a la siguiente columna
                        </div>
                        <div class="tip-card">
                            <i class="fa fa-arrow-left mr-2"></i>
                            <strong>Retroceder</strong> — devuelve la tarea a la columna anterior
                        </div>
                        <div class="tip-card">
                            <i class="fa fa-paperclip mr-2"></i>
                            <strong>Evidencia</strong> — adjuntá un enlace, imagen o documento como respaldo
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tip-card">
                            <i class="fa fa-envelope mr-2"></i>
                            <strong>Notificar</strong> — envía un email al responsable de esa tarea específica
                        </div>
                        <div class="tip-card">
                            <i class="fa fa-check-circle mr-2" style="color:#10b981"></i>
                            <strong>Completar</strong> — al llegar a "Hecho", se pide un comentario de cierre obligatorio
                        </div>
                        <div class="tip-card">
                            <i class="fa fa-trash mr-2" style="color:#ef4444"></i>
                            <strong>Eliminar</strong> — borra la tarea y sus evidencias permanentemente
                        </div>
                    </div>
                </div>

                {{-- Notificaciones --}}
                <h6 class="font-weight-bold mb-3 mt-2">
                    <i class="fa fa-bell mr-2 text-muted"></i>Notificaciones
                </h6>
                <div class="tip-card" style="border-left-color:#10b981">
                    <i class="fa fa-paper-plane mr-2" style="color:#10b981"></i>
                    <strong>Notificar a todos</strong> — usá el botón del encabezado cuando quieras enviar
                    un resumen de tareas a <em>todos</em> los responsables al mismo tiempo. Ideal para el inicio de semana o
                    cuando hubo cambios importantes.
                </div>
                <div class="tip-card" style="border-left-color:#f59e0b">
                    <i class="fa fa-envelope mr-2" style="color:#f59e0b"></i>
                    <strong>Notificar individualmente</strong> — el ícono de sobre en cada tarea envía un recordatorio
                    solo a esa persona. Útil cuando una tarea tiene urgencia o cambió de prioridad.
                </div>

                {{-- Vistas --}}
                <h6 class="font-weight-bold mb-3 mt-2">
                    <i class="fa fa-eye mr-2 text-muted"></i>Vistas del tablero
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="tip-card" style="border-left-color:#6366f1">
                            <i class="fa fa-columns mr-2" style="color:#6366f1"></i>
                            <strong>Por estado</strong> — vista clásica en columnas, ordenado por el flujo de trabajo
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tip-card" style="border-left-color:#ec4899">
                            <i class="fa fa-tag mr-2" style="color:#ec4899"></i>
                            <strong>Por etiqueta</strong> — agrupa todas las tareas según su etiqueta/color,
                            sin importar el estado. Ideal para ver la carga por departamento o categoría.
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer" style="background:#f8fafc">
                <small class="text-muted mr-auto">
                    <i class="fa fa-lightbulb text-warning mr-1"></i>
                    Recordá: solo podés mover tus propias tareas asignadas
                </small>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-check mr-1"></i>Entendido
                </button>
            </div>
        </div>
    </div>
</div>
