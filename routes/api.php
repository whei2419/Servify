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
        Route::post('/addQueue', [AppointmentController::class, 'addQueue']);
        Route::post('/generate', [AppointmentController::class, 'generateClearance']);
        Route::get('/queueList', [AppointmentController::class, 'queueList']);
        Route::post('/process', [AppointmentController::class, 'process']);
        Route::post('/appointmentList', [AppointmentController::class, 'appointmentList']);
        Route::post('/editAppointment', [AppointmentController::class, 'editAppointment']);

    });

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/document', [DocumentController::class, 'get']);
Route::get('/timeframe', [DocumentController::class, 'timeframe']);
Route::post('/appointment', [AppointmentController::class, 'add']);
Route::post('/checkTimeframe', [AppointmentController::class, 'checkTimeframe']);
