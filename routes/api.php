<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    // Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});


// class
Route::get('/classes', [ClassController::class, 'index']);
Route::post('/classes', [ClassController::class, 'store']);

Route::post('/classes/{id}', [ClassController::class, 'update']);
Route::post('/class/{id}', [ClassController::class, 'destroy']);


// project
Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);

Route::post('/projects/{id}', [ProjectController::class, 'update']);
Route::post('/project/{id}', [ProjectController::class, 'destroy']);


// bank
Route::get('/banks', [BankController::class, 'index']);
Route::post('/banks', [BankController::class, 'store']);

Route::post('/banks/{id}', [BankController::class, 'update']);
Route::post('/bank/{id}', [BankController::class, 'destroy']);


