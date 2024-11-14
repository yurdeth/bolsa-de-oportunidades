<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentsController;
use App\Http\Middleware\NoBrowserCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/students/nuevo', [StudentsController::class, 'store']);
Route::post('/auth', [AuthController::class, 'login']);

Route::middleware(['auth:api', NoBrowserCache::class])->group(function () {
    // Rutas para acceder a la información de los estudiantes.
    Route::get('/students/ver/', [StudentsController::class, 'index']);
    Route::get('/students/ver/{id}', [StudentsController::class, 'show']);
    Route::delete('/students/eliminar/{id}', [StudentsController::class, 'destroy']);
    Route::put('/students/actualizar/{id}', [StudentsController::class, 'update']);
    Route::patch('/students/editar/{id}', [StudentsController::class, 'partial']);
});
