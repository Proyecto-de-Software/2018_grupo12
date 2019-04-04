<?php

use Illuminate\Http\Request;

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

Route::fallback(function(){
  return response()->json(['error'=>"Recurso no encontrado"],404);
});

Route::get('/instituciones',"ApiController@index");

Route::get('/instituciones/{id}',"ApiController@institucion");

Route::get('/instituciones/region-sanitaria/{region}',"ApiController@institucionesRegion");

Route::post('/instituciones',"ApiController@store");

Route::put('/instituciones/{id}',"ApiController@update");

Route::get('/tipo-instituciones',"ApiController@tiposDeInstituciones");
