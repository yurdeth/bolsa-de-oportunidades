<?php

use App\Http\Controllers\CoordinadoresController;
use App\Http\Controllers\DepartamentosController;
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

//Route::resource('/departamentos', 'App\Http\Controllers\DepartamentosController', ['except' => ['edit', 'create']]);
Route::get('/departamentos', [DepartamentosController::class, 'index'])->name('departamentos.index');
Route::get('/departamentos/{id}', [DepartamentosController::class, 'index'])->name('departamentos.show');
Route::resource('/carreras', 'App\Http\Controllers\CarrerasController', ['except' => ['edit', 'create']]);
Route::resource('/empresas', 'App\Http\Controllers\EmpresasController', ['except' => ['edit', 'create']]);
Route::resource('/sectores_industria', 'App\Http\Controllers\SectoresIndustriaController', ['except' => ['edit', 'create']]);
//Route::resource('coordinadores', 'App\Http\Controllers\CoordinadoresController', ['except' => ['edit', 'create']]);
Route::resource('modalidades_trabajo', 'App\Http\Controllers\ModalidadesTrabajoController', ['except' => ['edit', 'create']]);
Route::resource('tipos_proyecto', 'App\Http\Controllers\TiposProyectoController', ['except' => ['edit', 'create']]);
Route::resource('estados_oferta', 'App\Http\Controllers\EstadosOfertaController', ['except' => ['edit', 'create']]);
Route::resource('estudiantes', 'App\Http\Controllers\EstudiantesController', ['except' => ['edit', 'create']]);
Route::resource('proyectos', 'App\Http\Controllers\ProyectosController', ['except' => ['edit', 'create']]);
Route::resource('aplicaciones', 'App\Http\Controllers\AplicacionesController', ['except' => ['edit', 'create']]);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/access_token', [AuthController::class, 'access_token']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/departamentos', [DepartamentosController::class, 'store'])->name('departamentos.store');
    Route::put('/departamentos/{id}', [DepartamentosController::class, 'update'])->name('departamentos.update');
    Route::delete('/departamentos/{id}', [DepartamentosController::class, 'destroy'])->name('departamentos.destroy');

    Route::get('/coordinadores', [CoordinadoresController::class, 'index'])->name('coordinadores.index');
    Route::get('/coordinadores/{id}', [CoordinadoresController::class, 'show'])->name('coordinadores.show');
    Route::post('/coordinadores', [CoordinadoresController::class, 'store'])->name('coordinadores.store');
    Route::put('/coordinadores/{id}', [CoordinadoresController::class, 'update'])->name('coordinadores.update');
    Route::delete('/coordinadores/{id}', [CoordinadoresController::class, 'destroy'])->name('coordinadores.destroy');
});
