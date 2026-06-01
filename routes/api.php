<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\CollecteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCollecteController;
use App\Http\Controllers\AdminCoordinateurController;

// Public
Route::get('/entreprises/{slug}', [EntrepriseController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/collectes/{collecte}/nb_inscrits_estime', [CollecteController::class, 'incrementInscrits']);

// Authentifié (tous rôles)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Admin uniquement
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/stats', [AdminController::class, 'stats']);
    Route::get('/entreprises', [AdminController::class, 'entreprises']);
    Route::get('/collectes/{collecte}', [AdminCollecteController::class, 'show']);
    Route::get('/collectes', [AdminCollecteController::class, 'index']);
    Route::post('/collectes', [AdminCollecteController::class, 'store']);
    Route::put('/collectes/{collecte}', [AdminCollecteController::class, 'update']);
    Route::delete('/collectes/{collecte}', [AdminCollecteController::class, 'destroy']);
    Route::patch('/collectes/{collecte}/statut', [AdminCollecteController::class, 'updateStatut']);
    Route::get('/coordinateurs', [AdminCoordinateurController::class, 'index']);
    Route::post('/coordinateurs', [AdminCoordinateurController::class, 'store']);
    Route::put('/coordinateurs/{coordinateur}', [AdminCoordinateurController::class, 'update']);
    Route::delete('/coordinateurs/{coordinateur}', [AdminCoordinateurController::class, 'destroy']);
    Route::get('/coordinateurs/{coordinateur}', [AdminCoordinateurController::class, 'show']);
});
