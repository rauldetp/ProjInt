<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntrepriseController;

// Public
Route::get('/entreprises/{slug}', [EntrepriseController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);

// Authentifié (tous rôles)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
