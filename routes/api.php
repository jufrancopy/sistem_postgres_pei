<?php

use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')
     ->get('/user', function (Request $request) {
         return $request->user();
 });

 Route::get('/patrimonies', 'Admin\Globales\PatrimonyController@mapPais');


// Route::group(['middleware' => ['auth', 'api']], function () {
//     Route::get('tthh', function(){
//         return datatables()
//             ->eloquent(App\Admin\Proyecto\EPC\TalentoHumano::query())
//             ->toJson();
//     });
// });

// Route::resource('movies', 'MovieController');

Route::get('tthh', function(){
     return datatables()
        ->eloquent(App\Admin\Proyecto\EPC\TalentoHumano::query())
        ->toJson();
});

// Ruta API pública para replicación de pantalla IPS desde Bookmarklet
Route::post('/mecip/importar-json', [\App\Http\Controllers\Admin\Mecip\MecipControlController::class, 'importarJson']);
Route::options('/mecip/importar-json', function() {
    return response()->json(['ok' => true], 200, [
        'Access-Control-Allow-Origin'  => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
    ]);
});
