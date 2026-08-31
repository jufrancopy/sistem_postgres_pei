<?php

use App\Http\Controllers\Admin\Globales\OpenAIController;
use App\Http\Controllers\Admin\Globales\Survey\QuestionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Planificacion\Coordinador\CoordinadorPlanificacionController;


Route::get('/', 'WelcomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/home-config', 'Admin\HomeConfigController@edit')->name('home-config.edit');
Route::put('/home-config', 'Admin\HomeConfigController@update')->name('home-config.update');
Route::patch('/home-config/toggle', 'Admin\HomeConfigController@toggle')->name('home-config.toggle');
Route::patch('/home-config/save',   'Admin\HomeConfigController@save')->name('home-config.save');

// ── Manifiesto & Propósito Institucional SIPLAN ─────────────────────────────────
Route::get('/nosotros', [\App\Http\Controllers\Admin\Planificacion\PublicPeiController::class, 'manifesto'])->name('siplan.manifesto');
Route::get('/public/nosotros', [\App\Http\Controllers\Admin\Planificacion\PublicPeiController::class, 'manifesto']);

// ── Vistas públicas PEI & Asesoría (sin autenticación) ────────────────────────
Route::get('/public/pei/{token}', 'Admin\Planificacion\PublicPeiController@show')->name('pei.public.show');
Route::get('/public/pei-asesor/{token}', 'Admin\Planificacion\PublicPeiController@showAsesor')->name('pei.asesor.public.show');

// Portal de Asesoría Externa (Correo + Código Único)
Route::get('/public/asesoria/login', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'loginForm'])->name('asesoria.public.login');
Route::post('/public/asesoria/login', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'loginSubmit'])->name('asesoria.public.login.submit');
Route::get('/public/asesoria/logout', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'logout'])->name('asesoria.public.logout');
Route::get('/public/asesoria/{id}', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'showPortal'])->name('asesoria.public.portal');
Route::post('/public/asesoria/{id}/comentario', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'guardarComentario'])->name('asesoria.public.comentario');
Route::post('/public/asesoria/{id}/finalizar', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'finalizarDictamen'])->name('asesoria.public.finalizar');

