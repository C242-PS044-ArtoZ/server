<?php

  use App\Http\Controllers\Api\AuthController;
  use App\Http\Controllers\Api\TransactionController;
  use Illuminate\Support\Facades\Route;

  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login', [AuthController::class, 'login']);

  Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('transactions', TransactionController::class);

    Route::get('/transactions-summary', [TransactionController::class, 'summary']);
  });
