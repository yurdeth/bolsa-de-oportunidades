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

Route::post('/students/new', [StudentsController::class, 'store'])->name('students.new');
Route::post('/companies/new', [CompanyController::class, 'store'])->name('companies.new');
Route::post('/managers/new', [ManagerController::class, 'store'])->name('managers.new');
Route::post('/auth', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['auth:api', NoBrowserCache::class])->group(function () {
    // Rutas para acceder a la información de los estudiantes.
    Route::get('/students/view/', [StudentsController::class, 'index'])->name('students.view');
    Route::get('/students/view/{id}', [StudentsController::class, 'show'])->name('students.show');
    Route::delete('/students/delete/{id}', [StudentsController::class, 'destroy'])->name('students.delete');
    Route::put('/students/update/{id}', [StudentsController::class, 'update'])->name('students.update');
    Route::patch('/students/edit/{id}', [StudentsController::class, 'partial'])->name('students.edit');

    // Rutas para acceder a la información de las empresas.
    Route::get('/companies/view/', [CompanyController::class, 'index'])->name('companies.view');
    Route::get('/companies/view/{id}', [CompanyController::class, 'show'])->name('companies.show');
    Route::delete('/companies/delete/{id}', [CompanyController::class, 'destroy'])->name('companies.delete');
    Route::put('/companies/update/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::patch('/companies/edit/{id}', [CompanyController::class, 'partial'])->name('companies.edit');

    // Rutas para acceder a la información de los coordinadores.
    Route::get('/managers/view/', [ManagerController::class, 'index'])->name('managers.view');
    Route::get('/managers/view/{id}', [ManagerController::class, 'show'])->name('managers.show');
    Route::delete('/managers/delete/{id}', [ManagerController::class, 'destroy'])->name('managers.delete');
    Route::put('/managers/update/{id}', [ManagerController::class, 'update'])->name('managers.update');
    Route::patch('/managers/edit/{id}', [ManagerController::class, 'partial'])->name('managers.edit');
});
