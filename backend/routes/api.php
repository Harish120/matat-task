<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(function () {
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('sync-orders', [OrderController::class, 'syncOrders']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
