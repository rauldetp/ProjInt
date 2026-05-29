<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\CollecteController;

// Public
Route::get('/entreprises/{slug}', [EntrepriseController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/collectes/{collecte}/nb_inscrits_estime', [CollecteController::class, 'incrementInscrits']);

// Authentifié (tous rôles)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
