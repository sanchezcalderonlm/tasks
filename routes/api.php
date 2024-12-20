<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});
Route::middleware(['auth:api'])->group(function(){
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
    Route::prefix('task')->group(function () {
        Route::post('', [TaskController::class, 'createTask']);
        Route::put('{task}', [TaskController::class, 'updateTask']);
        Route::delete('{task}', [TaskController::class, 'deleteTask']);
    });
});

