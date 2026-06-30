<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyEmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout']);

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('employees', CompanyEmployeeController::class);
    });
});
