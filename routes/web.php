<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('App'); // La vista donde está montada la app Vue
})->where('any', '.*');

