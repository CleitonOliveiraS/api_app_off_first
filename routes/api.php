<?php

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

});
Route::apiResource('/usuarios', \App\Http\Controllers\UsuarioControlador::class);
Route::apiResource('/setores', \App\Http\Controllers\SetorControlador::class);
