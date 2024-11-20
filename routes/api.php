<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Bienvenido a la API de Bolsa de Oportunidades FMO UES'
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::resource('/departamentos', 'App\Http\Controllers\DepartamentosController', ['except' => ['edit', 'create']]);
Route::resource('/carreras', 'App\Http\Controllers\CarrerasController', ['except' => ['edit', 'create']]);
Route::resource('/empresas', 'App\Http\Controllers\EmpresasController', ['except' => ['edit', 'create']]);
Route::resource('/sectores_industria', 'App\Http\Controllers\SectoresIndustriaController', ['except' => ['edit', 'create']]);
Route::resource('coordinadores', 'App\Http\Controllers\CoordinadoresController', ['except' => ['edit', 'create']]);
Route::resource('modalidades_trabajo', 'App\Http\Controllers\ModalidadesTrabajoController', ['except' => ['edit', 'create']]);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/access_token', [AuthController::class, 'access_token']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
