<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::patch('users', [UserController::class, 'update']);
    Route::prefix('students/{student}')->group(function () {
        Route::post('import-data', [StudentController::class, 'importTargetData']);
        Route::post('generate-report', [StudentController::class, 'generateReport']);
    });
    Route::apiResource('students', StudentController::class)->only(['index', 'store']);
    Route::apiResource('sessions', SessionController::class)->except(['show', 'delete']);
});
