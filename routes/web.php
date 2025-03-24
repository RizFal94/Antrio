<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
});

Route::get('/ambil-antrian', function () {
    return view('ambilAntrian');
});

Route::get('/admin', function () {
    if (auth()->check()) {
        return view('csDashboard');
    }
    return redirect('/login');
})->name('admin');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); jangan dihapus dulu