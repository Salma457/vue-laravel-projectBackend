<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\API\EmployerController;
use App\Http\Controllers\API\UsersjobController;
/* PUBLIC ROUTES
-------------------------------------------------------*/
Route::post('/login', [AuthController::class, 'login']); //login
//admin
// Route::middleware(['auth:sanctum', 'can:view-all-jobs'])->group(function () {
//     Route::get('/admin/jobs', [AdminController::class, 'index']); // New controller or method
// });
// users
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// candidates
Route::get('/candidates', [CandidateController::class, 'index']);
Route::post('/candidates', [CandidateController::class, 'store']);
Route::get('/candidates/{id}', [CandidateController::class, 'show']);

// employers
Route::get('/employers', [EmployerController::class, 'index']);
Route::post('/employers', [EmployerController::class, 'store']);

// for authintcation
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


////////////////////////

// Route::post('/employer/register', [EmployerController::class, 'register']);  //DELETED [SENU]

//login: I think it is just give me something to add in the header
// Route::post('/employer/login', [EmployerController::class, 'login']);        //DELETED [SENU]

// get the employer + update the profile
Route::middleware('auth:sanctum')->get('/employer/profile', [EmployerController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/employer/profile', [EmployerController::class, 'updateProfile']);

// for employer: 
Route::middleware('auth:sanctum')->prefix('employer')->group(function () {
    Route::get('jobs', [UsersjobController::class, 'index']);
    Route::post('jobs', [UsersjobController::class, 'store']);
    Route::get('jobs/{id}', [UsersjobController::class, 'show']);
    Route::put('jobs/{id}', [UsersjobController::class, 'update']);
    Route::delete('jobs/{id}', [UsersjobController::class, 'destroy']);
});
//admin routs

// Route::middleware('auth:sanctum')->post('/employer/logout', [EmployerController::class, 'logout']); //DELETED[SENU]

Route::middleware('auth:sanctum')->get('/employer/applications', [EmployerController::class, 'applications']);
Route::middleware('auth:sanctum')->put('/employer/applications/{id}', [EmployerController::class, 'updateApplicationStatus']);
Route::middleware('auth:sanctum')->delete('/employer/delete', [EmployerController::class, 'deleteAccount']);
Route::middleware('auth:sanctum')->post('/employer/change-password', [EmployerController::class, 'changePassword']);

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