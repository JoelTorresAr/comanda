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

Route::group(['namespace' => 'Tablet'], function () {

    Route::post('comanda/imprimir/cocina', 'TicketController@imprimirCocina');
    Route::post('comanda/imprimir/precuenta', 'TicketController@imprimirPrecuenta');
});