// ── Vistas públicas Acta de Reunión MECIP (sin autenticación) ─────────────────
Route::get('/actas-reunion/{token}', 'Admin\Globales\ActaMecipController@publicView')->name('actas.public.show');
Route::post('/actas-reunion/{token}/registro', 'Admin\Globales\ActaMecipController@publicRegistrar')->name('actas.public.registrar');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('products', 'Admin\ProductController');

    // ── Perfil de Usuario y Gamificación ───────────────────────────────────────
    Route::get('/perfil/{id?}', 'Admin\UserProfileController@show')->name('user.profile');
    Route::get('/perfil/{id}/puntos', 'Admin\UserProfileController@pointsDetails')->name('user.profile.points');
    Route::post('/perfil/avatar', 'Admin\UserProfileController@updateAvatar')->name('user.profile.avatar');
    Route::post('/perfil/detalles', 'Admin\UserProfileController@updateDetails')->name('user.profile.details');
    Route::post('/perfil/password', 'Admin\UserProfileController@updatePassword')->name('user.profile.password');
    Route::post('/gamification/recalculate', 'Admin\GamificationAdminController@recalculate')->name('gamification.recalculate');
    Route::post('pei-profiles/{idProfile}/gamification/award-manual', 'Admin\GamificationAdminController@awardManual')->name('pei-profiles.gamification.award-manual');
    Route::get('pei-profiles/{idProfile}/gamification/motivos', 'Admin\GamificationAdminController@motivosSugeridos')->name('pei-profiles.gamification.motivos');
    Route::get('pei-profiles/{idProfile}/gamification/buscar-usuarios', 'Admin\GamificationAdminController@buscarUsuarios')->name('pei-profiles.gamification.buscar-usuarios');
    Route::get('pei-profiles/{idProfile}/gamification/historial-manual', 'Admin\GamificationAdminController@historialManual')->name('pei-profiles.gamification.historial-manual');
    Route::get('pei-profiles/{profileId}/gamification/editores', 'Admin\GamificationAdminController@editoresPorNodo')->name('pei-profiles.gamification.editores');
    Route::get('pei-profiles/{idProfile}/gamification/ranking', 'Admin\Planificacion\Pei\PeiController@rankingTalentoHumano')->name('pei-profiles.gamification.ranking');
    Route::get('/profile', function () { return redirect()->route('user.profile'); })->name('profile.edit');

    // ── Mis Tareas & Telemetría (colaboradores) ──────────────────────────────
    Route::get('mis-tareas/{activityId}', 'Admin\Globales\ActivityController@misTareas')->name('globales.mis-tareas');
    Route::get('mis-actividades', 'Admin\Globales\ActivityController@misActividades')->name('globales.activities.mis-actividades');
    Route::get('admin/globales/users/{id}/telemetry', 'Admin\UserTelemetryController@getTelemetry')->name('globales.users.telemetry');

    //Rutas del Dpto. Planificacion
    Route::get('planificacion-dashboard', 'Admin\Planificacion\PlanificacionController@dashboard')->name('planificacion-dashboard');
    Route::post('planificacion-dashboard/ejecutar-diagnostico', 'Admin\Planificacion\PlanificacionController@ejecutarDiagnostico')->name('planificacion-dashboard.ejecutar-diagnostico');
    Route::post('planificacion-dashboard/guardar-pei', 'Admin\Planificacion\PlanificacionController@guardarPeiSeleccionado')->name('planificacion-dashboard.guardar-pei');

    // ── Plan Maestro & Iniciativas de Mejora Continua ────────────────────────
    Route::post('plan-maestro/iniciativas', 'Admin\PlanMaestro\PlanMaestroController@storeIniciativa')->name('plan-maestro.iniciativa.store');
    Route::patch('plan-maestro/acciones/{accion}/estado', 'Admin\PlanMaestro\PlanMaestroController@actualizarEstado')->name('plan-maestro.accion.estado');
    Route::delete('plan-maestro/acciones/{accion}', 'Admin\PlanMaestro\PlanMaestroController@destroyAccion')->name('plan-maestro.accion.destroy');

    Route::get('plan-maestro', 'Admin\PlanMaestro\PlanMaestroController@index')->name('plan-maestro.index');
    Route::get('plan-maestro/{plan}', 'Admin\PlanMaestro\PlanMaestroController@show')->name('plan-maestro.show')->where('plan', '[0-9]+');
    Route::get('plan-maestro/{plan}/buscar', 'Admin\PlanMaestro\PlanMaestroController@buscar')->name('plan-maestro.buscar')->where('plan', '[0-9]+');
    Route::post('plan-maestro/{plan}/acciones', 'Admin\PlanMaestro\PlanMaestroController@storeAccion')->name('plan-maestro.accion.store')->where('plan', '[0-9]+');

    //Rutas de PEI
    Route::patch('pei-profiles/{id}/toggle-status', 'Admin\Planificacion\Pei\PeiController@toggleStatus')->name('pei-profiles.toggle-status');
    Route::resource('pei-profiles', 'Admin\Planificacion\Pei\PeiController');
    Route::get('pei-profiles/{idPerfil}/detail', 'Admin\Planificacion\Pei\PeiController@showDetailForGroup');
    Route::get('pei-profiles-compareHistorical', 'Admin\Planificacion\Pei\PeiController@compareHistorical')->name('pei-profiles-compareHistorical');
    Route::get('pei-profiles-details/{idProfile}', 'Admin\Planificacion\Pei\PeiController@showDetailsTree')->name('pei-profiles.details');
    Route::get('pei-profiles-details/{idProfile}/pdf', 'Admin\Planificacion\Pei\PeiController@exportPdf')->name('pei-profiles.details.pdf');
    Route::get('pei-profiles/{idProfile}/axis-list', 'Admin\Planificacion\Pei\PeiController@showAxisList')->name('pei-profiles-axis-list');
    Route::get('pei-profiles/{idProfile}/goals-list', 'Admin\Planificacion\Pei\PeiController@showGoalsList')->name('pei-profiles-goals-list');
    Route::get('pei-profiles/{idProfile}/actions-list', 'Admin\Planificacion\Pei\PeiController@showActionsList')->name('pei-profiles-actions-list');
    Route::get('pei-profiles/{idProfile}/members-list', 'Admin\Planificacion\Pei\PeiController@showMembersList')->name('pei-profiles-members-list');
    Route::get('pei-profiles/{idProfile}/report-progress', 'Admin\Planificacion\Pei\PeiController@getDetails')->name('pei-profiles-reports');
    Route::get('pei-profiles/{idProfile}/semaforo', 'Admin\Planificacion\Pei\PeiController@getSemaforo')->name('pei-profiles.semaforo');
    Route::post('pei-profiles/{idProfile}/raci', 'Admin\Planificacion\Pei\PeiController@syncRaci')->name('pei-profiles.raci.sync');
    Route::get('pei-profiles/{idProfile}/alertas-presupuestarias', 'Admin\Planificacion\Pei\PeiController@getAlertasPresupuestarias')->name('pei-profiles.alertas-presupuestarias');
    Route::get('pei-profiles/{idProfile}/proceso', 'Admin\Planificacion\Pei\PeiController@proceso')->name('pei-profiles.proceso');
    Route::get('pei-profiles/{idProfile}/certificacion-mef', 'Admin\Planificacion\Pei\PeiController@certificacionMef')->name('pei-profiles.certificacion-mef');
    Route::get('pei-profiles/{peiId}/actores', 'Admin\Planificacion\Pei\PeiActorController@index')->name('pei-actores.index');
    Route::post('pei-profiles/{peiId}/actores', 'Admin\Planificacion\Pei\PeiActorController@store')->name('pei-actores.store');
    Route::delete('pei-profiles/{peiId}/actores/{actorId}', 'Admin\Planificacion\Pei\PeiActorController@destroy')->name('pei-actores.destroy');
    Route::get('instituciones-paraguay/buscar', 'Admin\Planificacion\Pei\PeiActorController@buscarInstituciones')->name('instituciones-paraguay.buscar');
    Route::get('pei-profiles/{profileId}/accordion',    'Admin\Planificacion\Pei\PeiController@accordion')->name('pei-profiles.accordion');
    Route::post('pei-profiles/{id}/reordenar',           'Admin\Planificacion\Pei\PeiController@reordenar')->name('pei-profiles.reordenar');
    Route::get('pei-profiles/{idProfile}/tree-draggable', 'Admin\Planificacion\Pei\PeiController@getTreeDraggable')->name('pei-profiles.tree-draggable');
    Route::post('pei-profiles/{idProfile}/reordenar-tree', 'Admin\Planificacion\Pei\PeiController@reordenarTree')->name('pei-profiles.reordenar-tree');
    Route::post('pei-profiles/{idProfile}/parameters',   'Admin\Planificacion\Pei\PeiController@updateParameters')->name('pei-profiles.update-parameters');
    Route::get('pei-profiles/{idProfile}/matriz',        'Admin\Planificacion\Pei\PeiController@matriz')->name('pei-profiles.matriz');
    Route::get('pei-profiles/{idProfile}/matriz/pdf',    'Admin\Planificacion\Pei\PeiController@matrizPdf')->name('pei-profiles.matriz.pdf');


    Route::get('pei-profiles/{idProfile}/vista-asesor', 'Admin\Planificacion\Pei\PeiController@vistaAsesor')->name('pei-profiles.vista-asesor');
    Route::post('pei-profiles/{idProfile}/guardar-comentario-asesor', 'Admin\Planificacion\Pei\PeiController@guardarComentarioAsesor')->name('pei-profiles.guardar-comentario-asesor');
    Route::post('pei-profiles/{idProfile}/asesor-token', 'Admin\Planificacion\Pei\PeiController@generarTokenAsesor')->name('pei.asesor.token.generate');
    Route::delete('pei-profiles/{idProfile}/asesor-token', 'Admin\Planificacion\Pei\PeiController@revocarTokenAsesor')->name('pei.asesor.token.revoke');
    Route::post('pei-profiles/{idProfile}/convocar-asesor', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'convocarStore'])->name('pei.asesor.convocar');
    Route::get('pei-profiles/{idProfile}/asesorias', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'listarAsesorias'])->name('pei.asesor.listar');
    Route::get('pei-profiles/{idProfile}/asesorias/reporte', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'reporteAportes'])->name('pei.asesor.reporte');
    Route::post('pei-asesorias/comentarios/{commentId}/integrar', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'integrarAporte'])->name('pei.asesor.comentario.integrar');
    Route::delete('pei-asesorias/comentarios/{commentId}', [\App\Http\Controllers\Admin\Planificacion\PeiAsesoriaController::class, 'eliminarAporte'])->name('pei.asesor.comentario.eliminar');
    Route::get('pei-profiles/{idProfile}/basurero', 'Admin\Planificacion\Pei\PeiController@basureroList')->name('pei.basurero.list');
    Route::post('pei-profiles/{idProfile}/basurero/restaurar-nodo/{nodeId}', 'Admin\Planificacion\Pei\PeiController@restaurarNodo')->name('pei.basurero.restaurar-nodo');
    Route::post('pei-profiles/{idProfile}/basurero/restaurar-iniciativa/{iniId}', 'Admin\Planificacion\Pei\PeiController@restaurarIniciativa')->name('pei.basurero.restaurar-iniciativa');
    Route::post('pei-profiles/{idProfile}/revertir-edicion/{editId}', 'Admin\Planificacion\Pei\PeiController@revertirEdicion')->name('pei.edicion.revertir');

    // ── Coordinador de Planificación & Proyectos ─────────────────────────────────────────
    Route::prefix('coordinador-planificacion')->name('coordinador.')->middleware(['role:Coordinador de Planificación|Analista de Planificación|Administrador|Coordinador de Proyectos|Coordinación de Proyectos'])->group(function () {
        Route::get('/', function() { return redirect()->route('globales.dashboard'); })->name('index');
        Route::get('gestionar-grupos', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@gestionarGrupos')->name('gestionar-grupos');
        Route::get('crear-actividad', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@crearActividad')->name('crear-actividad');
        Route::get('ver-grupos-usuarios', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@verGruposYUsuarios')->name('ver-grupos-usuarios');
        Route::get('ver-organigrama', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@verOrganigrama')->name('ver-organigrama');

        // Endpoints CRUD para Usuarios en Ámbito
        Route::get('usuarios/data', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@getUsuariosData')->name('usuarios.data');
        Route::post('usuarios', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@storeUsuario')->name('usuarios.store');
        Route::get('usuarios/{id}/edit', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@editUsuario')->name('usuarios.edit');
        Route::delete('usuarios/{id}', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@destroyUsuario')->name('usuarios.destroy');

        // Endpoints CRUD para Grupos de Trabajo en Ámbito
        Route::get('grupos/data', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@getGruposData')->name('grupos.data');
        Route::post('grupos', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@storeGrupo')->name('grupos.store');
        Route::get('grupos/{id}/edit', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@editGrupo')->name('grupos.edit');
        Route::delete('grupos/{id}', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@destroyGrupo')->name('grupos.destroy');
        Route::get('grupos/{id}/miembros', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@getGrupoMiembros')->name('grupos.miembros');
        Route::post('grupos/{id}/miembros/asignar', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@asignarMiembroGrupo')->name('grupos.miembros.asignar');
        Route::post('grupos/{id}/miembros/crear', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@crearYAsignarMiembroGrupo')->name('grupos.miembros.crear');
        Route::delete('grupos/{id}/miembros/{userId}', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@removerMiembroGrupo')->name('grupos.miembros.remover');

        // Endpoints CRUD para Organigrama en Ámbito
        Route::get('organigrama/tree', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@getOrganigramaTree')->name('organigrama.tree');
        Route::post('organigrama', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@storeDependencia')->name('organigrama.store');
        Route::get('organigrama/{id}/edit', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@editDependencia')->name('organigrama.edit');
        Route::delete('organigrama/{id}', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@destroyDependencia')->name('organigrama.destroy');
        Route::post('organigrama/{id}/mover', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@moverOrganigrama')->name('organigrama.mover');

        // Endpoints para Planes Institucionales (PEI)
        Route::get('planes/data', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@getPlanesData')->name('planes.data');

        // Endpoints para Proyectos Institucionales
        Route::get('proyectos/data', 'Admin\Planificacion\Coordinador\CoordinadorPlanificacionController@getProyectosData')->name('proyectos.data');
    });

    // Chat PEI
    Route::get('pei-profiles/{profileId}/chat/messages', 'Admin\Planificacion\Pei\PeiChatController@getMessages')->name('pei-chat.messages');
    Route::post('pei-profiles/{profileId}/chat/messages', 'Admin\Planificacion\Pei\PeiChatController@storeMessage')->name('pei-chat.store');
    Route::post('pei-profiles/{profileId}/chat/messages/{messageId}/react', 'Admin\Planificacion\Pei\PeiChatController@toggleReaction')->name('pei-chat.react');
    Route::get('pei-profiles/{profileId}/chat/unread', 'Admin\Planificacion\Pei\PeiChatController@getUnreadCount')->name('pei-chat.unread');
    Route::post('pei-profiles/{profileId}/chat/read', 'Admin\Planificacion\Pei\PeiChatController@markRead')->name('pei-chat.read');
    Route::post('pei-profiles/{profileId}/chat/donate', 'Admin\Planificacion\Pei\PeiChatController@donatePoints')->name('pei-chat.donate');

    // Alias con prefijo legacy/admin para compatibilidad total
    Route::get('admin/planificacion/pei-profiles/{profileId}/chat/messages', 'Admin\Planificacion\Pei\PeiChatController@getMessages');
    Route::post('admin/planificacion/pei-profiles/{profileId}/chat/messages', 'Admin\Planificacion\Pei\PeiChatController@storeMessage');
    Route::post('admin/planificacion/pei-profiles/{profileId}/chat/messages/{messageId}/react', 'Admin\Planificacion\Pei\PeiChatController@toggleReaction');
    Route::get('admin/planificacion/pei-profiles/{profileId}/chat/unread', 'Admin\Planificacion\Pei\PeiChatController@getUnreadCount');
    Route::post('admin/planificacion/pei-profiles/{profileId}/chat/read', 'Admin\Planificacion\Pei\PeiChatController@markRead');
    Route::post('admin/planificacion/pei-profiles/{profileId}/chat/donate', 'Admin\Planificacion\Pei\PeiChatController@donatePoints');

    // Relevamientos
    Route::get('proyectos-epc-relevamientos/{estandarId}', 'Admin\Proyectos\EPC\RelevamientoController@getFormulario')->name('proyectos-epc-relevamientos-form-dependencia');

    // Rutas de Estadisticas — redirige al nuevo SIESS
    Route::get('estadisticas-dashboard', function() {
        return redirect()->route('siess.dashboard');
    })->name('estadisticas-dashboard');

    // ── SIESS — Sistema de Estadísticas e Información (Res. 266/2022) ──────────
    Route::prefix('siess')->name('siess.')->group(function () {
        Route::get('/',                    'Admin\Estadistica\SiessController@dashboard')->name('dashboard');
        Route::get('/extractos',           'Admin\Estadistica\SiessController@index')->name('extractos.index');
        Route::post('/extractos',          'Admin\Estadistica\SiessController@store')->name('extractos.store');
        Route::get('/extractos/{id}/edit', 'Admin\Estadistica\SiessController@edit')->name('extractos.edit');
        Route::delete('/extractos/{id}',   'Admin\Estadistica\SiessController@destroy')->name('extractos.destroy');

        // Flujo de validación (Art. 8)
        Route::post('/extractos/{id}/enviar-validacion', 'Admin\Estadistica\SiessController@enviarValidacion')->name('extractos.enviar');
        Route::post('/extractos/{id}/aprobar',           'Admin\Estadistica\SiessController@aprobar')->name('extractos.aprobar');
        Route::post('/extractos/{id}/objetar',           'Admin\Estadistica\SiessController@objetar')->name('extractos.objetar');
        Route::post('/extractos/{id}/fuente-unica',      'Admin\Estadistica\SiessController@marcarFuenteUnica')->name('extractos.fuente-unica');

        // API para selects dinámicos
        Route::get('/modulos/{moduloId}/indicadores', 'Admin\Estadistica\SiessController@getIndicadoresPorModulo')->name('indicadores');
        Route::get('/periodos',                        'Admin\Estadistica\SiessController@getPeriodos')->name('periodos');
        Route::get('/establecimientos',                'Admin\Estadistica\SiessController@getEstablecimientos')->name('establecimientos');

        // Notificaciones
        Route::get('/notificaciones',                  'Admin\Estadistica\SiessController@notificaciones')->name('notificaciones');
        Route::post('/notificaciones/{id}/leer',       'Admin\Estadistica\SiessController@marcarNotificacionLeida')->name('notificaciones.leer');
        Route::post('/notificaciones/leer-todas',      'Admin\Estadistica\SiessController@marcarTodasLeidas')->name('notificaciones.leer-todas');

        // ── EPH — Contexto Nacional (INE Paraguay) ────────────────────────────
        Route::get('/eph',           'Admin\Estadistica\EphController@index')->name('eph.index');
        Route::get('/eph/nuevo',     'Admin\Estadistica\EphController@create')->name('eph.create');
        Route::post('/eph',          'Admin\Estadistica\EphController@store')->name('eph.store');
        Route::get('/eph/{id}',      'Admin\Estadistica\EphController@show')->name('eph.show');
        Route::post('/eph/{id}',     'Admin\Estadistica\EphController@show')->name('eph.show.data');
        Route::delete('/eph/{id}',   'Admin\Estadistica\EphController@destroy')->name('eph.destroy');

        // ── DGEEC — Interpretación EPHC + KPIs cruzados con IPS ──────────────
        Route::get('/dgeec',                          'Admin\Estadistica\DgeecController@index')->name('dgeec.index');        Route::get('/dgeec/brecha',                   'Admin\Estadistica\DgeecController@brecha')->name('dgeec.brecha');
        Route::get('/dgeec/mapear/{datasetId}',       'Admin\Estadistica\DgeecController@mapear')->name('dgeec.mapear');
        Route::post('/dgeec/procesar/{datasetId}',    'Admin\Estadistica\DgeecController@procesar')->name('dgeec.procesar');
        Route::get('/dgeec/chart/penetracion',        'Admin\Estadistica\DgeecController@chartPenetracion')->name('dgeec.chart.penetracion');
        Route::get('/dgeec/chart/informalidad',       'Admin\Estadistica\DgeecController@chartInformalidad')->name('dgeec.chart.informalidad');

        // ── Contexto Nacional — MPI + Vivienda + Demografía ──────────────────
        Route::get('/contexto',                            'Admin\Estadistica\ContextoNacionalController@index')->name('contexto.index');
        Route::get('/contexto/mpi',                        'Admin\Estadistica\ContextoNacionalController@mpi')->name('contexto.mpi');
        Route::get('/contexto/vivienda',                   'Admin\Estadistica\ContextoNacionalController@vivienda')->name('contexto.vivienda');
        Route::post('/contexto/mpi/{datasetId}',           'Admin\Estadistica\ContextoNacionalController@procesarMpi')->name('contexto.mpi.procesar');
        Route::post('/contexto/vivienda/{datasetId}',      'Admin\Estadistica\ContextoNacionalController@procesarVivienda')->name('contexto.vivienda.procesar');
        Route::get('/contexto/chart/mpi',                  'Admin\Estadistica\ContextoNacionalController@chartMpi')->name('contexto.chart.mpi');
        Route::get('/contexto/chart/vivienda',             'Admin\Estadistica\ContextoNacionalController@chartVivienda')->name('contexto.chart.vivienda');
        Route::get('/contexto/chart/riesgo',               'Admin\Estadistica\ContextoNacionalController@chartRiesgo')->name('contexto.chart.riesgo');

        // ── Vistas por módulo ──────────────────────────────────────────────────
        Route::get('/modulos/aop', 'Admin\Estadistica\SiessModuloController@aop')->name('modulos.aop');
        Route::get('/modulos/ju',  'Admin\Estadistica\SiessModuloController@ju')->name('modulos.ju');
        Route::get('/modulos/dt',  'Admin\Estadistica\SiessModuloController@dt')->name('modulos.dt');
        Route::get('/modulos/rl',  'Admin\Estadistica\SiessModuloController@rl')->name('modulos.rl');
        Route::get('/modulos/di',  'Admin\Estadistica\SiessModuloController@di')->name('modulos.di');
        Route::get('/modulos/rh',  'Admin\Estadistica\SiessModuloController@rh')->name('modulos.rh');
        Route::get('/modulos/cau', 'Admin\Estadistica\SiessModuloController@cau')->name('modulos.cau');

        // Carga masiva de datos estructurados
        Route::post('/modulos/{modulo}/datos', 'Admin\Estadistica\SiessModuloController@storeDatos')->name('modulos.datos.store');

        // ── Reportes Gerenciales ───────────────────────────────────────────────
        Route::get('/reportes',      'Admin\Estadistica\SiessReporteController@index')->name('reportes.index');
        Route::get('/reportes/pdf',  'Admin\Estadistica\SiessReporteController@pdfGerencial')->name('reportes.pdf');
        Route::get('/reportes/csv',  'Admin\Estadistica\SiessReporteController@exportarCsv')->name('reportes.csv');
    });

    // ── Bioestadísticas ────────────────────────────────────────────────────────
    Route::prefix('bioestadistica')->name('bioestadistica.')->middleware([
        'role:Administrador|Analista de Bioestadística|Digitador Bioestadística|Consultor Bioestadística|Auditor Bioestadística',
    ])->group(function () {
        Route::get('/', 'Admin\Bioestadistica\BioestadisticaDashboardController@index')
            ->middleware('permission:bio.dashboard.view')->name('dashboard');
        Route::get('/dashboard', 'Admin\Bioestadistica\BioestadisticaDashboardController@index')
            ->middleware('permission:bio.dashboard.view');

        Route::get('/configuraciones', 'Admin\Bioestadistica\ConfiguracionesController@index')
            ->name('configuraciones.index');

        Route::get('/auditoria', 'Admin\Bioestadistica\AuditoriaController@index')
            ->middleware('permission:bio.audit.view')->name('auditoria.index');
        Route::get('/auditoria/datatable', 'Admin\Bioestadistica\AuditoriaController@datatable')
            ->middleware('permission:bio.audit.view')->name('auditoria.datatable');
        Route::get('/auditoria/{auditoria}', 'Admin\Bioestadistica\AuditoriaController@show')
            ->middleware('permission:bio.audit.view')->name('auditoria.show');

        Route::get('/geografia', 'Admin\Bioestadistica\GeografiaController@index')
            ->middleware('permission:bio.geo.view')->name('geografia.index');
        Route::get('/geografia/datatable', 'Admin\Bioestadistica\GeografiaController@datatable')
            ->middleware('permission:bio.geo.view')->name('geografia.datatable');
        Route::get('/geografia/distritos', 'Admin\Bioestadistica\GeografiaController@distritos')
            ->middleware('permission:bio.geo.view')->name('geografia.distritos');
        Route::get('/geografia/establecimientos', 'Admin\Bioestadistica\GeografiaController@establecimientos')
            ->middleware('permission:bio.geo.view')->name('geografia.establecimientos');
        Route::get('/geografia/establecimientos/{establecimiento}/editar', 'Admin\Bioestadistica\GeografiaController@editEstablecimiento')
            ->middleware('permission:bio.geo.update')->name('geografia.establecimientos.edit');
        Route::post('/geografia/departamentos', 'Admin\Bioestadistica\GeografiaController@storeDepartamento')
            ->middleware('permission:bio.geo.create')->name('geografia.departamentos.store');
        Route::post('/geografia/distritos', 'Admin\Bioestadistica\GeografiaController@storeDistrito')
            ->middleware('permission:bio.geo.create')->name('geografia.distritos.store');
        Route::post('/geografia/establecimientos', 'Admin\Bioestadistica\GeografiaController@storeEstablecimiento')
            ->middleware('permission:bio.geo.create')->name('geografia.establecimientos.store');
        Route::put('/geografia/establecimientos/{establecimiento}', 'Admin\Bioestadistica\GeografiaController@updateEstablecimiento')
            ->middleware('permission:bio.geo.update')->name('geografia.establecimientos.update');
        Route::delete('/geografia/establecimientos/{establecimiento}', 'Admin\Bioestadistica\GeografiaController@destroyEstablecimiento')
            ->middleware('permission:bio.geo.delete')->name('geografia.establecimientos.destroy');

        Route::get('/estructura', 'Admin\Bioestadistica\EstablecimientoEstructuraController@index')
            ->middleware('permission:bio.geo.view')->name('estructura.index');
        Route::get('/estructura/cortes', 'Admin\Bioestadistica\EstablecimientoEstructuraController@cortes')
            ->middleware('permission:bio.record.create')->name('estructura.cortes');
        Route::post('/estructura', 'Admin\Bioestadistica\EstablecimientoEstructuraController@store')
            ->middleware('permission:bio.geo.create')->name('estructura.store');
        Route::delete('/estructura/{unidad}', 'Admin\Bioestadistica\EstablecimientoEstructuraController@destroy')
            ->middleware('permission:bio.geo.delete')->name('estructura.destroy');

        Route::get('/clasificaciones', 'Admin\Bioestadistica\ClasificacionController@index')
            ->middleware('permission:bio.geo.view')->name('clasificaciones.index');
        Route::get('/clasificaciones/servicios', 'Admin\Bioestadistica\ClasificacionController@servicios')
            ->middleware('permission:bio.geo.view')->name('clasificaciones.servicios');
        Route::post('/clasificaciones/{tipo}', 'Admin\Bioestadistica\ClasificacionController@store')
            ->middleware('permission:bio.geo.create')->name('clasificaciones.store');
        Route::put('/clasificaciones/{tipo}/{id}', 'Admin\Bioestadistica\ClasificacionController@update')
            ->middleware('permission:bio.geo.update')->name('clasificaciones.update');
        Route::delete('/clasificaciones/{tipo}/{id}', 'Admin\Bioestadistica\ClasificacionController@destroy')
            ->middleware('permission:bio.geo.delete')->name('clasificaciones.destroy');

        Route::get('/diccionario', 'Admin\Bioestadistica\DiccionarioController@index')
            ->middleware('permission:bio.catalog.view')->name('diccionario.index');
        Route::post('/diccionario/variables', 'Admin\Bioestadistica\DiccionarioController@storeVariable')
            ->middleware('permission:bio.catalog.create')->name('diccionario.variables.store');
        Route::put('/diccionario/variables/{variable}', 'Admin\Bioestadistica\DiccionarioController@updateVariable')
            ->middleware('permission:bio.catalog.update')->name('diccionario.variables.update');
        Route::delete('/diccionario/variables/{variable}', 'Admin\Bioestadistica\DiccionarioController@destroyVariable')
            ->middleware('permission:bio.catalog.delete')->name('diccionario.variables.destroy');
        Route::get('/diccionario/{variable}', 'Admin\Bioestadistica\DiccionarioController@show')
            ->middleware('permission:bio.catalog.view')->name('diccionario.show');
        Route::post('/diccionario/{variable}/detalles', 'Admin\Bioestadistica\DiccionarioController@storeDetalle')
            ->middleware('permission:bio.catalog.update')->name('diccionario.detalles.store');
        Route::put('/diccionario/detalles/{detalle}', 'Admin\Bioestadistica\DiccionarioController@updateDetalle')
            ->middleware('permission:bio.catalog.update')->name('diccionario.detalles.update');
        Route::delete('/diccionario/detalles/{detalle}', 'Admin\Bioestadistica\DiccionarioController@destroyDetalle')
            ->middleware('permission:bio.catalog.delete')->name('diccionario.detalles.destroy');
        Route::post('/diccionario/detalles/{detalle}/prestaciones', 'Admin\Bioestadistica\DiccionarioController@storePrestacion')
            ->middleware('permission:bio.catalog.update')->name('diccionario.prestaciones.store');
        Route::put('/diccionario/prestaciones/{prestacion}', 'Admin\Bioestadistica\DiccionarioController@updatePrestacion')
            ->middleware('permission:bio.catalog.update')->name('diccionario.prestaciones.update');
        Route::delete('/diccionario/prestaciones/{prestacion}', 'Admin\Bioestadistica\DiccionarioController@destroyPrestacion')
            ->middleware('permission:bio.catalog.delete')->name('diccionario.prestaciones.destroy');

        Route::get('/formularios', 'Admin\Bioestadistica\FormularioController@index')
            ->middleware('permission:bio.form.view')->name('formularios.index');
        Route::post('/formularios', 'Admin\Bioestadistica\FormularioController@store')
            ->middleware('permission:bio.form.create')->name('formularios.store');
        Route::get('/formularios/{formulario}/editar', 'Admin\Bioestadistica\FormularioController@edit')
            ->middleware('permission:bio.form.view')->name('formularios.edit');
        Route::put('/formularios/{formulario}', 'Admin\Bioestadistica\FormularioController@update')
            ->middleware('permission:bio.form.update')->name('formularios.update');
        Route::delete('/formularios/{formulario}', 'Admin\Bioestadistica\FormularioController@destroy')
            ->middleware('permission:bio.form.delete')->name('formularios.destroy');
        Route::post('/formularios/{formulario}/publicar', 'Admin\Bioestadistica\FormularioController@publish')
            ->middleware('permission:bio.form.publish')->name('formularios.publish');
        Route::post('/formularios/{formulario}/secciones', 'Admin\Bioestadistica\FormularioController@storeSeccion')
            ->middleware('permission:bio.form.update')->name('formularios.secciones.store');
        Route::put('/secciones/{seccion}', 'Admin\Bioestadistica\FormularioController@updateSeccion')
            ->middleware('permission:bio.form.update')->name('secciones.update');
        Route::delete('/secciones/{seccion}', 'Admin\Bioestadistica\FormularioController@destroySeccion')
            ->middleware('permission:bio.form.update')->name('secciones.destroy');
        Route::post('/secciones/{seccion}/campos', 'Admin\Bioestadistica\FormularioController@storeField')
            ->middleware('permission:bio.form.update')->name('secciones.fields.store');
        Route::put('/campos/{field}', 'Admin\Bioestadistica\FormularioController@updateField')
            ->middleware('permission:bio.form.update')->name('fields.update');
        Route::delete('/campos/{field}', 'Admin\Bioestadistica\FormularioController@destroyField')
            ->middleware('permission:bio.form.update')->name('fields.destroy');

        Route::get('/captura', 'Admin\Bioestadistica\CapturaController@index')
            ->middleware('permission:bio.record.view')->name('captura.index');
        Route::get('/captura/datatable', 'Admin\Bioestadistica\CapturaController@datatable')
            ->middleware('permission:bio.record.view')->name('captura.datatable');
        Route::get('/captura/nueva', 'Admin\Bioestadistica\CapturaController@create')
            ->middleware('permission:bio.record.create')->name('captura.create');
        Route::get('/captura/pendientes', 'Admin\Bioestadistica\CapturaController@pending')
            ->middleware('permission:bio.record.view')->name('captura.pending');
        Route::post('/captura', 'Admin\Bioestadistica\CapturaController@store')
            ->middleware('permission:bio.record.create')->name('captura.store');
        Route::get('/captura/importar', 'Admin\Bioestadistica\SpPlanillaImportController@index')
            ->middleware('permission:bio.record.create')->name('captura.import.index');
        Route::post('/captura/importar/analizar', 'Admin\Bioestadistica\SpPlanillaImportController@analyze')
            ->middleware('permission:bio.record.create')->name('captura.import.analyze');
        Route::get('/captura/importar/resumen', 'Admin\Bioestadistica\SpPlanillaImportController@summary')
            ->middleware('permission:bio.record.create')->name('captura.import.summary');
        Route::get('/captura/importar/vista-previa', 'Admin\Bioestadistica\SpPlanillaImportController@preview')
            ->middleware('permission:bio.record.create')->name('captura.import.preview');
        Route::post('/captura/importar/confirmar', 'Admin\Bioestadistica\SpPlanillaImportController@confirm')
            ->middleware('permission:bio.record.create')->name('captura.import.confirm');
        Route::post('/captura/importar/confirmar-lote', 'Admin\Bioestadistica\SpPlanillaImportController@confirmBatch')
            ->middleware('permission:bio.record.create')->name('captura.import.confirm-batch');
        Route::post('/captura/importar/descartar', 'Admin\Bioestadistica\SpPlanillaImportController@discard')
            ->middleware('permission:bio.record.create')->name('captura.import.discard');
        Route::get('/captura/{record}', 'Admin\Bioestadistica\CapturaController@edit')
            ->middleware('permission:bio.record.view')->name('captura.edit');
        Route::put('/captura/{record}', 'Admin\Bioestadistica\CapturaController@update')
            ->middleware('permission:bio.record.update')->name('captura.update');
        Route::post('/captura/{record}/autosave', 'Admin\Bioestadistica\CapturaController@autosave')
            ->middleware('permission:bio.record.update')->name('captura.autosave');
        Route::put('/captura/{record}/periodo', 'Admin\Bioestadistica\CapturaController@updatePeriod')
            ->middleware('permission:bio.record.update')->name('captura.period.update');
        Route::post('/captura/{record}/enviar', 'Admin\Bioestadistica\CapturaController@submit')
            ->middleware('permission:bio.record.submit')->name('captura.submit');
        Route::post('/captura/{record}/aprobar', 'Admin\Bioestadistica\CapturaController@approve')
            ->middleware('permission:bio.record.approve')->name('captura.approve');
        Route::post('/captura/{record}/objetar', 'Admin\Bioestadistica\CapturaController@reject')
            ->middleware('permission:bio.record.approve')->name('captura.reject');
        Route::get('/captura-asignaciones', fn () => redirect()->route('bioestadistica.asignaciones.index'))
            ->middleware('permission:bio.assignment.manage')->name('captura.assignments');
        Route::put('/captura-asignaciones/{user}', 'Admin\Bioestadistica\CapturaController@updateAssignments')
            ->middleware('permission:bio.assignment.manage')->name('captura.assignments.update');

        Route::get('/asignaciones', 'Admin\Bioestadistica\AsignacionesCapturaController@index')
            ->middleware('permission:bio.assignment.manage')->name('asignaciones.index');
        Route::post('/asignaciones', 'Admin\Bioestadistica\AsignacionesCapturaController@store')
            ->middleware('permission:bio.assignment.manage')->name('asignaciones.store');
        Route::put('/asignaciones/{asignacion}', 'Admin\Bioestadistica\AsignacionesCapturaController@update')
            ->middleware('permission:bio.assignment.manage')->name('asignaciones.update');
        Route::delete('/asignaciones/{asignacion}', 'Admin\Bioestadistica\AsignacionesCapturaController@destroy')
            ->middleware('permission:bio.assignment.manage')->name('asignaciones.destroy');

        Route::get('/seguimiento', 'Admin\Bioestadistica\SeguimientoController@index')
            ->middleware('permission:bio.record.view')->name('seguimiento.index');
        Route::get('/seguimiento/exportar', 'Admin\Bioestadistica\SeguimientoController@export')
            ->middleware('permission:bio.report.export')->name('seguimiento.export');
        Route::get('/seguimiento/exportar/csv', 'Admin\Bioestadistica\SeguimientoController@exportCsv')
            ->middleware('permission:bio.report.export')->name('seguimiento.export.csv');
        Route::get('/seguimiento/exportar/xlsx', 'Admin\Bioestadistica\SeguimientoController@exportXlsx')
            ->middleware('permission:bio.report.export')->name('seguimiento.export.xlsx');
        Route::get('/seguimiento/exportar/pdf', 'Admin\Bioestadistica\SeguimientoController@exportPdf')
            ->middleware('permission:bio.report.export')->name('seguimiento.export.pdf');

        Route::get('/indicadores', 'Admin\Bioestadistica\IndicadorController@index')
            ->middleware('permission:bio.indicator.view')->name('indicadores.index');
        Route::post('/indicadores', 'Admin\Bioestadistica\IndicadorController@store')
            ->middleware('permission:bio.indicator.manage')->name('indicadores.store');
        Route::get('/indicadores/{indicador}', 'Admin\Bioestadistica\IndicadorController@show')
            ->middleware('permission:bio.indicator.view')->name('indicadores.show');
        Route::put('/indicadores/{indicador}', 'Admin\Bioestadistica\IndicadorController@update')
            ->middleware('permission:bio.indicator.manage')->name('indicadores.update');
        Route::delete('/indicadores/{indicador}', 'Admin\Bioestadistica\IndicadorController@destroy')
            ->middleware('permission:bio.indicator.manage')->name('indicadores.destroy');
        Route::post('/indicadores/{indicador}/validar-formula', 'Admin\Bioestadistica\IndicadorController@validateFormula')
            ->middleware('permission:bio.indicator.manage')->name('indicadores.formulas.validate');
        Route::post('/indicadores/{indicador}/formulas', 'Admin\Bioestadistica\IndicadorController@storeFormula')
            ->middleware('permission:bio.indicator.manage')->name('indicadores.formulas.store');
        Route::put('/indicadores/{indicador}/formulas/{formula}', 'Admin\Bioestadistica\IndicadorController@updateFormula')
            ->middleware('permission:bio.indicator.manage')->name('indicadores.formulas.update');
        Route::delete('/indicadores/{indicador}/formulas/{formula}', 'Admin\Bioestadistica\IndicadorController@destroyFormula')
            ->middleware('permission:bio.indicator.manage')->name('indicadores.formulas.destroy');
        Route::post('/indicadores/{indicador}/evaluar', 'Admin\Bioestadistica\IndicadorController@evaluate')
            ->middleware('permission:bio.indicator.evaluate')->name('indicadores.evaluate');

        Route::get('/reportes', 'Admin\Bioestadistica\ReporteController@index')
            ->middleware('permission:bio.report.view')->name('reportes.index');
        Route::get('/reportes/crear', 'Admin\Bioestadistica\ReporteController@create')
            ->middleware('permission:bio.report.manage')->name('reportes.create');
        Route::post('/reportes', 'Admin\Bioestadistica\ReporteController@store')
            ->middleware('permission:bio.report.manage')->name('reportes.store');
        Route::get('/reportes/{reporte}/editar', 'Admin\Bioestadistica\ReporteController@edit')
            ->middleware('permission:bio.report.manage')->name('reportes.edit');
        Route::put('/reportes/{reporte}', 'Admin\Bioestadistica\ReporteController@update')
            ->middleware('permission:bio.report.manage')->name('reportes.update');
        Route::delete('/reportes/{reporte}', 'Admin\Bioestadistica\ReporteController@destroy')
            ->middleware('permission:bio.report.manage')->name('reportes.destroy');
        Route::get('/reportes/{reporte}', 'Admin\Bioestadistica\ReporteController@run')
            ->middleware('permission:bio.report.view')->name('reportes.run');
        Route::get('/reportes/{reporte}/exportar/csv', 'Admin\Bioestadistica\ReporteController@exportCsv')
            ->middleware('permission:bio.report.export')->name('reportes.export.csv');
        Route::get('/reportes/{reporte}/exportar/xlsx', 'Admin\Bioestadistica\ReporteController@exportXlsx')
            ->middleware('permission:bio.report.export')->name('reportes.export.xlsx');
        Route::get('/reportes/{reporte}/exportar/pdf', 'Admin\Bioestadistica\ReporteController@exportPdf')
            ->middleware('permission:bio.report.export')->name('reportes.export.pdf');

        Route::get('/dashboards', 'Admin\Bioestadistica\DashboardController@index')
            ->middleware('permission:bio.dashboard.view')->name('dashboards.index');
        Route::get('/dashboards/crear', 'Admin\Bioestadistica\DashboardController@create')
            ->middleware('permission:bio.dashboard.manage')->name('dashboards.create');
        Route::post('/dashboards', 'Admin\Bioestadistica\DashboardController@store')
            ->middleware('permission:bio.dashboard.manage')->name('dashboards.store');
        Route::get('/dashboards/{dashboard}/editar', 'Admin\Bioestadistica\DashboardController@edit')
            ->middleware('permission:bio.dashboard.manage|bio.dashboard.personalize')->name('dashboards.edit');
        Route::get('/dashboards/{dashboard}', 'Admin\Bioestadistica\DashboardController@show')
            ->middleware('permission:bio.dashboard.view')->name('dashboards.show');
        Route::put('/dashboards/{dashboard}', 'Admin\Bioestadistica\DashboardController@update')
            ->middleware('permission:bio.dashboard.manage|bio.dashboard.personalize')->name('dashboards.update');
        Route::delete('/dashboards/{dashboard}', 'Admin\Bioestadistica\DashboardController@destroy')
            ->middleware('permission:bio.dashboard.manage|bio.dashboard.personalize')->name('dashboards.destroy');
        Route::post('/dashboards/{dashboard}/clonar', 'Admin\Bioestadistica\DashboardController@cloneTemplate')
            ->middleware('permission:bio.dashboard.personalize')->name('dashboards.clone');
        Route::post('/dashboards/{dashboard}/layout', 'Admin\Bioestadistica\DashboardController@layout')
            ->middleware('permission:bio.dashboard.manage|bio.dashboard.personalize')->name('dashboards.layout');
        Route::post('/dashboards/{dashboard}/widgets', 'Admin\Bioestadistica\DashboardController@storeWidget')
            ->middleware('permission:bio.dashboard.manage|bio.dashboard.personalize')->name('dashboards.widgets.store');
        Route::put('/dashboards/{dashboard}/widgets/{widget}', 'Admin\Bioestadistica\DashboardController@updateWidget')
            ->middleware('permission:bio.dashboard.manage|bio.dashboard.personalize')->name('dashboards.widgets.update');
        Route::delete('/dashboards/{dashboard}/widgets/{widget}', 'Admin\Bioestadistica\DashboardController@destroyWidget')
            ->middleware('permission:bio.dashboard.manage|bio.dashboard.personalize')->name('dashboards.widgets.destroy');
        Route::get('/dashboards/{dashboard}/widgets/{widget}/datos', 'Admin\Bioestadistica\DashboardController@widgetData')
            ->middleware('permission:bio.dashboard.view')->name('dashboards.widgets.data');

        Route::get('/importaciones', 'Admin\Bioestadistica\ImportacionController@index')
            ->middleware('permission:bio.import.view')->name('importaciones.index');
        Route::post('/importaciones', 'Admin\Bioestadistica\ImportacionController@store')
            ->middleware('permission:bio.import.execute')->name('importaciones.store');
        Route::get('/importaciones/{importacion}', 'Admin\Bioestadistica\ImportacionController@show')
            ->middleware('permission:bio.import.view')->name('importaciones.show');
        Route::put('/importaciones/{importacion}/mapeo', 'Admin\Bioestadistica\ImportacionController@map')
            ->middleware('permission:bio.import.execute')->name('importaciones.map');
        Route::post('/importaciones/{importacion}/confirmar', 'Admin\Bioestadistica\ImportacionController@confirm')
            ->middleware('permission:bio.import.execute')->name('importaciones.confirm');
        Route::post('/importaciones/{importacion}/datos', 'Admin\Bioestadistica\ImportacionController@importData')
            ->middleware('permission:bio.import.execute')->name('importaciones.data');
        Route::delete('/importaciones/{importacion}', 'Admin\Bioestadistica\ImportacionController@destroy')
            ->middleware('permission:bio.import.execute')->name('importaciones.destroy');

        Route::get('/hospitalizacion', 'Admin\Bioestadistica\HospitalizacionController@index')
            ->middleware('permission:bio.hosp.view')->name('hospitalizacion.index');
        Route::get('/hospitalizacion/panel', 'Admin\Bioestadistica\HospitalizacionController@panel')
            ->middleware('permission:bio.hosp.view')->name('hospitalizacion.panel');
        Route::get('/hospitalizacion/exportar', 'Admin\Bioestadistica\HospitalizacionController@export')
            ->middleware('permission:bio.hosp.export')->name('hospitalizacion.export');
        Route::get('/hospitalizacion/importar', 'Admin\Bioestadistica\HospitalizacionController@importForm')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.import');
        Route::post('/hospitalizacion/importar', 'Admin\Bioestadistica\HospitalizacionController@import')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.import.store');
        Route::post('/hospitalizacion/consolidar', 'Admin\Bioestadistica\HospitalizacionController@consolidate')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.consolidate');
        Route::get('/hospitalizacion/planilla', 'Admin\Bioestadistica\HospitalizacionController@spreadsheet')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.spreadsheet');
        Route::post('/hospitalizacion/planilla', 'Admin\Bioestadistica\HospitalizacionController@saveSpreadsheet')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.spreadsheet.save');
        Route::post('/hospitalizacion/planilla/autosave', 'Admin\Bioestadistica\HospitalizacionController@autosaveSpreadsheet')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.spreadsheet.autosave');
        Route::get('/hospitalizacion/crear', 'Admin\Bioestadistica\HospitalizacionController@create')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.create');
        Route::post('/hospitalizacion', 'Admin\Bioestadistica\HospitalizacionController@store')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.store');
        Route::get('/hospitalizacion/{episodio}/editar', 'Admin\Bioestadistica\HospitalizacionController@edit')
            ->middleware('permission:bio.hosp.view')->name('hospitalizacion.edit');
        Route::put('/hospitalizacion/{episodio}', 'Admin\Bioestadistica\HospitalizacionController@update')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.update');
        Route::delete('/hospitalizacion/{episodio}', 'Admin\Bioestadistica\HospitalizacionController@destroy')
            ->middleware('permission:bio.hosp.manage')->name('hospitalizacion.destroy');
    });

    // Rutas de Proyectos 
    Route::get('proyectos-dashboard', 'Admin\Proyectos\ProyectosDashboardController@index')->name('proyectos-dashboard');

    // ── Proyectos Institucionales (SCPI) ──────────────────────────────────────────────────────
    Route::get('proyectos-institucionales',                    'Admin\Proyectos\ProyectoInstitucionalController@index')->name('proyectos-institucionales.all');
    Route::get('pei-profiles/{profileId}/proyectos',          'Admin\Proyectos\ProyectoInstitucionalController@index')->name('proyectos-institucionales.index');
    Route::get('pei-profiles/{profileId}/proyectos/create',   'Admin\Proyectos\ProyectoInstitucionalController@createForPerfil')->name('proyectos-institucionales.create-for-perfil');
    Route::post('pei-profiles/{profileId}/proyectos',         'Admin\Proyectos\ProyectoInstitucionalController@store')->name('proyectos-institucionales.store');
    Route::get('pei-profiles/{profileId}/proyectos/acciones', 'Admin\Proyectos\ProyectoInstitucionalController@getAccionesDePerfil')->name('proyectos-institucionales.acciones-de-perfil');
    Route::get('proyectos-institucionales-pei-acciones',    'Admin\Proyectos\ProyectoInstitucionalController@getPeiAcciones')->name('proyectos-institucionales.pei-acciones');
    Route::get('proyectos-institucionales/{id}',              'Admin\Proyectos\ProyectoInstitucionalController@show')->name('proyectos-institucionales.show');
    Route::get('proyectos-institucionales/{id}/edit',         'Admin\Proyectos\ProyectoInstitucionalController@edit')->name('proyectos-institucionales.edit');
    Route::put('proyectos-institucionales/{id}',              'Admin\Proyectos\ProyectoInstitucionalController@update')->name('proyectos-institucionales.update');
    Route::post('proyectos-institucionales/{id}/estado',      'Admin\Proyectos\ProyectoInstitucionalController@cambiarEstado')->name('proyectos-institucionales.estado');
    Route::post('proyectos-institucionales/{id}/checklist',   'Admin\Proyectos\ProyectoInstitucionalController@updateChecklist')->name('proyectos-institucionales.checklist');

    // ── Módulo de Solicitudes de Ajuste de Estructura Organizacional ───────────
    Route::get('admin/planificacion/estructura-solicitudes',
        [\App\Http\Controllers\Admin\Planificacion\Estructura\SolicitudAjusteEstructuraController::class, 'index'])
        ->name('admin.estructura-solicitudes.index');
    Route::get('admin/planificacion/estructura-solicitudes/{id}',
        [\App\Http\Controllers\Admin\Planificacion\Estructura\SolicitudAjusteEstructuraController::class, 'show'])
        ->name('admin.estructura-solicitudes.show');
    Route::post('admin/planificacion/estructura-solicitudes/{id}/estado',
        [\App\Http\Controllers\Admin\Planificacion\Estructura\SolicitudAjusteEstructuraController::class, 'cambiarEstado'])
        ->name('admin.estructura-solicitudes.estado');

    Route::group(['prefix' => 'admin/globales', 'as' => 'globales.'], function () {
        //Dashboard
        Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'Admin\Globales\GlobalesController@dashboard']);

        // ── Users: Administradores y Coordinadores ───────────
        Route::middleware(['role:Administrador|Super Admin|Coordinador de Planificación|Coordinación de Planificación|Analista de Planificación|Analista PEI|Coordinador de Proyectos|Coordinación de Proyectos'])->group(function () {
            Route::resource('users', 'Admin\UserController');
            Route::resource('activities', 'Admin\Globales\ActivityController', ['except' => ['show']]);
        });

        // ── Globales administrativas estrictas: solo Administrador ───────────────────────
        Route::middleware(['role:Administrador|Super Admin'])->group(function () {
            Route::get('configuracion-sistema',  'Admin\HomeConfigController@editGlobalSettings')->name('configuracion-sistema');
            Route::post('configuracion-sistema', 'Admin\HomeConfigController@updateGlobalSettings')->name('configuracion-sistema.update');

            Route::resource('localities', 'Admin\Globales\LocalityController');
            Route::resource('patrimonies', 'Admin\Globales\PatrimonyController');
            Route::resource('patrimony-profiles', 'Admin\Globales\PatrimonyProfileController');
            Route::resource('permisos', 'Admin\PermissionController');
            Route::resource('roles', 'Admin\RoleController');
            Route::resource('formularios', 'Admin\Globales\Formulario\FormularioController');
        });

        // ── Helper endpoints abiertos a usuarios autenticados para asignación ──
        Route::get('get-root-groups', 'Admin\Globales\GroupController@getRootGroups')->name('get-root-groups');
        Route::get('get-groups/{idRoot}', 'Admin\Globales\GroupController@getGroupsFromRoot')->name('get-groups');
        Route::get('get-group-parent/{idSelection}', 'Admin\Globales\GroupController@dataGroupParent')->name('get-group-parent');
        Route::get('get-group/{idSelection}', 'Admin\Globales\GroupController@dataGroup')->name('get-group');
        Route::get('get-users', 'Admin\UserController@getUsers')->name('get-users');
        Route::get('get-user/{id}', 'Admin\UserController@getUser')->name('get-user');
        Route::get('get-pei-profiles', 'Admin\Globales\ActivityController@getPeiProfiles')->name('get-pei-profiles');

        // ── Show: Administrador + Gestor + Colaborador ───────────────────────
        Route::get('activities/{activity}', 'Admin\Globales\ActivityController@show')->name('activities.show');

        // ── Creación de tareas y Notificaciones por correo: Administrador, Gestores y Analistas ──
        Route::middleware(['role:Administrador|Gestor de Actividades|Coordinador de Planificación|Analista de Planificación|Analista PEI|Líder MECIP|Analista|Colaborador de Actividades|Coordinador de Proyectos|Coordinación de Proyectos'])->group(function () {
            Route::post('activities/{activityId}/tareas', 'Admin\Globales\ActivityController@storeTarea')->name('activities.tareas.store');
            Route::post('activities/{activityId}/notificar-todos', 'Admin\Globales\ActivityController@notificarTodos')->name('activities.notificar-todos');
            Route::post('activities/tareas/{taskId}/notificar', 'Admin\Globales\ActivityController@notificarTarea')->name('activities.tareas.notificar');
        });

        // ── Evidencias y Eliminación de Tareas ──
        Route::middleware(['role:Administrador|Gestor de Actividades|Analista de Planificación|Analista PEI|Analista|Coordinador de Proyectos|Coordinación de Proyectos'])->group(function () {
            Route::delete('activities/tareas/{taskId}', 'Admin\Globales\ActivityController@destroyTarea')->name('activities.tareas.destroy');
            Route::post('activities/tareas/{taskId}/evidencias', 'Admin\Globales\ActivityController@storeEvidencia')->name('activities.tareas.evidencias.store');
            Route::delete('activities/tareas/evidencias/{evidenceId}', 'Admin\Globales\ActivityController@destroyEvidencia')->name('activities.tareas.evidencias.destroy');
        });

        Route::middleware(['role:Administrador|Gestor de Actividades|Colaborador de Actividades|Analista de Planificación|Analista PEI|Analista|Coordinador de Proyectos|Coordinación de Proyectos'])->group(function () {
            Route::patch('activities/tareas/{taskId}/reasignar', 'Admin\Globales\ActivityController@reasignarTarea')->name('activities.tareas.reasignar');
        });

        // ── Mover tarea: Gestor + Colaborador (el controlador valida ownership) ──
        Route::patch('activities/tareas/{taskId}/status', 'Admin\Globales\ActivityController@updateStatus')->name('activities.tareas.status');

        // ── Comentarios: todos los autenticados ───────────────────────────────
        Route::get('activities/tareas/{taskId}/detalle', 'Admin\Globales\ActivityController@detalleTarea')->name('activities.tareas.detalle');
        Route::get('activities/{activityId}/reuniones', 'Admin\Globales\ActivityController@reuniones')->name('activities.reuniones');
        Route::get('activities/{activityId}/documentos', 'Admin\Globales\ActivityController@documentos')->name('activities.documentos');
        Route::get('activities/{activityId}/seguimientos', 'Admin\Globales\ActivityController@seguimientos')->name('activities.seguimientos');
        Route::get('activities/tareas/{taskId}/comentarios', 'Admin\Globales\ActivityController@getComentarios')->name('activities.tareas.comentarios.index');
        Route::post('activities/tareas/{taskId}/comentarios', 'Admin\Globales\ActivityController@storeComentario')->name('activities.tareas.comentarios.store');
        Route::delete('activities/tareas/comentarios/{commentId}', 'Admin\Globales\ActivityController@destroyComentario')->name('activities.tareas.comentarios.destroy');

        // ── Galería de Fotos de Reunión ────────────────────────────────────────
        Route::get('activities/reuniones/{taskId}/fotos', 'Admin\Globales\ActivityController@getReunionPhotos')->name('activities.reuniones.fotos.index');
        Route::post('activities/reuniones/{taskId}/fotos', 'Admin\Globales\ActivityController@storeReunionPhoto')->name('activities.reuniones.fotos.store');
        Route::delete('activities/reuniones/fotos/{photoId}', 'Admin\Globales\ActivityController@destroyReunionPhoto')->name('activities.reuniones.fotos.destroy');


        // ── Actas de Reunión MECIP ──────────────────────────────────────────
        Route::get('activities/tareas/{taskId}/acta-mecip', 'Admin\Globales\ActaMecipController@getActa')->name('activities.acta-mecip.get');
        Route::post('activities/tareas/{taskId}/acta-mecip', 'Admin\Globales\ActaMecipController@storeOrUpdate')->name('activities.acta-mecip.store');
        Route::post('activities/tareas/{taskId}/acta-mecip/finalizar', 'Admin\Globales\ActaMecipController@finalizar')->name('activities.acta-mecip.finalizar');
        Route::post('activities/tareas/{taskId}/acta-mecip/participantes', 'Admin\Globales\ActaMecipController@addParticipante')->name('activities.acta-mecip.participantes.add');
        Route::delete('activities/tareas/{taskId}/acta-mecip/participantes/{participanteId}', 'Admin\Globales\ActaMecipController@deleteParticipante')->name('activities.acta-mecip.participantes.delete');
        Route::get('activities/tareas/{taskId}/acta-mecip/imprimir', 'Admin\Globales\ActaMecipController@imprimir')->name('activities.acta-mecip.imprimir');
        Route::get('activities/tareas/{taskId}/acta-mecip/pdf', 'Admin\Globales\ActaMecipController@descargarPdf')->name('activities.acta-mecip.pdf');


        //Localities
        Route::get('patrimony-profiles/{idPatrimonyProfile}/detail', 'Admin\Globales\PatrimonyProfileController@detailPatrimonyProfile')->name('patrimonies.detail-profile');

        // Get data from Select2
        Route::get('/locality/{state}/cities', 'Admin\Globales\LocalityController@getCities');
        Route::get('/locality/{city}/localities', 'Admin\Globales\LocalityController@getLocalities');

        //Roles and permissions
        Route::get('roles-guide', 'Admin\RoleController@guide')->name('roles.guide');
        Route::get('roles/{id}/edit-ajax', 'Admin\RoleController@editAjax')->name('roles.edit-ajax');
        Route::get('roles/{id}/show-ajax', 'Admin\RoleController@showAjax')->name('roles.show-ajax');
        Route::get('get-roles', 'Admin\RoleController@getRoles')->name('get-roles');
        Route::get('get-role/{userId}', 'Admin\RoleController@getRole')->name('get-role');

        Route::get('formularios-dependecies', 'Admin\Globales\Formulario\FormularioController@getDependencies')->name('formularios.get-dependencies');
        Route::post('formulario-item/{idForm}/selected', 'Admin\Globales\Formulario\FormularioController@postSelectedItem')->name('form.item.selected');
        Route::post('formulario/{idForm}/response', 'Admin\Globales\Formulario\FormularioController@postResponse')->name('form-response-ok');

        //Organizational
        Route::resource('organigramas', 'Admin\Globales\OrganigramaController');
        Route::get('organigramas-crear-subdependencia/{idDependencia}', 'Admin\Globales\OrganigramaController@crearSubDependencia')->name('organigramas-crear-subdependencia');
        Route::get('organigramas-editar-subdependencia/{idDependencia}', 'Admin\Globales\OrganigramaController@editarSubDependencia')->name('organigramas-editar-subdependencia');
        Route::get('organigrama-gestionar/{id}', 'Admin\Globales\OrganigramaController@verOrganigrama')->name('organigrama-gestionar');
        Route::get('get-dependencies', 'Admin\Globales\OrganigramaController@getDependencies')->name('get-dependencies');
        Route::get('get-dependencies/{idRoot}', 'Admin\Globales\OrganigramaController@getDependenciesFromRoot')->name('has-dependencies');
        Route::get('get-dependency/{idSelection}', 'Admin\Globales\OrganigramaController@getDependency')->name('get-dependency');
        Route::get('get-root-of-dependency/{idSelection}', 'Admin\Globales\OrganigramaController@getRootOfDependency')->name('get-root-of-dependency');
        Route::post('organigramas/{id}/mover', 'Admin\Globales\OrganigramaController@mover')->name('organigramas.mover');
        Route::post('admin/globales/organigramas/{id}/mover', 'Admin\Globales\OrganigramaController@mover');
        Route::get('get-dependencies-root', 'Admin\Globales\OrganigramaController@getDependenciesRoot')->name('get-dependencies-root');
        Route::get('usuarios-buscar', 'Admin\Globales\OrganigramaController@buscarUsuarios')->name('usuarios.buscar');

        //Variables de Encuesta
        Route::resource('variables', 'Admin\Globales\Formulario\VariableController');
        Route::get('variables-crear-item/{idVariable}', 'Admin\Globales\Formulario\VariableController@crearItem')->name('variables-crear-item');
        Route::get('variables-editar-item/{idVariable}', 'Admin\Globales\Formulario\VariableController@editarItem')->name('variables-editar-item');
        Route::get('variable-gestionar/{id}', 'Admin\Globales\Formulario\VariableController@verVariable')->name('gestionar-variable');

        // Servicios
        Route::resource('servicios', 'Admin\Globales\ServicioController');

        // Catgories
        Route::resource('categories', 'Backend\CategoryController');

        //Groups
        Route::resource('groups', 'Admin\Globales\GroupController');
        Route::post('groups/{id}/otorgar-puntos', 'Admin\Globales\GroupController@otorgarPuntosGrupo')->name('groups.otorgar-puntos');
        Route::post('groups/{id}/otorgar-puntos-cierre-semana', 'Admin\Globales\GroupController@otorgarPuntosCierreSemana')->name('groups.otorgar-puntos-cierre-semana');
        Route::post('groups/{id}/crear-asignar-funcionario', 'Admin\Globales\GroupController@crearYAsignarFuncionario')->name('groups.crear-asignar-funcionario');
        Route::post('users/{id}/otorgar-puntos-manuales', 'Admin\Globales\GroupController@otorgarPuntosUsuario')->name('users.otorgar-puntos-manuales');
        Route::get('get-root-groups', 'Admin\Globales\GroupController@getRootGroups')->name('get-root-groups');
        Route::get('get-groups/{idRoot}', 'Admin\Globales\GroupController@getGroupsFromRoot')->name('get-groups');
        Route::get('get-group-parent/{idSelection}', 'Admin\Globales\GroupController@dataGroupParent')->name('get-group-parent');
        Route::get('get-group/{idSelection}', 'Admin\Globales\GroupController@dataGroup')->name('get-group');
        Route::get('get-users', 'Admin\UserController@getUsers')->name('get-users');
        Route::get('get-user/{id}', 'Admin\UserController@getUser')->name('get-user');
        Route::get('get-pei-profiles', 'Admin\Globales\ActivityController@getPeiProfiles')->name('get-pei-profiles');

        // ── Reflexión Diaria y Código de Ética ────────────────────────────────
        Route::get('reflexion-diaria', 'Admin\ReflexionController@obtenerReflexionDiaria')->name('reflexion.diaria');
        Route::post('reflexion-ia', 'Admin\ReflexionController@generarConGroq')->name('reflexion.ia');
    });

    // ── Impersonación de Usuarios / Vista Previa por Rol ──────────────────
    Route::get('impersonate/take/{id}', 'Admin\ImpersonateController@take')->name('impersonate.take');
    Route::get('impersonate/leave', 'Admin\ImpersonateController@leave')->name('impersonate.leave');
    Route::get('impersonate/list-users', 'Admin\ImpersonateController@listUsers')->name('impersonate.list-users');

    //Rutas del Modulo Surveys
    Route::resource('surveys', 'Admin\Globales\Survey\SurveyController');
    Route::get('/surveys/{id}/details', 'Admin\Globales\Survey\SurveyController@detailAnswer')->name('surveys.show.details');
    Route::get('/surveys/{id}/details-json', 'Admin\Globales\Survey\SurveyController@showDetails')->name('surveys.show.details-json');
    Route::get('/surveys/{id}/questions', 'Admin\Globales\Survey\SurveyController@showQuestions')->name('surveys.show.questions');
    Route::get('/surveys/{id}/answers', 'Admin\Globales\Survey\SurveyController@showQuestionsTemplate')->name('surveys.answers');
    Route::post('/surveys/{surveyId}/check-answer', 'Admin\Globales\Survey\SurveyController@checkAnswer');
    Route::get('/surveys/{surveyId}/details-answers', 'Admin\Globales\Survey\SurveyController@detailAnswer')->name('surveys.answers.details');
    Route::get('/surveys/{surveyId}/scores', 'Admin\Globales\Survey\SurveyController@getTopScores')->name('surveys.scores');
    Route::post('/save-answer', 'Admin\Globales\Survey\AnswerController@saveAnswer');
    Route::post('/save-score', 'Admin\Globales\Survey\AnswerController@saveScore');
    Route::get('/surveys/{surveyId}/has-responded', 'Admin\Globales\Survey\AnswerController@hasUserResponded');

    Route::resource('questions', 'Admin\Globales\Survey\QuestionController');

    Route::resource('anwers', 'Admin\Globales\Survey\AnswerController');
    // Route::get('/generar-encuesta/{tema}/{numPreguntas?}', [OpenAIController::class, 'generarEncuesta']);
    Route::get('/questions/{questionId}', [QuestionController::class, 'generarEncuesta']);

    //Rutas del Modulo FODA
    Route::resource('foda-models', 'Admin\Planificacion\Foda\FodaModeloController');
    Route::get('foda-models/{categoryId}/getAspects', 'Admin\Planificacion\Foda\FodaModeloController@getAspects')->name('foda-models-getAspects');
    Route::get('foda-models/{categoryId}/showAspects', 'Admin\Planificacion\Foda\FodaModeloController@showAspects');
    Route::resource('foda-categorias', 'Admin\Planificacion\Foda\FodaCategoriaController');

    Route::get('get-foda-category/{idSelection}', 'Admin\Planificacion\Foda\FodaCategoriaController@dataCategory')->name('get-foda-category');
    Route::get('get-models', 'Admin\Planificacion\Foda\FodaModeloController@getModels')->name('get-models');
    Route::get('get-foda-categories/{modelId}', 'Admin\Planificacion\Foda\FodaModeloController@getCategories')->name('get-foda-categories');
    Route::get('get-model', 'Admin\Planificacion\Foda\FodaModeloController@dataModel')->name('get-model');
    Route::get('foda-modelo-categorias/{idModelo}', 'Admin\Planificacion\Foda\FodaCategoriaController@listadoCategorias')->name('foda-modelo-categorias');
    Route::get('foda-modelo-categoria-crear/{idModelo}', 'Admin\Planificacion\Foda\FodaCategoriaController@crearCategoria')->name('foda-modelo-categoria-crear');
    Route::get('perfil-categorias/{id}', 'Admin\Planificacion\Foda\FodaAnalisisController@listarCategorias')->name('perfil-categorias');
    Route::resource('foda-aspectos', 'Admin\Planificacion\Foda\FodaAspectoController');
    Route::get('foda-modelo-categoria-aspectos/{idModelo}/{idCategoria}', 'Admin\Planificacion\Foda\FodaModeloController@listadoAspectos')->name('foda-modelo-categoria-aspectos');
    Route::get('foda-modelo-categoria-aspectos-crear/{idCategoria}', 'Admin\Planificacion\Foda\FodaAspectoController@crearAspecto')->name('foda-modelo-categoria-aspectos-crear');
    Route::resource('foda-perfiles', 'Admin\Planificacion\Foda\FodaPerfilController');
    Route::get('get-foda-perfiles', 'Admin\Planificacion\Foda\FodaPerfilController@getPerfilesSelect2')->name('get-foda-perfiles');
    Route::resource('foda-analisis', 'Admin\Planificacion\Foda\FodaAnalisisController');
    Route::get('foda-analisis/{idPerfil}/matriz', 'Admin\Planificacion\Foda\FodaAnalisisController@matriz');
    Route::get('foda-ambiente-interno/{idCategoria}/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@categoriasAmbienteInterno')->name('foda-ambiente-interno');
    Route::get('foda-ambiente-externo/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@categoriasAmbienteExterno')->name('foda-ambiente-externo');
    Route::get('foda-aspectos-categoria/{idCategoria}/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@aspectosCategoria')->name('foda-aspectos-categoria');
    Route::get('foda-aspectos-categoria/{idCategoria}/{idPerfil}/edit', 'Admin\Planificacion\Foda\FodaAnalisisController@aspectosCategoriaEdit')->name('foda-aspectos-categoria-edit');
    Route::get('foda-analisis-asignar-aspectos/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@asignarAspectos')->name('ffoda-analisis-asignar-aspectos');
    Route::get('foda-listado-categorias-aspectos/{idCategoria}/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@listadoCategoriaAspectos')->name('foda-listado-categorias-aspectos');
    Route::get('foda-analisis-ambientes/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@seleccionarAmbiente')->name('foda-analisis-ambientes');
    Route::get('foda-analisis-ambiente-interno/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@analisisCategoriasAmbienteInterno')->name('foda-analisis-ambiente-interno');
    Route::get('foda-analisis-ambiente-externo/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@analisisCategoriasAmbienteExterno')->name('foda-analisis-ambiente-externo');
    Route::get('foda-analisis-aspectos/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@analisisAspectos')->name('foda-analisis-aspectos');
    Route::get('foda-analisis-listado-categoria-aspectos/{idCategoria}/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@listadoCategoriaAspectos')->name('foda-analisis-listado-categoria-aspectos');
    Route::get('foda-analisis-matriz/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@matriz')->name('foda-analisis-matriz');
    Route::get('foda-categoria-aspectos/{idCategoria}', 'Admin\Planificacion\Foda\FodaCategoriaController@listaAspectosCategoria')->name('foda-categoria-aspectos');
    Route::get('descargar-matriz-foda/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@matrizPdf')->name('matriz-foda.pdf');
    Route::get('foda-cruce-ambientes/{idPerfil}', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@index')->name('foda-cruce-ambientes');
    Route::get('foda-cruce-pdf/{idPerfil}', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@descargarCrucePdf')->name('foda-cruce-pdf');
    Route::get('foda-cruce-ambientes-fo/{idPerfil}', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@FO')->name('foda-cruce-ambientes-fo');
    Route::get('foda-cruce-ambientes-do/{idPerfil}', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@DO')->name('foda-cruce-ambientes-do');
    Route::get('foda-cruce-ambientes-fa/{idPerfil}', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@FA')->name('foda-cruce-ambientes-fa');
    Route::get('foda-cruce-ambientes-da/{idPerfil}', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@DA')->name('foda-cruce-ambientes-da');
    Route::resource('foda-cruce-ambientes', 'Admin\Planificacion\Foda\FodaCruceAmbienteController');
    Route::post('foda-cruce-ambientes-ia', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@generarConIA')->name('foda-cruce-ambientes.ia');
    Route::post('foda-analisis-mecip-ia', 'Admin\Planificacion\Foda\FodaAnalisisController@generarMecipIA')->name('foda-analisis.mecip-ia');
    Route::get('foda-aspectos-elegir-modelo', 'Admin\Planificacion\Foda\FodaAspectoController@elegirModelo')->name('foda-aspectos-elegir-modelo');
    Route::get('/foda-perfiles-modelo/{id}/categorias', 'Admin\Planificacion\Foda\FodaPerfilController@getCategorias');
    Route::get('foda-perfiles/{idPerfil}/add-group', 'Admin\Planificacion\Foda\FodaPerfilController@addGroup')->name('foda.add.group');
    Route::get('foda-profiles/{idProfile}/details', 'Admin\Planificacion\Foda\FodaPerfilController@showDetails')->name('foda.show.details');
    Route::get('foda-perfiles/{idProfile}/get-tree', 'Admin\Planificacion\Foda\FodaPerfilController@getTree')->name('foda.get.tree');
    Route::get('foda-list-groups', 'Admin\Planificacion\Foda\FodaAnalisisController@getListGroup')->name('foda-list-groups');
    Route::get('foda-matriz-groups/{idGroup}', 'Admin\Planificacion\Foda\FodaAnalisisController@getMatrizForGroup')->name('foda-matriz-groups');
    Route::get('foda-matriz-groups/{idGroup}/crossing', 'Admin\Planificacion\Foda\FodaAnalisisController@getMatrizForCrossing')->name('foda-matriz-groups-crossing');
    Route::get('foda-matriz-crossing/{idProfile}/fo', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@foGroup')->name('foda-matriz-crossing-fo');
    Route::get('foda-matriz-crossing/{idProfile}/do', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@doGroup')->name('foda-matriz-crossing-do');
    Route::get('foda-matriz-crossing/{idProfile}/fa', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@faGroup')->name('foda-matriz-crossing-fa');
    Route::get('foda-matriz-crossing/{idProfile}/da', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@daGroup')->name('foda-matriz-crossing-da');
    Route::post('foda-profile/{idRootGroup}/', 'Admin\Planificacion\Foda\FodaPerfilController@createGroupRootProfile')->name('foda-profile-create');
    Route::get('get-crossings', 'Admin\Planificacion\Foda\FodaCruceAmbienteController@getCrossings')->name('get-crossings');
    Route::post('foda-analisis/{id}/calcular-iea', 'Admin\Planificacion\Foda\FodaAnalisisController@calcularIEA')->name('foda-analisis.calcular-iea');


    // ── Módulo de Monitoreo / Reportes de Avance ─────────────────────────────
    Route::get('pei-profiles/{profileId}/mis-acciones',              'Admin\Planificacion\PeiReporteController@misAcciones')->name('pei.reportes.mis-acciones');
    Route::get('pei-monitoreo/dashboard',                             'Admin\Planificacion\PeiReporteController@monitoreDashboard')->name('pei.monitoreo.dashboard');
    Route::post('pei-profiles/{profileId}/public-token',              'Admin\Planificacion\PublicPeiController@generateToken')->name('pei.public.token.generate');
    Route::delete('pei-profiles/{profileId}/public-token',            'Admin\Planificacion\PublicPeiController@revokeToken')->name('pei.public.token.revoke');
    Route::post('pei-profiles/{profileId}/notificar-todos',          'Admin\Planificacion\PeiReporteController@notificarTodos')->name('pei.reportes.notificar-todos');
    Route::post('pei-profiles/{profileId}/acciones/{accionId}/notificar', 'Admin\Planificacion\PeiReporteController@notificarAccion')->name('pei.reportes.notificar-accion');
    Route::get('pei-profiles/{accionId}/reportes',                   'Admin\Planificacion\PeiReporteController@index')->name('pei.reportes.index');
    Route::post('pei-profiles/{accionId}/reportes',                  'Admin\Planificacion\PeiReporteController@store')->name('pei.reportes.store');
    Route::put('pei-profiles/{accionId}/reportes/{id}',              'Admin\Planificacion\PeiReporteController@update')->name('pei.reportes.update');
    Route::delete('pei-profiles/{accionId}/reportes/{id}',           'Admin\Planificacion\PeiReporteController@destroy')->name('pei.reportes.destroy');

    // ── Marco Estratégico Específico (MEE) ───────────────────────────────────
    Route::get('pei-profiles/{profileId}/mee',                      'Admin\Planificacion\MeeController@modulo')->name('pei.mee.modulo');
    Route::get('pei-profiles/{profileId}/mee/marcos',               'Admin\Planificacion\MeeController@indexMarcos')->name('pei.mee.marcos.index');
    Route::post('pei-profiles/{profileId}/mee/marcos',              'Admin\Planificacion\MeeController@storeMarco')->name('pei.mee.marcos.store');
    Route::put('pei-profiles/{profileId}/mee/marcos/{id}',          'Admin\Planificacion\MeeController@updateMarco')->name('pei.mee.marcos.update');
    Route::delete('pei-profiles/{profileId}/mee/marcos/{id}',       'Admin\Planificacion\MeeController@destroyMarco')->name('pei.mee.marcos.destroy');
    Route::get('pei-profiles/{profileId}/mee/ofertas',              'Admin\Planificacion\MeeController@indexOfertas')->name('pei.mee.ofertas.index');
    Route::post('pei-profiles/{profileId}/mee/ofertas',             'Admin\Planificacion\MeeController@storeOferta')->name('pei.mee.ofertas.store');
    Route::put('pei-profiles/{profileId}/mee/ofertas/{id}',         'Admin\Planificacion\MeeController@updateOferta')->name('pei.mee.ofertas.update');
    Route::delete('pei-profiles/{profileId}/mee/ofertas/{id}',      'Admin\Planificacion\MeeController@destroyOferta')->name('pei.mee.ofertas.destroy');

    // ── Indicadores (Ficha Técnica) ───────────────────────────────────────────
    Route::get('pei-profiles/{profileId}/indicadores/buscar',           'Admin\Planificacion\IndicadorController@buscar')->name('pei.indicadores.buscar');
    Route::get('pei-profiles/{profileId}/indicadores/siguiente-codigo', 'Admin\Planificacion\IndicadorController@siguienteCodigo')->name('pei.indicadores.siguienteCodigo');
    Route::get('pei-profiles/{profileId}/indicadores/modulo',           'Admin\Planificacion\IndicadorController@modulo')->name('pei.indicadores.modulo');
    Route::get('pei-profiles/{profileId}/indicadores/{id}/detalle',     'Admin\Planificacion\IndicadorController@detalle')->name('pei.indicadores.detalle');
    Route::get('pei-profiles/{profileId}/indicadores',              'Admin\Planificacion\IndicadorController@porPerfil')->name('pei.indicadores.index');
    Route::post('pei-profiles/{profileId}/indicadores',             'Admin\Planificacion\IndicadorController@store')->name('pei.indicadores.store');
    Route::get('pei/actividades/buscar',                            'Admin\Planificacion\Pei\PeiController@buscarActividades')->name('pei.actividades.buscar');
    Route::post('pei-profiles/{profileId}/actividad',               'Admin\Planificacion\Pei\PeiController@crearActividadParaAccion')->name('pei.actividades.crear');
    Route::get('pei/actividades/{activityId}/tareas',               'Admin\Planificacion\Pei\PeiController@tareasDeActividad')->name('pei.actividades.tareas');
    Route::put('pei-profiles/{profileId}/indicadores/{id}',         'Admin\Planificacion\IndicadorController@update')->name('pei.indicadores.update');
    Route::delete('pei-profiles/{profileId}/indicadores/{id}',      'Admin\Planificacion\IndicadorController@destroy')->name('pei.indicadores.destroy');

    // ── Marcos Referenciales (PND, ODS, MECIP, etc.) ──────────────────────────
    Route::get('pei/marcos',                'Admin\Planificacion\MarcoReferencialController@index')->name('pei.marcos.index');
    Route::post('pei/marcos',               'Admin\Planificacion\MarcoReferencialController@store')->name('pei.marcos.store');
    Route::put('pei/marcos/{id}',           'Admin\Planificacion\MarcoReferencialController@update')->name('pei.marcos.update');
    Route::delete('pei/marcos/{id}',        'Admin\Planificacion\MarcoReferencialController@destroy')->name('pei.marcos.destroy');
    Route::patch('pei/marcos/{id}/toggle',  'Admin\Planificacion\MarcoReferencialController@toggle')->name('pei.marcos.toggle');
    Route::get('pei/marcos/tipos',          'Admin\Planificacion\MarcoReferencialController@tipos')->name('pei.marcos.tipos');
    Route::post('pei/marcos/tipos',         'Admin\Planificacion\MarcoReferencialController@storeTipo')->name('pei.marcos.tipos.store');
    Route::get('pei/marcos/buscar',         'Admin\Planificacion\MarcoReferencialController@buscar')->name('pei.marcos.buscar');
    Route::post('pei/marcos/crear',         'Admin\Planificacion\MarcoReferencialController@crear')->name('pei.marcos.crear');
    Route::get('pei-profiles/{profileId}/marcos',        'Admin\Planificacion\MarcoReferencialController@porPerfil')->name('pei.marcos.porPerfil');
    Route::post('pei-profiles/{profileId}/marcos/sync',  'Admin\Planificacion\MarcoReferencialController@sync')->name('pei.marcos.sync');

    // ── Módulo PGN ────────────────────────────────────────────────────────────
    Route::prefix('pgn')->name('pgn.')->middleware(['role:Administrador|Coordinador de Planificación|Analista PEI|Analista de Planificación|Analista|Coordinador de Proyectos|Coordinación de Proyectos'])->group(function () {
        Route::get('/',                                     'Admin\Planificacion\Pgn\PgnController@index')->name('index');
        // Estructura de niveles
        Route::get('estructura/{anio}',                    'Admin\Planificacion\Pgn\PgnController@estructuraDeAnio')->name('estructura.anio');
        Route::post('estructura',                          'Admin\Planificacion\Pgn\PgnController@storeEstructura')->name('estructura.store');
        Route::put('estructura/{estructura}',              'Admin\Planificacion\Pgn\PgnController@updateEstructura')->name('estructura.update');
        Route::delete('estructura/{estructura}',           'Admin\Planificacion\Pgn\PgnController@destroyEstructura')->name('estructura.destroy');
        // Nodos
        Route::get('nodos/{anio}',                         'Admin\Planificacion\Pgn\PgnController@nodosDeAnio')->name('nodos.anio');
        Route::post('nodos',                               'Admin\Planificacion\Pgn\PgnController@storeNodo')->name('nodos.store');
        Route::put('nodos/{nodo}',                         'Admin\Planificacion\Pgn\PgnController@updateNodo')->name('nodos.update');
        Route::delete('nodos/{nodo}',                      'Admin\Planificacion\Pgn\PgnController@destroyNodo')->name('nodos.destroy');
        // Búsqueda Select2
        Route::get('buscar',                               'Admin\Planificacion\Pgn\PgnController@buscar')->name('buscar');
        // Importación CSV
        Route::post('importar',                            'Admin\Planificacion\Pgn\PgnController@importar')->name('importar');
    });

    // Vinculación PGN ↔ Acción PEI
    Route::get('pei-profiles/{actionId}/pgn',  'Admin\Planificacion\Pgn\PeiAccionPgnController@porAccion')->name('pei.accion.pgn.get');
    Route::post('pei-profiles/{actionId}/pgn', 'Admin\Planificacion\Pgn\PeiAccionPgnController@sync')->name('pei.accion.pgn.sync');

    //Rutas de Elaboración del PEI
    Route::resource('tasks', 'Admin\Planificacion\Task\TaskController');
    // Route::resource('tasks-type', 'Admin\Planificacion\Task\TypeTaskController');
    Route::get('get-tasks', 'Admin\Planificacion\Task\TaskController@getTask')->name('get-tasks');
    Route::get('get-task/{idSelection}', 'Admin\Planificacion\Task\TaskController@dataTask')->name('get-task');
    // Route::get('get-type-tasks', 'Admin\Planificacion\Task\TypeTaskController@getTaskType')->name('get-type-tasks');
    Route::get('tasks-list-tree/', 'Admin\Planificacion\Task\TaskController@getTasksForGroup')->name('tasks-list-tree');
    Route::get('tree-group', 'Admin\Planificacion\Task\TaskController@dataTreeGroup')->name('tree-group');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('table-list', function () {
        return view('pages.table_list');
    })->name('table');

    Route::get('typography', function () {
        return view('pages.typography');
    })->name('typography');

    Route::get('icons', function () {
        return view('pages.icons');
    })->name('icons');

    Route::get('map', function () {
        return view('pages.map');
    })->name('map');

    Route::get('notifications', function () {
        return view('pages.notifications');
    })->name('notifications');

    Route::get('rtl-support', function () {
        return view('pages.language');
    })->name('language');

    Route::get('upgrade', function () {
        return view('pages.upgrade');
    })->name('upgrade');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'Admin\UserController', ['except' => ['show']]);
    Route::get('profile', function () { return redirect()->route('user.profile'); })->name('profile.edit');
    Route::post('profile', function () { return redirect()->route('user.profile'); })->name('profile.update');
    Route::post('profile/password', function () { return redirect()->route('user.profile'); })->name('profile.password');

    // ── RIISS - Red Integrada e Integral de Servicios de Salud ───────────────
    Route::prefix('riiss')->name('riiss.')->middleware(['role:Administrador|Super Admin|Analista - RIISS|Analista RIISS|Coordinador RIISS|Coordinador - RIISS|Coordinación RIISS'])->group(function () {

        // Centro de Control Unificado RIISS
        Route::get('/', [\App\Http\Controllers\Admin\Riiss\RiissCenterController::class, 'index'])
            ->name('index');
        Route::get('configuracion', [\App\Http\Controllers\Admin\Riiss\RiissCenterController::class, 'configuracion'])
            ->name('configuracion');
        Route::get('datos-unificados', [\App\Http\Controllers\Admin\Riiss\RiissCenterController::class, 'datosUnificados'])
            ->name('datos-unificados');
        Route::get('buscar-establecimientos', [\App\Http\Controllers\Admin\Riiss\RiissCenterController::class, 'buscarEstablecimientos'])
            ->name('buscar-establecimientos');

        // Aliases / Redirecciones de rutas anteriores
        Route::get('dashboard', function() { return redirect()->route('riiss.index'); })->name('dashboard');
        Route::get('asignaciones', function() { return redirect()->route('riiss.index'); })->name('asignaciones.index');
        Route::get('establecimientos', function() { return redirect()->route('riiss.index'); })->name('establecimientos.index');
        Route::get('evaluaciones', function() { return redirect()->route('riiss.index'); })->name('evaluaciones.index');

        // Endpoints de datos del Dashboard y Asignaciones
        Route::get('dashboard/datos', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'dashboardDatos'])
            ->name('dashboard.datos');
        Route::get('asignaciones/datos', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'datos'])
            ->name('asignaciones.datos');
        Route::post('asignaciones', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'store'])
            ->name('asignaciones.store');
        Route::get('asignaciones/{asignacion}/edit', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'edit'])
            ->name('asignaciones.edit');
        Route::put('asignaciones/{asignacion}', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'update'])
            ->name('asignaciones.update');
        Route::patch('asignaciones/{asignacion}/estado', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'actualizarEstado'])
            ->name('asignaciones.estado');
        Route::post('asignaciones/{asignacion}/renotificar', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'renotificar'])
            ->name('asignaciones.renotificar');
        Route::delete('asignaciones/{asignacion}', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'destroy'])
            ->name('asignaciones.destroy');

        // Formularios por nivel
        Route::get('formularios', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'index'])
            ->name('formularios.index');
        Route::get('formularios/datos', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'datos'])
            ->name('formularios.datos');
        Route::get('formularios/tipologias', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'tipologias'])
            ->name('formularios.tipologias');
        Route::get('formularios/tipologias/{tipologia}', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'showTipologia'])
            ->name('formularios.tipologias.show');
        Route::post('formularios/tipologias/{tipologia}/reglas', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'updateTipologia'])
            ->name('formularios.tipologias.update');
        Route::patch('formularios/tipologias/{tipologia}/renombrar', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'renombrarTipologia'])
            ->name('formularios.tipologias.renombrar');
        Route::patch('formularios/preguntas/{pregunta}/mapeo', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'actualizarMapeo'])
            ->name('formularios.preguntas.mapeo');
        Route::patch('formularios/preguntas/{pregunta}', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'updatePregunta'])
            ->name('formularios.preguntas.update');
        Route::delete('formularios/preguntas/{pregunta}', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'destroyPregunta'])
            ->name('formularios.preguntas.destroy');
        Route::get('formularios/secciones/{seccion}', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'showSeccion'])
            ->name('formularios.secciones.show');
        Route::patch('formularios/secciones/{seccion}', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'updateSeccion'])
            ->name('formularios.secciones.update');
        Route::post('formularios/secciones/{seccion}/preguntas', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'storePregunta'])
            ->name('formularios.secciones.preguntas.store');
        Route::post('formularios/secciones', [\App\Http\Controllers\Admin\Riiss\FormularioController::class, 'storeSeccion'])
            ->name('formularios.secciones.store');

        // Mis asignaciones (evaluador)
        Route::get('mis-asignaciones', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'misAsignaciones'])
            ->name('mis-asignaciones');

        // Establecimientos
        Route::get('establecimientos', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'index'])
            ->name('establecimientos.index');
        Route::get('establecimientos/filtros/opciones', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'filtros'])
            ->name('establecimientos.filtros');
        Route::get('establecimientos/estadisticas', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'estadisticas'])
            ->name('establecimientos.estadisticas');
        Route::get('establecimientos/resolver-alias', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'resolverAlias'])
            ->name('establecimientos.resolver-alias');
        Route::post('establecimientos/recalcular-derivados', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'recalcular'])
            ->name('establecimientos.recalcular');
        Route::get('establecimientos/buscar', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'buscar'])
            ->name('establecimientos.buscar');
        Route::patch('establecimientos/{id}', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'update'])
            ->name('establecimientos.update');

        // Grados de Complejidad (Administrador y Coordinador RIISS)
        Route::middleware(['role:Administrador|Super Admin|Coordinador RIISS|Coordinador - RIISS|Coordinación RIISS'])->group(function () {
            Route::get('complejidad', [\App\Http\Controllers\Admin\Riiss\ComplejidadTipoController::class, 'index'])
                ->name('complejidad.index');
            Route::get('complejidad/{complejidadTipo}/edit', [\App\Http\Controllers\Admin\Riiss\ComplejidadTipoController::class, 'edit'])
                ->name('complejidad.edit');
            Route::put('complejidad/{complejidadTipo}', [\App\Http\Controllers\Admin\Riiss\ComplejidadTipoController::class, 'update'])
                ->name('complejidad.update');
        });

        // Evaluaciones — helpers
        Route::get('evaluaciones/usuarios', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'buscarUsuarios'])
            ->name('evaluaciones.usuarios');

        // Localidades — selectores encadenados
        Route::get('localidades/departamentos', [\App\Http\Controllers\Admin\Riiss\LocalidadController::class, 'departamentos'])
            ->name('localidades.departamentos');
        Route::get('localidades/distritos', [\App\Http\Controllers\Admin\Riiss\LocalidadController::class, 'distritos'])
            ->name('localidades.distritos');
        Route::get('localidades/barrios', [\App\Http\Controllers\Admin\Riiss\LocalidadController::class, 'barrios'])
            ->name('localidades.barrios');
        Route::get('establecimientos/{id}', [\App\Http\Controllers\Admin\Riiss\EstablecimientoController::class, 'show'])
            ->name('establecimientos.show');

        // Evaluaciones
        Route::get('evaluaciones', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'index'])
            ->name('evaluaciones.index');
        Route::get('evaluaciones/formulario/{id_establecimiento}', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'formulario'])
            ->name('evaluaciones.formulario');
        Route::get('evaluaciones/requisitos/{id_establecimiento}', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'requisitos'])
            ->name('evaluaciones.requisitos');
        Route::get('evaluaciones/nueva/{id_establecimiento}', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'nueva'])
            ->name('evaluaciones.nueva');
        Route::post('evaluaciones', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'crear'])
            ->name('evaluaciones.crear');
        Route::put('evaluaciones/{evaluacion}/respuestas', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'guardarRespuestas'])
            ->name('evaluaciones.respuestas');
        Route::patch('evaluaciones/{evaluacion}/datos-visita', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'actualizarDatosVisita'])
            ->name('evaluaciones.datos-visita');
        Route::post('evaluaciones/{evaluacion}/ejecutar-gap', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'ejecutarGap'])
            ->name('evaluaciones.gap.ejecutar');
        Route::get('evaluaciones/{evaluacion}/gap', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'obtenerGap'])
            ->name('evaluaciones.gap');
        Route::get('evaluaciones/{evaluacion}/resumen-clasificacion', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'resumenClasificacion'])
            ->name('evaluaciones.clasificacion');
        Route::get('evaluaciones/{evaluacion}', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'show'])
            ->name('evaluaciones.show');
        Route::delete('evaluaciones/{evaluacion}', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'destroy'])
            ->name('evaluaciones.destroy');
    });
});

