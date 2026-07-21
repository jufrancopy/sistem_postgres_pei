<?php

use App\Http\Controllers\Admin\Globales\OpenAIController;
use App\Http\Controllers\Admin\Globales\Survey\QuestionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    // Actividades y tareas
    $activities = \App\Admin\Globales\Activity::with(['tasks', 'responsibles'])->latest()->take(6)->get();
    $totalTareas    = \App\Admin\Globales\ActivityTask::count();
    $tareasEnCurso  = \App\Admin\Globales\ActivityTask::where('status', 1)->count();
    $tareasHechas   = \App\Admin\Globales\ActivityTask::where('status', 2)->count();
    $tareasVencidas = \App\Admin\Globales\ActivityTask::where('status', '!=', 2)
        ->whereNotNull('fecha_vencimiento')
        ->where('fecha_vencimiento', '<', now())
        ->count();

    // Evaluaciones RIISS
    $evaluaciones    = \App\Models\Riiss\Evaluacion::with('establecimiento')->latest('fecha_evaluacion')->take(8)->get();
    $evalTotal       = \App\Models\Riiss\Evaluacion::count();
    $evalCompletadas = \App\Models\Riiss\Evaluacion::where('estado', 'completada')->count();
    $evalEnCurso     = \App\Models\Riiss\Evaluacion::where('estado', 'en_curso')->count();

    // SIESS
    $siessModulos    = \App\Models\Estadistica\SiessModulo::where('activo', true)->orderBy('orden')->get();
    $siessAprobados  = \App\Models\Estadistica\SiessExtracto::aprobados()->count();
    $siessPendientes = \App\Models\Estadistica\SiessExtracto::pendientes()->count();
    $siessObjetados  = \App\Models\Estadistica\SiessExtracto::where('estado', 'objetado')->count();

    // FODA
    $fodaPerfiles    = \App\Admin\Planificacion\Foda\FodaPerfil::count();
    $fodaAnalisis    = \App\Admin\Planificacion\Foda\FodaAnalisis::count();
    $fodaFortalezas  = \App\Admin\Planificacion\Foda\FodaAnalisis::where('tipo', 'fortaleza')->count();
    $fodaDebilidades = \App\Admin\Planificacion\Foda\FodaAnalisis::where('tipo', 'debilidad')->count();
    $fodaOportunidades = \App\Admin\Planificacion\Foda\FodaAnalisis::where('tipo', 'oportunidad')->count();
    $fodaAmenazas    = \App\Admin\Planificacion\Foda\FodaAnalisis::where('tipo', 'amenaza')->count();
    $fodaEstrategias = \App\Admin\Planificacion\Foda\FodaCruceAmbiente::count();
    $fodaIeaResumen  = \App\Admin\Planificacion\Foda\FodaAnalisis::whereNotNull('iea_clasificacion')
        ->selectRaw('iea_clasificacion, count(*) as total')
        ->groupBy('iea_clasificacion')
        ->pluck('total', 'iea_clasificacion');

    // PEI — solo raíces master (parent_id null)
    $peiPlanes       = \App\Admin\Planificacion\Pei\PeiProfile::whereNull('parent_id')->where('level', 'master')->count();
    $peiAcciones     = \App\Admin\Planificacion\Pei\PeiProfile::where('level', 'action')->count();
    $peiSemaforo     = \App\Admin\Planificacion\Pei\PeiProfile::whereNotNull('semaforo')
        ->selectRaw('semaforo, count(*) as total')
        ->groupBy('semaforo')
        ->pluck('total', 'semaforo');
    $peiRecientes    = \App\Admin\Planificacion\Pei\PeiProfile::whereNull('parent_id')
        ->where('level', 'master')
        ->latest()->take(5)
        ->get(['id', 'name', 'year_start', 'year_end', 'semaforo', 'type']);

    return view('welcome', compact(
        'activities', 'totalTareas', 'tareasEnCurso', 'tareasHechas', 'tareasVencidas',
        'evaluaciones', 'evalTotal', 'evalCompletadas', 'evalEnCurso',
        'siessModulos', 'siessAprobados', 'siessPendientes', 'siessObjetados',
        'fodaPerfiles', 'fodaAnalisis', 'fodaFortalezas', 'fodaDebilidades',
        'fodaOportunidades', 'fodaAmenazas', 'fodaEstrategias', 'fodaIeaResumen',
        'peiPlanes', 'peiAcciones', 'peiSemaforo', 'peiRecientes'
    ));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

