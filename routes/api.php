<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;

use App\Http\Controllers\DocumentController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::group(['prefix' => 'appointment'], function () {
        Route::post('/create', [AppointmentController::class, 'add']);
        Route::post('/checkTimeframe', [AppointmentController::class, 'checkTimeframe']);

    });

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/document', [DocumentController::class, 'get']);
Route::get('/timeframe', [DocumentController::class, 'timeframe']);
Route::post('/appointment', [AppointmentController::class, 'add']);
Route::post('/checkTimeframe', [AppointmentController::class, 'checkTimeframe']);
