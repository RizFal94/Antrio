<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\CustomerController;

//utama
Route::resource('/tampilkan-service', ServiceController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::post("/logout", [AuthController::class, "logout"])->middleware("auth:sanctum");

//admin
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index']);
    Route::post('/tambah-user', [AdminController::class, 'addUser']);
    Route::delete('/hapus-user/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/tambah-service', [AdminController::class, 'storeService']);
    Route::put('/update-service/{id}', [AdminController::class, 'updateService']);
    Route::delete('/hapus-service/{id}', [AdminController::class, 'deleteService']);
    Route::get('/antrian/belum-terlayani', [AdminController::class, 'showMenunggu']);
    Route::get('/antrian/dilayani', [AdminController::class, 'showDilayani']);
    Route::get('/antrian/terlayani', [AdminController::class, 'showSelesai']);
    Route::get('/antrian/dilewati', [AdminController::class, 'showSkip']);
});

//cs
Route::prefix('customer-service')->group(function () {
    Route::get('/all', [CustomerServiceController::class, 'showCustomerServices']);
    Route::post('/aktifkan/{id}', [CustomerServiceController::class, 'aktifkan'])->middleware('auth:sanctum');
    Route::post('/non-aktifkan/{id}', [CustomerServiceController::class, 'nonaktifkan'])->middleware('auth:sanctum');
    Route::post('/antrian/berikutnya', [CustomerServiceController::class, 'ambilBerikutnya'])->middleware('auth:sanctum');
    Route::post('/antrian/skip/{id}', [CustomerServiceController::class, 'skip'])->middleware('auth:sanctum');
    Route::post('/antrian/selesai/{id}', [CustomerServiceController::class, 'selesai'])->middleware('auth:sanctum');
});

//umum
Route::post('/ambil-antrian', [CustomerController::class, 'ambilAntrian']);
Route::get('/antrian/belum-terlayani', [CustomerController::class, 'showMenunggu']);
Route::get('/antrian/dilayani', [CustomerController::class, 'showDilayani']);
Route::get('/antrian/terlayani', [CustomerController::class, 'showSelesai']);
Route::get('/antrian/dilewati', [CustomerController::class, 'showSkip']);
