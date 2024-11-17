<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Route::fallback(function () {
//    return redirect()->route('iniciarSesion');
    return 'No se encontró la ruta';
});

// Rutas de autenticación
Route::get('/', [AuthController::class, 'index'])->name('iniciarSesion');
//Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/request-password-reset', [AuthController::class, 'requestPasswordReset'])->name('request-password-reset');


Route::get('/inicio', function (){
    return 'Hola mundo';
})->name('inicio');

Route::get('/login', function (){
    return 'login';
})->name('login');
