<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'orders',], function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('sync', [OrderController::class, 'syncOrders']);
    });

    Route::get('/user', [AuthController::class, 'user']);
});
