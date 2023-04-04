<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::controller(AuthController::class)->group(function () {
   Route::post('login', 'login');
   Route::post('register', 'register');
   Route::post('logout', 'logout');
   Route::post('refresh', 'refresh');
   Route::post('verify_otp', 'verify_otp');
});

Route::controller(KapalController::class)->group(function () {
   Route::post('kapal', 'store');
   Route::post('kapal/terima', 'terima');
   Route::put('kapal/{id}', 'update');
   Route::delete('kapal/{id}', 'destroy');
});

Route::controller(UserController::class)->group(function () {
   Route::get('user', 'index');
   Route::post('user/terima', 'terima');
   Route::put('user/{id}', 'update');
   Route::delete('user/{id}', 'destroy');
});
