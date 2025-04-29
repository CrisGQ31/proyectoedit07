<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('/createUserAdmin', [UsersController::class, 'createUserAdmin']);

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta protegida para el dashboard
Route::get('/dashboard', function () {
    return view('layouts/dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/welcome', function () {
    return view('modules.welcome');
})->name('welcome');

Route::get('/users', function () {
    return view('modules.users');
})->name('users');