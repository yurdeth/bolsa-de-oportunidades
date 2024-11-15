<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
//    return redirect()->route('iniciarSesion');
    return 'No se encontró la ruta';
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inicio', function (){
    return 'Hola mundo';
});

Route::get('/login', function (){
    return 'login';
})->name('login');
