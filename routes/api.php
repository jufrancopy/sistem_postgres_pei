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
