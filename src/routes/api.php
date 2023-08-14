<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PingController;
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

Route::get('ping', [PingController::class,'ping'])->name('ping');


Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});



Route::group(['prefix'=>''], function() {
    Route::post('register', [AuthController::class,'register'])->name('register');
    Route::post('login', [AuthController::class,'authenticate'])->name ('login_api');
    Route::post('logout', [AuthController::class,'logout'])->name ('logout');

    Route::get('open', [DataController::class,'open'])->name('open');
});

Route::group(['prefix'=>'users', 'middleware' => ['jwt.verify']], function() {
    Route::get('', [UserController::class,'getAuthenticatedUser'])->name('user');

    Route::post('update/', [UserController::class,'updatePassword'])->name('updatePassword');

    Route::get('id/{id}', [UserController::class,'getUserById'])->name('Userid');
    Route::get('email/{email}', [UserController::class,'getUserByEmail'])->name('UserEmail');

    Route::get('list', [UserController::class,'getUsers'])->name('users');

    Route::get('closed', [DataController::class,'closed'])->name('closed');
});


