<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProposalController;

// --- Rotas de Autenticação e Públicas ---
Route::post('/users', [UserController::class, 'store']);
Route::post('/sessions', [SessionController::class, 'store']);
Route::get('/view/proposal/{hash}', [ProposalController::class, 'view']);

// --- Rotas Protegidas (precisam de autenticação) ---
Route::middleware('auth:api')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('proposals', ProposalController::class);
});