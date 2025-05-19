<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmployerController;
use App\Http\Controllers\API\UsersjobController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/employer/register', [EmployerController::class, 'register']);

Route::post('/employer/login', [EmployerController::class, 'login']);
Route::middleware('auth:sanctum')->get('/employer/profile', [EmployerController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/employer/profile', [EmployerController::class, 'updateProfile']);

Route::middleware('auth:sanctum')->prefix('employer')->group(function () {
    Route::get('jobs', [UsersjobController::class, 'index']);
    Route::post('jobs', [UsersjobController::class, 'store']);
    Route::get('jobs/{id}', [UsersjobController::class, 'show']);
    Route::put('jobs/{id}', [UsersjobController::class, 'update']);
    Route::delete('jobs/{id}', [UsersjobController::class, 'destroy']);
});
Route::middleware('auth:sanctum')->post('/employer/logout', [EmployerController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/employer/applications', [EmployerController::class, 'applications']);
Route::middleware('auth:sanctum')->put('/employer/applications/{id}', [EmployerController::class, 'updateApplicationStatus']);
Route::middleware('auth:sanctum')->delete('/employer/delete', [EmployerController::class, 'deleteAccount']);
Route::middleware('auth:sanctum')->post('/employer/change-password', [EmployerController::class, 'changePassword']);

