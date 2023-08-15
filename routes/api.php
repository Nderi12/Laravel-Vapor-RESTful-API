<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::post('/registerUser', [AuthController::class, 'register']);
Route::post('/loginUser', [AuthController::class, 'login']);

// Route::group(['middleware' => 'auth:api'], function(){
    Route::resource('users', UserController::class)->only('index', 'store', 'show', 'update', 'destroy');
// });
