<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inicio', function (){
    return 'Hola mundo';
});

Route::get('/login', function (){
    return 'login';
})->name('login');
