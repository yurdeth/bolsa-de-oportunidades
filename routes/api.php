<?php

use App\Http\Controllers\AplicacionesController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\CoordinadoresController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentosController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\EstadosOfertaController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\ModalidadesTrabajoController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\SectoresIndustriaController;
use App\Http\Controllers\TiposProyectoController;
use App\Http\Middleware\NoBrowserCache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Bienvenido a la API de Bolsa de Oportunidades FMO UES'
    ]);
});


Route::get('proyectos/empresa', [ProyectosController::class, 'indexBelongs'])->name('proyectos.belongs');
Route::get('/empresas/proyecto/{id}', [EmpresasController::class, 'showByProyecto'])->name('empresas.find');

//-------------------------------------------------------------------------
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

//-------------------------------------------------------------------------
Route::get('/departamentos', [DepartamentosController::class, 'index'])->name('departamentos.index');
Route::get('/departamentos/{id}', [DepartamentosController::class, 'show'])->name('departamentos.show');

//-------------------------------------------------------------------------
Route::get('/carreras', [CarrerasController::class, 'index'])->name('carreras.index');
Route::get('/carreras/{id}', [CarrerasController::class, 'show'])->name('carreras.show');
Route::get('/departamentos/carreras/{id}', [CarrerasController::class, 'getCarrerasByDepartamento'])->name('carreras.by.departamento');

//-------------------------------------------------------------------------
Route::get('/sectores-industria', [SectoresIndustriaController::class, 'index'])->name('sectores.index');
Route::get('/sectores-industria/{id}', [SectoresIndustriaController::class, 'show'])->name('sectores.show');

//-------------------------------------------------------------------------
Route::get('/modalidades-trabajo', [ModalidadesTrabajoController::class, 'index'])->name('modalidades.index');
Route::get('/modalidades-trabajo/{id}', [ModalidadesTrabajoController::class, 'show'])->name('modalidades.show');

//-------------------------------------------------------------------------
Route::get('/tipos-proyecto', [TiposProyectoController::class, 'index'])->name('tipos.proyectos.index');
Route::get('/tipos-proyecto/{id}', [TiposProyectoController::class, 'show'])->name('tipos.proyectos.show');

//-------------------------------------------------------------------------
Route::get('/estado-oferta', [EstadosOfertaController::class, 'index'])->name('estado.oferta.index');
Route::get('/estado-oferta/{id}', [EstadosOfertaController::class, 'show'])->name('estado.oferta.show');


/*Route::resource('proyectos', 'App\Http\Controllers\ProyectosController', ['except' => ['edit', 'create']]);
Route::resource('aplicaciones', 'App\Http\Controllers\AplicacionesController', ['except' => ['edit', 'create']]);*/