// ── Rutas públicas RIISS (sin auth) ──────────────────────────────────────────
Route::get('riiss/evaluaciones/{evaluacion}/matriz-partial',
    [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'matrizPartial'])
    ->name('evaluaciones.matriz-partial');

// ── Rutas públicas Proyectos (sin auth) ───────────────────────────────────────
Route::get('pei-profiles/{profileId}/solicitar-proyecto',
    [\App\Http\Controllers\Admin\Proyectos\ProyectoInstitucionalController::class, 'solicitarForm'])
    ->name('proyectos.solicitar.form');
Route::post('pei-profiles/{profileId}/solicitar-proyecto',
    [\App\Http\Controllers\Admin\Proyectos\ProyectoInstitucionalController::class, 'solicitarStore'])
    ->name('proyectos.solicitar.store');
Route::get('pei-profiles/{profileId}/proyectos/acciones-publico',
    [\App\Http\Controllers\Admin\Proyectos\ProyectoInstitucionalController::class, 'getAccionesDePerfil'])
    ->name('proyectos.solicitar.acciones');

// ── Rutas públicas Solicitud de Ajuste de Estructura Organizacional (sin auth) ─
Route::get('pei-profiles/{profileId}/solicitar-ajuste-estructura',
    [\App\Http\Controllers\Admin\Planificacion\Estructura\SolicitudAjusteEstructuraController::class, 'solicitarForm'])
    ->name('proyectos.solicitar.ajuste-estructura.form');
