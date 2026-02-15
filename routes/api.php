<?php

use App\Http\Controllers\API\ActivityLogController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PotentialCustomerController;
use App\Models\PotentialCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login',[AuthController::class,'login']);

Route::post('/register-lead', function(Request $r){
    return PotentialCustomer::create($r->all());
});

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('leads',PotentialCustomerController::class);
    // Route::get('activity-logs',ActivityLogController::class);
});