<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Default Sanctum Route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    //Dashboard
    Route::get('/stats', [ActivityController::class, 'stats']);
    Route::get('/activbycat/{id}', [ActivityController::class, 'getactivitybycategory']);

    //CRUD
    Route::resource('/category', CategoryController::class)->except('create', 'edit');
    Route::resource('/activity', ActivityController::class)->except('create', 'edit');

    //Accout Management
    Route::put('/user/{id}', [AuthController::class, 'update']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/showuser/{id}', [AuthController::class, 'show']);









// Default For Studying

//CRUD Category Route
// Route::get('/category', [CategoryController::class, 'index']);
// Route::post('/category',[CategoryController::class, 'store']);
// Route::put('/category/{id}',[CategoryController::class, 'update']);
// Route::get('/category/{id}',[CategoryController::class, 'show']);
// Route::delete('/category/{id}',[CategoryController::class, 'destroy']);
