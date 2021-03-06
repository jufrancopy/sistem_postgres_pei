<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    
    //Rutas del tipo Resource
    Route::resource('permisos', 'Admin\PermissionController');
    Route::resource('roles', 'Admin\RoleController');
    Route::resource('users', 'Admin\UserController');
    Route::resource('products', 'Admin\ProductController');
    Route::resource('organigramas', 'Admin\Globales\OrganigramaController');
    Route::get('organigramas-crear-subdependencia/{idDependencia}', 'Admin\Globales\OrganigramaController@crearSubDependencia')->name('organigramas-crear-subdependencia');
    Route::get('organigramas-editar-subdependencia/{idDependencia}', 'Admin\Globales\OrganigramaController@editarSubDependencia')->name('organigramas-editar-subdependencia');
    Route::get('organigramas-listado', 'Admin\Globales\OrganigramaController@listaOrganigramas')->name('organigramas-listado');
    Route::get('organigramas-ver/{id}', 'Admin\Globales\OrganigramaController@verOrganigrama')->name('organigramas-ver');
    
    //Rutas del Dpto. Planificacion
    Route::get('planificacion-dashboard', 'Admin\Planificacion\PlanificacionController@dashboard')->name('planificacion-dashboard');
    
    //Rutas de PEI
    Route::view('peis-dashboard', 'admin.planificacion.peis.index')->name('peis-dashboard');
    Route::resource('peis-perfiles', 'Admin\Planificacion\Pei\PeiPerfilController');

    // Rutas de Estadisticas
    Route::view('estadisticas-dashboard', 'admin.estadisticas.dashboard')->name('estadisticas-dashboard');

    // Rutas de Proyectos
    Route::view('proyectos-dashboard', 'admin.proyectos.dashboard')->name('proyectos-dashboard');

    
    // Rutas Configuraciones Globales
    Route::get('globales-dashboard', 'Admin\Globales\GlobalesController@dashboard')->name('globales-dashboard');
    Route::resource('formulario-variables', 'Admin\Globales\Formulario\VariableController');
    Route::get('formulario-variables-items/{idVariable}', 'Admin\Globales\Formulario\ItemController@itemsVariable')->name('formulario-variables-items');
    Route::get('formulario-agregar-item/{idVariable}', 'Admin\Globales\Formulario\ItemController@agregarItem')->name('formulario-agregar-item');
    
    Route::resource('formulario-items', 'Admin\Globales\Formulario\ItemController');
    Route::resource('formulario-clasificadores', 'Admin\Globales\Formulario\ClasificadorController');
    Route::get('formulario-clasificadores-listado', 'Admin\Globales\Formulario\ClasificadorController@listaClasificadores')->name('formulario-clasificadores-listado');
    Route::get('formulario-clasificadores-crear-subclasificador/{idClasificador}', 'Admin\Globales\Formulario\ClasificadorController@crearSubClasificador')->name('formulario-clasificadores-crear-subclasificador');
    Route::get('formulario-clasificadores-editar-subclasificador/{idClasificador}', 'Admin\Globales\Formulario\ClasificadorController@editarSubClasificador')->name('formulario-clasificadores-editar-subclasificador');
    
    Route::resource('formulario-formularios', 'Admin\Globales\Formulario\FormularioController');
    Route::resource('estructuras-control', 'Admin\Globales\EstructuraControlController');
    Route::get('/estructuras-control/{id}/subdependencias','Admin\Globales\EstructuraControlController@getSubDependencias');
    

    //Rutas del Modulo FODA
    Route::resource('foda-modelos', 'Admin\Planificacion\Foda\FodaModeloController');
    Route::resource('foda-categorias', 'Admin\Planificacion\Foda\FodaCategoriaController');
    Route::get('foda-modelo-categorias/{idModelo}', 'Admin\Planificacion\Foda\FodaCategoriaController@listadoCategorias')->name('foda-modelo-categorias');
    Route::get('foda-modelo-categoria-crear/{idModelo}', 'Admin\Planificacion\Foda\FodaCategoriaController@crearCategoria')->name('foda-modelo-categoria-crear');
    Route::get('perfil-categorias/{id}', 'Admin\Planificacion\Foda\FodaAnalisisController@listarCategorias')->name('perfil-categorias');
    Route::resource('foda-aspectos', 'Admin\Planificacion\Foda\FodaAspectoController');
    Route::get('foda-modelo-categoria-aspectos/{idModelo}/{idCategoria}', 'Admin\Planificacion\Foda\FodaModeloController@listadoAspectos')->name('foda-modelo-categoria-aspectos');
    Route::get('foda-modelo-categoria-aspectos-crear/{idCategoria}', 'Admin\Planificacion\Foda\FodaAspectoController@crearAspecto')->name('foda-modelo-categoria-aspectos-crear');
    Route::resource('fodas', 'Admin\Planificacion\FodaController');
    Route::resource('foda-perfiles', 'Admin\Planificacion\Foda\FodaPerfilController');
    Route::resource('foda-analisis', 'Admin\Planificacion\Foda\FodaAnalisisController');
    Route::get('foda-ambiente-interno/{idCategoria}/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@categoriasAmbienteInterno')->name('foda-ambiente-interno');
    Route::get('foda-ambiente-externo/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@categoriasAmbienteExterno')->name('foda-ambiente-externo');
    Route::get('foda-aspectos-categoria/{idCategoria}/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@aspectosCategoria')->name('foda-aspectos-categoria');
    Route::get('foda-aspectos-categoria/{idCategoria}/{idPerfil}/edit', 'Admin\Planificacion\Foda\FodaAnalisisController@aspectosCategoriaEdit')->name('foda-aspectos-categoria-edit');
    Route::get('foda-analisis-asignar-aspectos/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@asignarAspectos')->name('ffoda-analisis-asignar-aspectos');
    Route::get('foda-listado-perfiles', 'Admin\Planificacion\Foda\FodaAnalisisController@listadoPerfiles')->name('foda-listado-perfiles');
    Route::get('foda-listado-categorias-aspectos/{idPerfil}', 'Admin\Planificacion\Foda\FodaAnalisisController@listadoCategoriaAspectos')->name('foda-listado-categorias-aspectos');
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
    Route::get('/foda-perfiles-modelo/{id}/categorias','Admin\Planificacion\Foda\FodaPerfilController@getCategorias');


    //Rutas del Monitoreo
    Route::resource('monitoreo-tipo_evaluaciones', 'Admin\Planificacion\Monitoreo\TipoEvaluacionController');
    Route::resource('monitoreo-evaluaciones', 'Admin\Planificacion\Monitoreo\EvaluacionController');
    
 
    
});
