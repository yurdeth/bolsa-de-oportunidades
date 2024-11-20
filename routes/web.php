<?php

use App\Http\Middleware\NoBrowserCache;
use Illuminate\Support\Facades\Route;

// ********************************Rutas para rutas no definidas*************************************
Route::fallback(function () {
    return view('App');
});

Route::get('/{any}', function () {
    return view('App'); // La vista donde está montada la app Vue
})->where('any', '.*');

Route::middleware(['auth', NoBrowserCache::class])->group(function () {
    // *****************************************************************************************************
});
