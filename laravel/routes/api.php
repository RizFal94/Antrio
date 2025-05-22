<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\CustomerController;

Route::resource('/service', ServiceController::class);

//utama
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::post("/logout", [AuthController::class, "logout"])->middleware("auth:sanctum");

//admin
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index']);
    Route::post('/tambah-service', [AdminController::class, 'storeService']);
    Route::put('/service/{id}', [AdminController::class, 'updateService']);
    Route::delete('/service/{id}', [AdminController::class, 'deleteService']);
});

//cs
Route::prefix('customer-service')->group(function () {
    Route::get('/all', [CustomerServiceController::class, 'showCustomerServices']);
    Route::post('/aktifkan/{id}', [CustomerServiceController::class, 'aktifkan'])->middleware('auth:sanctum');
    Route::post('/non-aktifkan/{id}', [CustomerServiceController::class, 'nonaktifkan'])->middleware('auth:sanctum');
    Route::get('/antrian/berikutnya', [CustomerServiceController::class, 'ambilBerikutnya']);
    Route::post('/antrian/skip/{id}', [CustomerServiceController::class, 'skip']);
    Route::post('/antrian/selesai/{id}', [CustomerServiceController::class, 'selesai']);
});

//umum
Route::post('/ambil-antrian', [CustomerController::class, 'ambilAntrian']);
Route::get('/antrian/belum-terlayani', [CustomerServiceController::class, 'showMenunggu']);
Route::get('/antrian/sudah-terlayani', [CustomerServiceController::class, 'showSelesai']);
Route::get('/antrian/dilewati', [CustomerServiceController::class, 'showSkip']);
