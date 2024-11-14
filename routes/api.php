<?php

use App\Http\Controllers\StudentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/students', [StudentsController::class, 'index']);
Route::post('/students', [StudentsController::class, 'store']);
Route::get('/students/{id}', [StudentsController::class, 'show']);
Route::delete('/students/{id}', [StudentsController::class, 'destroy']);
Route::put('/students/{id}', [StudentsController::class, 'update']);
Route::patch('/students/{id}', [StudentsController::class, 'partial']);
