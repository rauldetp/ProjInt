<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Auth publique
Route::post('/login', [AuthController::class, 'login']);

// Routes coordinateur
Route::middleware(['auth:sanctum', 'coordinateur'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
