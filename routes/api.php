<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StudentsController;
use App\Http\Middleware\NoBrowserCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/students/new', [StudentsController::class, 'store']);
Route::post('/companies/new', [CompanyController::class, 'store']);
Route::post('/managers/new', [ManagerController::class, 'store']);
Route::post('/auth', [AuthController::class, 'login']);

Route::middleware(['auth:api', NoBrowserCache::class])->group(function () {
    // Rutas para acceder a la información de los estudiantes.
    Route::get('/students/view/', [StudentsController::class, 'index']);
    Route::get('/students/view/{id}', [StudentsController::class, 'show']);
    Route::delete('/students/delete/{id}', [StudentsController::class, 'destroy']);
    Route::put('/students/update/{id}', [StudentsController::class, 'update']);
    Route::patch('/students/edit/{id}', [StudentsController::class, 'partial']);

    // Rutas para acceder a la información de las empresas.
    Route::get('/companies/view/', [CompanyController::class, 'index']);
    Route::get('/companies/view/{id}', [CompanyController::class, 'show']);
    Route::delete('/companies/delete/{id}', [CompanyController::class, 'destroy']);
    Route::put('/companies/update/{id}', [CompanyController::class, 'update']);
    Route::patch('/companies/edit/{id}', [CompanyController::class, 'partial']);

    // Rutas para acceder a la información de los coordinadores.
    Route::get('/managers/view/', [ManagerController::class, 'index']);
    Route::get('/managers/view/{id}', [ManagerController::class, 'show']);
    Route::delete('/managers/delete/{id}', [ManagerController::class, 'destroy']);
    Route::put('/managers/update/{id}', [ManagerController::class, 'update']);
    Route::patch('/managers/edit/{id}', [ManagerController::class, 'partial']);
});
