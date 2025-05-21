<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContasController;
use App\Http\Controllers\TransacaoContasController;


Route::post('/register', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'showUser']);
    Route::post('/conta', [ContasController::class, 'store']);
    Route::get('/conta', [ContasController::class, 'show']);
    Route::post('/transacao', [TransacaoContasController::class, 'transaction']);
    Route::get('/transacao', [TransacaoContasController::class, 'getTransacaoContas']);
});