// ── Vistas públicas PEI (sin autenticación) ───────────────────────────────────
Route::get('/public/pei/{token}', 'Admin\Planificacion\PublicPeiController@show')->name('pei.public.show');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('products', 'Admin\ProductController');

    // ── Mis Tareas (colaboradores) ────────────────────────────────────────────
    Route::get('mis-tareas/{activityId}', 'Admin\Globales\ActivityController@misTareas')->name('globales.mis-tareas');
    Route::get('mis-actividades', 'Admin\Globales\ActivityController@misActividades')->name('globales.activities.mis-actividades');

    //Rutas del Dpto. Planificacion
    Route::get('planificacion-dashboard', 'Admin\Planificacion\PlanificacionController@dashboard')->name('planificacion-dashboard');

    // ── Plan Maestro ─────────────────────────────────────────────────────────
    Route::get('plan-maestro', 'Admin\PlanMaestro\PlanMaestroController@index')->name('plan-maestro.index');
    Route::get('plan-maestro/{plan}', 'Admin\PlanMaestro\PlanMaestroController@show')->name('plan-maestro.show');
    Route::get('plan-maestro/{plan}/buscar', 'Admin\PlanMaestro\PlanMaestroController@buscar')->name('plan-maestro.buscar');
    Route::patch('plan-maestro/acciones/{accion}/estado', 'Admin\PlanMaestro\PlanMaestroController@actualizarEstado')->name('plan-maestro.accion.estado');
    Route::post('plan-maestro/{plan}/acciones', 'Admin\PlanMaestro\PlanMaestroController@storeAccion')->name('plan-maestro.accion.store');
    Route::delete('plan-maestro/acciones/{accion}', 'Admin\PlanMaestro\PlanMaestroController@destroyAccion')->name('plan-maestro.accion.destroy');

    //Rutas de PEI
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
    Route::get('pei-profiles/{idProfile}/matriz',        'Admin\Planificacion\Pei\PeiController@matriz')->name('pei-profiles.matriz');
    Route::get('pei-profiles/{idProfile}/matriz/pdf',    'Admin\Planificacion\Pei\PeiController@matrizPdf')->name('pei-profiles.matriz.pdf');
    Route::get('pei-profiles/{idProfile}/dashboard', 'Admin\Planificacion\Pei\PeiController@dashboard')->name('pei-profiles.dashboard');

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

    // Rutas de Proyectos 
    Route::get('proyectos-dashboard', 'Admin\Proyectos\ProyectosDashboardController@index')->name('proyectos-dashboard');

    //Estandar por Complejidad
    Route::view('proyectos-epc-dashboard', 'admin.proyectos.epc.dashboard')->name('proyectos-epc-dashboard');
    Route::get('proyectos-epc-home', 'Admin\Proyectos\EPC\EPCController@getHome')->name('proyectos-epc-home');
    Route::get('proyectos-epc/{type}', 'Admin\Proyectos\EPC\EquipamientoController@getForType')->name('proyectos-epc');

    // Horarios
    Route::resource('proyectos-epc-horarios', 'Admin\Proyectos\EPC\HorarioController');

    // Horarios
    Route::resource('proyectos-epc-horarios', 'Admin\Proyectos\EPC\HorarioController');

    // TTHH
    Route::resource('proyectos-epc-tthh', 'Admin\Proyectos\EPC\TalentoHumanoController');
    Route::get('tthhs/get',         'Admin\Proyectos\EPC\TalentoHumanoController@get')->name('tthhs.get');

    // Equipamientos
    Route::resource('proyectos-epc-equipamientos', 'Admin\Proyectos\EPC\EquipamientoController');
    Route::get('equipamientos/get',         'Admin\Proyectos\EPC\EquipamientoController@get')->name('equipamientos.get');

    // Orders
    Route::resource('orders', 'OrdersController');

    // Infraestructuras
    Route::resource('proyectos-epc-infraestructuras', 'Admin\Proyectos\EPC\InfraestructuraController');
    Route::get('infraestructuras/get', 'Admin\Proyectos\EPC\InfraestructuraController@get')->name('infraestructuras.get');

    // Otros Servicios
    Route::resource('proyectos-epc-otros_servs', 'Admin\Proyectos\EPC\OtroServicioController');
    Route::get('otro-servicios/get', 'Admin\Proyectos\EPC\OtroServicioController@get')->name('otroServicios.get');

    // Horarios
    Route::resource('proyectos-epc-horarios', 'Admin\Proyectos\EPC\HorarioController');

    // Prestaciones
    Route::resource('proyectos-epc-prestaciones', 'Admin\Proyectos\EPC\PrestacionController');
    Route::get('prestaciones/get', 'Admin\Proyectos\EPC\PrestacionController@get')->name('prestaciones.get');

    // Turnos
    Route::resource('proyectos-epc-turnos', 'Admin\Proyectos\EPC\TurnoController');

    // Medicamento e Insumos
    Route::resource('proyectos-epc-mds_ins', 'Admin\Proyectos\EPC\MedicamentoInsumoController');

    // Specialties
    Route::resource('proyectos-epc-especialidades', 'Admin\Proyectos\EPC\EspecialidadController');
    Route::get('proyectos-epc-especialidad/{type}', 'Admin\Proyectos\EPC\EspecialidadController@getForType')->name('especialidades');

    // Servicios
    Route::resource('proyectos-epc-servicios', 'Admin\Proyectos\EPC\ServicioController');
    Route::get('proyectos-epc-servs/{type}', 'Admin\Proyectos\EPC\ServicioController@getForType')->name('servicios');
    Route::get('servicios/get', 'Admin\Proyectos\EPC\ServicioController@get')->name('servicios.get');

    // Estándares 
    Route::resource('proyectos-epc-estandares', 'Admin\Proyectos\EPC\EstandarController');

    // Riesgos 
    Route::resource('risks', 'Admin\Planificacion\Riesgo\RiskController');

    // ── Proyectos Institucionales (SCPI) ──────────────────────────────────────
    Route::resource('proyectos-institucionales', 'Admin\Proyectos\ProyectoInstitucionalController');
    Route::post('proyectos-institucionales/{id}/estado',    'Admin\Proyectos\ProyectoInstitucionalController@cambiarEstado')->name('proyectos-institucionales.estado');
    Route::post('proyectos-institucionales/{id}/checklist', 'Admin\Proyectos\ProyectoInstitucionalController@updateChecklist')->name('proyectos-institucionales.checklist');
    Route::get('proyectos-institucionales-pei-acciones',    'Admin\Proyectos\ProyectoInstitucionalController@getPeiAcciones')->name('proyectos-institucionales.pei-acciones');

    Route::group(['prefix' => 'admin/globales', 'as' => 'globales.'], function () {
        //Dashboard
        Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'Admin\Globales\GlobalesController@dashboard']);

        // Cronogramas
        Route::get('cronogramas/gantt', 'Admin\Globales\ScheduleController@gantt')->name('cronogramas.gantt');
        Route::middleware(['role:Administrador|Gestor de Actividades'])->group(function () {
            Route::get('cronogramas', 'Admin\Globales\ScheduleController@index')->name('cronogramas.index');
            Route::post('cronogramas/import', 'Admin\Globales\ScheduleController@import')->name('cronogramas.import');
            Route::get('cronogramas/{period}', 'Admin\Globales\ScheduleController@show')->name('cronogramas.show');
        });

        // ── Actividades: index/crear/editar/eliminar solo Administrador ────────
        Route::middleware(['role:Administrador'])->group(function () {
            Route::resource('activities', 'Admin\Globales\ActivityController', ['except' => ['show']]);
        });

        // ── Show: Administrador + Gestor + Colaborador ───────────────────────
        Route::get('activities/{activity}', 'Admin\Globales\ActivityController@show')->name('activities.show');

        // ── Tareas: Administrador y Gestor de Actividades ─────────────────
        Route::middleware(['role:Administrador|Gestor de Actividades'])->group(function () {
            Route::post('activities/{activityId}/tareas', 'Admin\Globales\ActivityController@storeTarea')->name('activities.tareas.store');
            Route::delete('activities/tareas/{taskId}', 'Admin\Globales\ActivityController@destroyTarea')->name('activities.tareas.destroy');
            Route::post('activities/tareas/{taskId}/evidencias', 'Admin\Globales\ActivityController@storeEvidencia')->name('activities.tareas.evidencias.store');
            Route::delete('activities/tareas/evidencias/{evidenceId}', 'Admin\Globales\ActivityController@destroyEvidencia')->name('activities.tareas.evidencias.destroy');
            Route::post('activities/{activityId}/notificar-todos', 'Admin\Globales\ActivityController@notificarTodos')->name('activities.notificar-todos');
            Route::post('activities/tareas/{taskId}/notificar', 'Admin\Globales\ActivityController@notificarTarea')->name('activities.tareas.notificar');
        });

        Route::middleware(['role:Administrador|Gestor de Actividades|Colaborador de Actividades'])->group(function () {
            Route::patch('activities/tareas/{taskId}/reasignar', 'Admin\Globales\ActivityController@reasignarTarea')->name('activities.tareas.reasignar');
        });

        // ── Mover tarea: Gestor + Colaborador (el controlador valida ownership) ──
        Route::patch('activities/tareas/{taskId}/status', 'Admin\Globales\ActivityController@updateStatus')->name('activities.tareas.status');

        // ── Comentarios: todos los autenticados ───────────────────────────────
        Route::get('activities/tareas/{taskId}/detalle', 'Admin\Globales\ActivityController@detalleTarea')->name('activities.tareas.detalle');
        Route::get('activities/{activityId}/reuniones', 'Admin\Globales\ActivityController@reuniones')->name('activities.reuniones');
        Route::get('activities/tareas/{taskId}/comentarios', 'Admin\Globales\ActivityController@getComentarios')->name('activities.tareas.comentarios.index');
        Route::post('activities/tareas/{taskId}/comentarios', 'Admin\Globales\ActivityController@storeComentario')->name('activities.tareas.comentarios.store');
        Route::delete('activities/tareas/comentarios/{commentId}', 'Admin\Globales\ActivityController@destroyComentario')->name('activities.tareas.comentarios.destroy');


        //Localities
        Route::resource('localities', 'Admin\Globales\LocalityController');
        Route::resource('patrimonies', 'Admin\Globales\PatrimonyController');
        Route::resource('patrimony-profiles', 'Admin\Globales\PatrimonyProfileController');
        Route::get('patrimony-profiles/{idPatrimonyProfile}/detail', 'Admin\Globales\PatrimonyProfileController@detailPatrimonyProfile')->name('patrimonies.detail-profile');

        // Get data from Select2
        Route::get('/locality/{state}/cities', 'Admin\Globales\LocalityController@getCities');
        Route::get('/locality/{city}/localities', 'Admin\Globales\LocalityController@getLocalities');

        //Roles and permissions
        Route::resource('users', 'Admin\UserController');
        Route::resource('permisos', 'Admin\PermissionController');
        Route::resource('roles', 'Admin\RoleController');
        Route::get('roles/{id}/edit-ajax', 'Admin\RoleController@editAjax')->name('roles.edit-ajax');
        Route::get('roles/{id}/show-ajax', 'Admin\RoleController@showAjax')->name('roles.show-ajax');
        Route::get('get-roles', 'Admin\RoleController@getRoles')->name('get-roles');
        Route::get('get-role/{userId}', 'Admin\RoleController@getRole')->name('get-role');

        Route::get('formularios-dependecies', 'Admin\Globales\Formulario\FormularioController@getDependencies')->name('formularios.get-dependencies');
        Route::resource('formularios', 'Admin\Globales\Formulario\FormularioController');
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
        Route::get('get-root-groups', 'Admin\Globales\GroupController@getRootGroups')->name('get-root-groups');
        Route::get('get-groups/{idRoot}', 'Admin\Globales\GroupController@getGroupsFromRoot')->name('get-groups');
        Route::get('get-group-parent/{idSelection}', 'Admin\Globales\GroupController@dataGroupParent')->name('get-group-parent');
        Route::get('get-group/{idSelection}', 'Admin\Globales\GroupController@dataGroup')->name('get-group');
        Route::get('get-users', 'Admin\UserController@getUsers')->name('get-users');
        Route::get('get-user/{id}', 'Admin\UserController@getUser')->name('get-user');
        Route::get('get-users/{idGroup}', 'Admin\UserController@getUsersForGroup')->name('get-users-group');
    });

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
    Route::get('pei-profiles/{profileId}/bsc',                       'Admin\Planificacion\PeiReporteController@bsc')->name('pei.bsc');
    Route::post('pei-profiles/{profileId}/notificar-todos',          'Admin\Planificacion\PeiReporteController@notificarTodos')->name('pei.reportes.notificar-todos');
    Route::post('pei-profiles/{profileId}/acciones/{accionId}/notificar', 'Admin\Planificacion\PeiReporteController@notificarAccion')->name('pei.reportes.notificar-accion');
    Route::get('pei-profiles/{accionId}/reportes',                   'Admin\Planificacion\PeiReporteController@index')->name('pei.reportes.index');
    Route::post('pei-profiles/{accionId}/reportes',                  'Admin\Planificacion\PeiReporteController@store')->name('pei.reportes.store');
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
    Route::prefix('pgn')->name('pgn.')->middleware(['role:Administrador|Analista PEI'])->group(function () {
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
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    // ── RIISS - Red Integrada e Integral de Servicios de Salud ───────────────
    Route::prefix('riiss')->name('riiss.')->middleware(['role:Administrador|Analista - RIISS'])->group(function () {

        // Dashboard de monitoreo
        Route::get('dashboard', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'dashboard'])
            ->name('dashboard');
        Route::get('dashboard/datos', [\App\Http\Controllers\Admin\Riiss\EvaluacionController::class, 'dashboardDatos'])
            ->name('dashboard.datos');

        // Asignaciones (admin)
        Route::get('asignaciones', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'index'])
            ->name('asignaciones.index');
        Route::get('asignaciones/datos', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'datos'])
            ->name('asignaciones.datos');
        Route::post('asignaciones', [\App\Http\Controllers\Admin\Riiss\AsignacionController::class, 'store'])
            ->name('asignaciones.store');
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

        // Grados de Complejidad (solo Administrador)
        Route::middleware(['role:Administrador'])->group(function () {
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
