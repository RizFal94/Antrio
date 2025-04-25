<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\CustomerController;

Route::resource('/service', ServiceController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::post("/logout", [AuthController::class, "logout"])->middleware("auth:sanctum");

Route::prefix('customer-service')->group(function () {
    Route::get('/all', [CustomerServiceController::class, 'showCustomerServices']);
    Route::post('/aktifkan/{id}', [CustomerServiceController::class, 'aktifkan'])->middleware('auth:sanctum');
    Route::get('/antrian/berikutnya', [CustomerServiceController::class, 'ambilBerikutnya']);
    Route::post('/antrian/skip/{id}', [CustomerServiceController::class, 'skip']);
    Route::post('/antrian/selesai/{id}', [CustomerServiceController::class, 'selesai']);
});

Route::post('/ambil-antrian', [CustomerController::class, 'ambilAntrian']);
Route::get('/antrian/belum-terlayani', [CustomerServiceController::class, 'showMenunggu']);
Route::get('/antrian/sudah-terlayani', [CustomerServiceController::class, 'showSelesai']);
Route::get('/antrian/dilewati', [CustomerServiceController::class, 'showSkip']);
