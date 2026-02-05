<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/wisudawan', [App\Http\Controllers\Api\WisudawanController::class, 'index'])->name('api.wisudawan.index');
