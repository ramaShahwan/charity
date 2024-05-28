<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\CenterController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BillController;



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
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});


// class
// for dashboard
Route::get('/classes', [ClassController::class, 'index']);

// for home website
Route::get('/classes_projects', [ClassController::class, 'get_class_with_project']);

// for dashboard
Route::post('/classes', [ClassController::class, 'store']);

Route::post('/classes/{id}', [ClassController::class, 'update']);
Route::post('/class/{id}', [ClassController::class, 'destroy']);



// project
// for dashboard
Route::get('/projects', [ProjectController::class, 'index']);


Route::get('/projectForClass/{class_id}', [ProjectController::class, 'get_project_for_class']);

// for dashboard
Route::post('/projects', [ProjectController::class, 'store']);

Route::post('/projects/{id}', [ProjectController::class, 'update']);
Route::post('/project/{id}', [ProjectController::class, 'destroy']);

Route::get('/statistic/{project_id}', [ProjectController::class, 'statistic']);



// bank
Route::get('/banks', [BankController::class, 'index']);
Route::post('/banks', [BankController::class, 'store']);

Route::post('/banks/{id}', [BankController::class, 'update']);
Route::post('/bank/{id}', [BankController::class, 'destroy']);



//users
Route::get('/donation', [UserController::class, 'show_donation']);
Route::get('/benifit', [UserController::class, 'show_benifit']);
Route::post('/users', [UserController::class, 'store_benifit']);
Route::post('/donation/{project_id}', [UserController::class, 'store_donation']);

Route::post('/users/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);


Route::post('/register_project/{id}', [UserController::class, 'register_project']);



//centers
Route::get('/centers', [CenterController::class, 'show']);
Route::post('/centers', [CenterController::class, 'store']);
Route::post('/centers/{id}', [CenterController::class, 'update']);
Route::delete('/center/{id}', [CenterController::class, 'destroy']);



//bills
Route::get('/bills/{bank_id}', [BillController::class, 'get']);
