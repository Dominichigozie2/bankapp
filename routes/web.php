<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/register', [RegisterController::class, 'store'])->name('register.post');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');


Route::middleware(['auth'])->group(function () {
    Route::get('/account/dashboard', function () {
        return view('account.user.index');
    })->name('account.user.index');

    Route::get('/admin/dashboard', function () {
        return view('account.admin.index');
    })->name('admin.dashboard');
    
    Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


});
