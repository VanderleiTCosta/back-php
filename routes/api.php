<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProposalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// --- Rotas de Autenticação e Públicas ---
Route::post('/users', [UserController::class, 'store']);
Route::post('/sessions', [SessionController::class, 'store']);
Route::get('/view/proposal/{hash}', [ProposalController::class, 'view']);

// --- Rotas Protegidas (precisam de autenticação) ---
Route::middleware('auth:api')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('proposals', ProposalController::class);
});