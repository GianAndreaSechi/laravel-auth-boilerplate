<?php

use App\Http\Controllers\DataController;
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

Route::get('ping', function () {
    return response()->json(array('ping'=> now()));
});


Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>'users'], function() {
    Route::post('register', [UserController::class,'register'])->name('register');
    Route::post('login', [UserController::class,'authenticate'])->name ('login');
    Route::get('open', [DataController::class,'open'])->name('open');
});

Route::group(['prefix'=>'users', 'middleware' => ['jwt.verify']], function() {
    Route::get('', [UserController::class,'getAuthenticatedUser'])->name('user');
    Route::get('list', [UserController::class,'getUsers'])->name('users');
    Route::get('closed', [DataController::class,'closed'])->name('closed');
});


