<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('cliente', 'App\Http\Controllers\ClienteController@getClientes');
Route::get('autor', 'App\Http\Controllers\AutorController@getAutor');
Route::get('libro', 'App\Http\Controllers\LibroController@getLibro');
Route::get('vencidos', 'App\Http\Controllers\PrestamosController@obtenerClientesConLibrosVencidos');
Route::get('prestamoSemana', 'App\Http\Controllers\PrestamosController@obtenerPrestamosPorSemana');
Route::get('prestamoMes', 'App\Http\Controllers\PrestamosController@obtenerPrestamosPorMes');
