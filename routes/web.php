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
    Route::resource('organigramas', 'Admin\OrganigramaController');
    Route::get('accesos', 'Admin\AccesoController@index')->name('accesos');
    
    //Rutas del Modulo FODA
    
    Route::view('fodas-dashboard', 'admin.fodas.index')->name('fodas-dashboard');
    Route::resource('foda-modelos', 'Admin\FodaModeloController');
    
    Route::resource('foda-categorias', 'Admin\FodaCategoriaController');
    Route::get('foda-modelo-categorias/{idModelo}', 'Admin\FodaCategoriaController@listadoCategorias')->name('foda-modelo-categorias');
    Route::get('foda-modelo-categoria-crear/{idModelo}', 'Admin\FodaCategoriaController@crearCategoria')->name('foda-modelo-categoria-crear');
    
    Route::get('perfil-categorias/{id}', 'Admin\FodaAnalisisController@listarCategorias')->name('perfil-categorias');
    Route::resource('foda-aspectos', 'Admin\FodaAspectoController');
    Route::get('foda-modelo-categoria-aspectos/{idModelo}/{idCategoria}', 'Admin\FodaModeloController@listadoAspectos')->name('foda-modelo-categoria-aspectos');
    Route::get('foda-modelo-categoria-aspectos-crear/{idCategoria}', 'Admin\FodaAspectoController@crearAspecto')->name('foda-modelo-categoria-aspectos-crear');
    
    Route::resource('fodas', 'Admin\FodaController');
    Route::resource('foda-perfiles', 'Admin\FodaPerfilController');
    
    
    
    Route::resource('foda-analisis', 'Admin\FodaAnalisisController');
    Route::get('foda-ponderaciones/{idAspecto}/{idPerfil}/ponderacion', 'Admin\FodaAnalisisController@ponderaciones')->name('foda-ponderaciones');
    Route::get('foda-ambiente-interno/{idCategoria}/{idPerfil}', 'Admin\FodaAnalisisController@categoriasAmbienteInterno')->name('foda-ambiente-interno');
    Route::get('foda-ambiente-externo/{idPerfil}', 'Admin\FodaAnalisisController@categoriasAmbienteExterno')->name('foda-ambiente-externo');
    Route::get('foda-aspectos-categoria/{idCategoria}/{idPerfil}', 'Admin\FodaAnalisisController@aspectosCategoria')->name('foda-aspectos-categoria');
    Route::get('foda-aspectos-categoria/{idCategoria}/{idPerfil}/edit', 'Admin\FodaAnalisisController@aspectosCategoriaEdit')->name('foda-aspectos-categoria-edit');
    Route::get('foda-analisis-asignar-aspectos/{idPerfil}', 'Admin\FodaAnalisisController@asignarAspectos')->name('ffoda-analisis-asignar-aspectos');
    // Route::post('foda-ponderar/{id}/ponderar', 'Admin\FodaAnalisisController@ponderar')->name('foda-ponderar');
    Route::get('foda-listado-perfiles', 'Admin\FodaAnalisisController@listadoPerfiles')->name('foda-listado-perfiles');
    Route::get('foda-listado-categorias-aspectos/{idPerfil}', 'Admin\FodaAnalisisController@listadoCategoriaAspectos')->name('foda-listado-categorias-aspectos');
    Route::get('foda-analisis-ambientes/{idPerfil}', 'Admin\FodaAnalisisController@seleccionarAmbiente')->name('foda-analisis-ambientes');
    Route::get('foda-analisis-ambiente-interno/{idPerfil}', 'Admin\FodaAnalisisController@analisisCategoriasAmbienteInterno')->name('foda-analisis-ambiente-interno');
    Route::get('foda-analisis-ambiente-externo/{idPerfil}', 'Admin\FodaAnalisisController@analisisCategoriasAmbienteExterno')->name('foda-analisis-ambiente-externo');
    Route::get('foda-analisis-aspectos/{idPerfil}', 'Admin\FodaAnalisisController@analisisAspectos')->name('foda-analisis-aspectos');
    Route::get('foda-analisis-listado-categoria-aspectos/{idCategoria}/{idPerfil}', 'Admin\FodaAnalisisController@listadoCategoriaAspectos')->name('foda-analisis-listado-categoria-aspectos');
    Route::get('foda-analisis-matriz/{idPerfil}', 'Admin\FodaAnalisisController@matriz')->name('foda-analisis-matriz');
    Route::get('foda-categoria-aspectos/{idCategoria}', 'Admin\FodaCategoriaController@listaAspectosCategoria')->name('foda-categoria-aspectos');
    Route::get('descargar-matriz-foda/{idPerfil}', 'Admin\FodaAnalisisController@matrizPdf')->name('matriz-foda.pdf');
    
    Route::get('foda-cruce-ambientes/{idPerfil}', 'Admin\FodaCruceAmbienteController@index')->name('foda-cruce-ambientes');
    Route::get('foda-cruce-pdf/{idPerfil}', 'Admin\FodaCruceAmbienteController@descargarCrucePdf')->name('foda-cruce-pdf');
    
    //Rutas Create 
    Route::get('foda-cruce-ambientes-fo/{idPerfil}', 'Admin\FodaCruceAmbienteController@FO')->name('foda-cruce-ambientes-fo');
    Route::get('foda-cruce-ambientes-do/{idPerfil}', 'Admin\FodaCruceAmbienteController@DO')->name('foda-cruce-ambientes-do');
    Route::get('foda-cruce-ambientes-fa/{idPerfil}', 'Admin\FodaCruceAmbienteController@FA')->name('foda-cruce-ambientes-fa');
    Route::get('foda-cruce-ambientes-da/{idPerfil}', 'Admin\FodaCruceAmbienteController@DA')->name('foda-cruce-ambientes-da');
    Route::resource('foda-cruce-ambientes', 'Admin\FodaCruceAmbienteController');
    
    
    Route::get('foda-aspectos-elegir-modelo', 'Admin\FodaAspectoController@elegirModelo')->name('foda-aspectos-elegir-modelo');
    
    Route::get('/foda-perfiles-modelo/{id}/categorias','Admin\FodaPerfilController@getCategorias');
 
    
});
