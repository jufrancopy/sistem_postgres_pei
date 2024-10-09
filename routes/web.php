<?php

use App\Http\Controllers\Admin\Survey\SurveyController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('products', 'Admin\ProductController');
    //Rutas del Dpto. Planificacion
    Route::get('planificacion-dashboard', 'Admin\Planificacion\PlanificacionController@dashboard')->name('planificacion-dashboard');

    //Rutas de PEI
    Route::resource('pei-profiles', 'Admin\Planificacion\Pei\PeiController');
    Route::get('pei-profiles/{idPerfil}/detail', 'Admin\Planificacion\Pei\PeiController@showDetailForGroup');
    Route::get('pei-profiles-compareHistorical', 'Admin\Planificacion\Pei\PeiController@compareHistorical')->name('pei-profiles-compareHistorical');
    Route::get('pei-profiles-details/{idProfile}', 'Admin\Planificacion\Pei\PeiController@showDetailsTree')->name('pei-profiles.details');
    Route::get('pei-profiles/{idProfile}/axis-list', 'Admin\Planificacion\Pei\PeiController@showAxisList')->name('pei-profiles-axis-list');
    Route::get('pei-profiles/{idProfile}/goals-list', 'Admin\Planificacion\Pei\PeiController@showGoalsList')->name('pei-profiles-goals-list');
    Route::get('pei-profiles/{idProfile}/actions-list', 'Admin\Planificacion\Pei\PeiController@showActionsList')->name('pei-profiles-actions-list');
    Route::get('pei-profiles/{idProfile}/members-list', 'Admin\Planificacion\Pei\PeiController@showMembersList')->name('pei-profiles-members-list');
    Route::get('pei-profiles/{idProfile}/report-progress', 'Admin\Planificacion\Pei\PeiController@getDetails')->name('pei-profiles-reports');

    // Relevamientos
    Route::get('proyectos-epc-relevamientos/{estandarId}', 'Admin\Proyectos\EPC\RelevamientoController@getFormulario')->name('proyectos-epc-relevamientos-form-dependencia');

    // Rutas de Estadisticas
    Route::view('estadisticas-dashboard', 'admin.estadisticas.dashboard')->name('estadisticas-dashboard');

    // Rutas de Proyectos 
    Route::view('proyectos-dashboard', 'admin.proyectos.dashboard')->name('proyectos-dashboard');

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

    Route::group(['prefix' => 'admin/globales', 'as' => 'globales.'], function () {
        //Dashboard
        Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'Admin\Globales\GlobalesController@dashboard']);
        Route::resource('activities', 'Admin\Globales\ActivityController');

        //Localities
        Route::resource('localities', 'Admin\Globales\LocalityController');
        Route::resource('patrimonies', 'Admin\Globales\PatrimonyController');

        // Get data from Select2
        Route::get('/locality/{state}/cities', 'Admin\Globales\LocalityController@getCities');
        Route::get('/locality/{city}/localities', 'Admin\Globales\LocalityController@getLocalities');

        //Roles and permissions
        Route::resource('users', 'Admin\UserController');
        Route::resource('permisos', 'Admin\PermissionController');
        Route::resource('roles', 'Admin\RoleController');
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
        Route::get('get-dependencies-root', 'Admin\Globales\OrganigramaController@getDependenciesRoot')->name('get-dependencies-root');

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
        Route::get('get-users/{idGroup}', 'Admin\UserController@getUsersForGroup')->name('get-users-group');
    });

    Route::prefix('surveys')->group(function () {
        // Mostrar la página inicial del Quiz
        Route::get('/', [SurveyController::class, 'index'])->name('surveys.index');

        // Mostrar una pregunta específica
        Route::get('/question/{id}', [SurveyController::class, 'show'])->name('surveys.show');

        // Enviar una respuesta para la pregunta
        Route::post('/answer/{id}', [SurveyController::class, 'answer'])->name('surveys.answer');

        // Mostrar los resultados al finalizar el Quiz
        Route::get('/result', [SurveyController::class, 'result'])->name('surveys.result');
    });

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
});
