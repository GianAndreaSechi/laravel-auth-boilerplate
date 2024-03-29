<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::middleware('jwt.verify')->get('/', function () {
//Route::middleware('auth.basic')->get('/', function () {ù
Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', function(){
    return view('login');
})->name('login');

Route::get('/logout', [AuthController::class,'logout'])->name('logout');


