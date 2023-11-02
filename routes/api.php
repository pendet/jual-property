<?php

use App\Http\Controllers\Api\V1\AdvertiseController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::post('advertises-edit/{advertise}', [AdvertiseController::class, 'update']);
        Route::apiResource('advertises', AdvertiseController::class);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/signup', [AuthController::class, 'sign_up']);
Route::post('/login', [AuthController::class, 'login']);