Route::post('pei-profiles/{profileId}/solicitar-ajuste-estructura',
    [\App\Http\Controllers\Admin\Planificacion\Estructura\SolicitudAjusteEstructuraController::class, 'solicitarStore'])
    ->name('proyectos.solicitar.ajuste-estructura.store');
Route::get('solicitar-ajuste-estructura',
    [\App\Http\Controllers\Admin\Planificacion\Estructura\SolicitudAjusteEstructuraController::class, 'solicitarForm'])
    ->name('estructura.solicitar.directo');
Route::get('solicitud-estructura/{token}',
    [\App\Http\Controllers\Admin\Planificacion\Estructura\SolicitudAjusteEstructuraController::class, 'consultarPublica'])
    ->name('solicitud-estructura.consulta');

Route::get('/debug-patrimonies', function() { return Illuminate\Support\Facades\Schema::getColumnListing('patrimonies'); });

// ── Módulo de Soporte Técnico y Reporte de Fallas ─────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::post('/soporte/tickets', [\App\Http\Controllers\Admin\Soporte\SoporteTicketController::class, 'store'])
        ->name('soporte.tickets.store');
    Route::get('/admin/soporte/tickets/count-pending', [\App\Http\Controllers\Admin\Soporte\SoporteTicketController::class, 'countPending'])
        ->name('admin.soporte.tickets.countPending');

    Route::middleware(['role:Administrador'])->prefix('admin/soporte')->name('admin.soporte.')->group(function() {
        Route::get('/tickets', [\App\Http\Controllers\Admin\Soporte\SoporteTicketController::class, 'index'])->name('tickets.index');
        Route::put('/tickets/{ticket}/status', [\App\Http\Controllers\Admin\Soporte\SoporteTicketController::class, 'updateStatus'])->name('tickets.updateStatus');
        Route::delete('/tickets/{ticket}', [\App\Http\Controllers\Admin\Soporte\SoporteTicketController::class, 'destroy'])->name('tickets.destroy');
    });

    // ── Asistente de IA (Groq / Llama 3.3 70B) ─────────────────────────────
    Route::prefix('admin/ai')->name('admin.ai.')->group(function() {
        Route::post('/redactar-smart', [\App\Http\Controllers\Admin\Ai\AiAssistantController::class, 'redactarSmart'])->name('redactarSmart');
        Route::post('/sugerir-indicador', [\App\Http\Controllers\Admin\Ai\AiAssistantController::class, 'sugerirIndicador'])->name('sugerirIndicador');
        Route::post('/generar-accion-completa', [\App\Http\Controllers\Admin\Ai\AiAssistantController::class, 'generarAccionCompleta'])->name('generarAccionCompleta');
    });

    // ── Módulo de Juntas Consultivas (Consejo de Sabios) & Intervención de Alertas ──
    Route::prefix('admin/planificacion/juntas')->name('admin.juntas.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'store'])->name('store');
        Route::get('/intervenciones', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'intervenciones'])->name('intervenciones');
        Route::post('/remitir-alerta', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'remitirAlerta'])->name('remitirAlerta');
        Route::post('/crear-usuario-rapido', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'crearUsuarioRapido'])->name('crearUsuarioRapido');
        Route::post('/dictamen/{id}', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'emitirDictamen'])->name('emitirDictamen');
        Route::post('/sugerir-mitigacion-ia', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'sugerirMitigacionIa'])->name('sugerirMitigacionIa');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\Planificacion\JuntaController::class, 'destroy'])->name('destroy');
    });

    // ── Módulo de Novedades y Desarrollos del Sistema (Git Changelog) ───────
    Route::get('/admin/novedades', [\App\Http\Controllers\Admin\NovedadesController::class, 'index'])->name('admin.novedades.index');
});

// ── Módulo de Sincronización y Control MECIP (IPS) (Manejado con Auth interno en Controller) ───
Route::prefix('admin/mecip/control')->name('admin.mecip.control.')->group(function() {
    Route::get('/', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'store'])->name('store');
    Route::post('/importar-json', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'importarJson'])->name('importarJson');
    Route::get('/{id}', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'show'])->name('show');
    Route::post('/{id}/remitir-lider', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'remitirALider'])->name('remitirALider');
    Route::post('/{id}/resolver-elemento', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'resolverElemento'])->name('resolverElemento');
    Route::post('/{id}/cerrar-analisis', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'cerrarAnalisis'])->name('cerrarAnalisis');
    Route::post('/{id}/actividades', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'agregarActividad'])->name('agregarActividad');
    Route::post('/actividades/{actividadId}/tareas', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'agregarTarea'])->name('agregarTarea');
    Route::post('/sync-bpm', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'syncFromBpm'])->name('syncFromBpm');
    Route::post('/parse-html', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'parseHtmlPayload'])->name('parseHtmlPayload');
});