Route::group(['middleware' => 'auth:api', NoBrowserCache::class], function () {
    //-------------------------------------------------------------------------
    Route::post('/access_token', [AuthController::class, 'access_token']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    //-------------------------------------------------------------------------
    Route::post('/departamentos', [DepartamentosController::class, 'store'])->name('departamentos.store');
    Route::patch('/departamentos/{id}', [DepartamentosController::class, 'update'])->name('departamentos.update');
    Route::delete('/departamentos/{id}', [DepartamentosController::class, 'destroy'])->name('departamentos.destroy');

    //-------------------------------------------------------------------------
    Route::post('/carreras', [CarrerasController::class, 'store'])->name('carreras.store');
    Route::patch('/carreras/{id}', [CarrerasController::class, 'update'])->name('carreras.update');
    Route::delete('/carreras/{id}', [CarrerasController::class, 'destroy'])->name('carreras.destroy');

    //-------------------------------------------------------------------------
    Route::get('/coordinadores', [CoordinadoresController::class, 'index'])->name('coordinadores.index');
    Route::get('/coordinadores/{id}', [CoordinadoresController::class, 'show'])->name('coordinadores.show');
    Route::post('/coordinadores', [CoordinadoresController::class, 'store'])->name('coordinadores.store');
    Route::patch('/coordinadores/{id}', [CoordinadoresController::class, 'update'])->name('coordinadores.update');
    Route::delete('/coordinadores/{id}', [CoordinadoresController::class, 'destroy'])->name('coordinadores.destroy');

    //-------------------------------------------------------------------------
    Route::get('/empresas', [EmpresasController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/{id}', [EmpresasController::class, 'show'])->name('empresas.show');
    Route::patch('/empresas/{id}', [EmpresasController::class, 'update'])->name('empresas.update');
    Route::delete('/empresas/{id}', [EmpresasController::class, 'destroy'])->name('empresas.destroy');

    //-------------------------------------------------------------------------
    Route::get('/estudiantes', [EstudiantesController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/{id}', [EstudiantesController::class, 'show'])->name('estudiantes.show');
    Route::patch('/estudiantes/{id}', [EstudiantesController::class, 'update'])->name('estudiantes.update');
    Route::delete('/estudiantes/{id}', [EstudiantesController::class, 'destroy'])->name('estudiantes.destroy');

    //-------------------------------------------------------------------------
    Route::post('/sectores-industria/{id}', [SectoresIndustriaController::class, 'store'])->name('sectores.store');
    Route::patch('/sectores-industria/{id}', [SectoresIndustriaController::class, 'update'])->name('sectores.update');
    Route::delete('/sectores-industria/{id}', [SectoresIndustriaController::class, 'destroy'])->name('sectores.destroy');

    //-------------------------------------------------------------------------
    Route::post('/modalidades-trabajo/{id}', [ModalidadesTrabajoController::class, 'store'])->name('modalidades.store');
    Route::patch('/modalidades-trabajo/{id}', [ModalidadesTrabajoController::class, 'update'])->name('modalidades.update');
    Route::delete('/modalidades-trabajo/{id}', [ModalidadesTrabajoController::class, 'destroy'])->name('modalidades.destroy');

    //-------------------------------------------------------------------------
    Route::post('/tipos-proyecto/{id}', [TiposProyectoController::class, 'store'])->name('tipos.proyectos.store');
    Route::patch('/tipos-proyecto/{id}', [TiposProyectoController::class, 'update'])->name('tipos.proyectos.update');
    Route::delete('/tipos-proyecto/{id}', [TiposProyectoController::class, 'destroy'])->name('tipos.proyectos.destroy');

    //-------------------------------------------------------------------------
    Route::post('/estado-oferta/{id}', [EstadosOfertaController::class, 'store'])->name('estado.oferta.store');
    Route::patch('/estado-oferta/{id}', [EstadosOfertaController::class, 'update'])->name('estado.oferta.update');
    Route::delete('/estado-oferta/{id}', [EstadosOfertaController::class, 'destroy'])->name('estado.oferta.destroy');

    //-------------------------------------------------------------------------
    Route::get('/proyectos', [ProyectosController::class, 'index'])->name('proyectos.index');
    Route::get('/proyectos/{id}', [ProyectosController::class, 'show'])->name('proyectos.show');
    Route::get('/proyectos/interesados/{id}', [ProyectosController::class, 'getInteresados'])->name('proyectos.interesados');
    Route::post('/proyectos', [ProyectosController::class, 'store'])->name('proyectos.store');
    Route::patch('/proyectos/{id}', [ProyectosController::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{id}', [ProyectosController::class, 'destroy'])->name('proyectos.destroy');
    Route::get('/proyectos/empresa/{id}', [ProyectosController::class, 'findByEmpresa'])->name('proyectos.find');

    //-------------------------------------------------------------------------
    Route::get('/aplicaciones', [AplicacionesController::class, 'index'])->name('aplicaciones.index');
    Route::get('/aplicaciones/{id}', [AplicacionesController::class, 'show'])->name('aplicaciones.show');
    Route::post('/aplicaciones', [AplicacionesController::class, 'store'])->name('aplicaciones.store');
    Route::patch('/aplicaciones/{id}', [AplicacionesController::class, 'update'])->name('aplicaciones.update');
    Route::delete('/aplicaciones/{id}', [AplicacionesController::class, 'destroy'])->name('aplicaciones.destroy');
    Route::get('/aplicaciones/estudiante/{id}', [AplicacionesController::class, 'findByEstudiante'])->name('aplicaciones.find');
});
