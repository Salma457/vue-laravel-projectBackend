<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\API\EmployerController;

/* PUBLIC ROUTES
-------------------------------------------------------*/
Route::post('/login', [AuthController::class, 'login']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

Route::get('/candidates', [CandidateController::class, 'index']);
Route::post('/candidates', [CandidateController::class, 'store']);

Route::get('/employers', [EmployerController::class, 'index']);
Route::post('/employers', [EmployerController::class, 'store']);


/* PROTECTED ROUTES (require Bearer token in headers)
------------------------------------------------------*/
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user(); // <current authenticated user>
    });

    Route::post('/logout', [AuthController::class, 'logout']);

});



// LOGIC:
/*
Route::middleware('auth:sanctum'):
makes sure Laravel auto checks Bearer token in the Authorization header.

If valid::::::: 
auth()->user()
$request->user(): 
will return the logged-in user.




*/