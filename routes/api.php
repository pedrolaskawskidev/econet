<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyEmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('companies', CompanyController::class);
Route::apiResource('employees', CompanyEmployeeController::class);
