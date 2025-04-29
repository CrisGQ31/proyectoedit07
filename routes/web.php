<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;

// CREAR EL PRIMER USUARIO ADMINISTRADOR
Route::get('/createUserAdmin', [UsersController::class, 'createUserAdmin']);

// RUTAS PARA ACCESO AL LOGIN
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginDefault');

// VALIDAR CREDENCIALES
Route::post('/validateUser', [AuthController::class, 'login'])->name('validateUser');

// CERRAR SESSION Y ENVIAR AL LOGIN
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logoutDefault');

// RUTAS PROTEGIDAS POR INICIO DE SESSION
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.dashboard');
    })->name('dashboard');

    Route::get('/welcome', function () {
        return view('modules.welcome');
    })->name('welcome');

    Route::get('/users', function () {
        return view('modules.users');
    })->name('users');
});