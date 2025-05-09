<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
//use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProveedorController;


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

//Route::middleware('auth')->group(function () {
//    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
//    Route::get('/proveedores/data', [ProveedorController::class, 'getData'])->name('proveedores.data');
//    Route::post('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
//    Route::post('/proveedores/update/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
//    Route::post('/proveedores/toggle/{id}', [ProveedorController::class, 'toggle'])->name('proveedores.toggle');
//});

// Ruta para el registro de un nuevo usuario
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('registerUser');

Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/data', [ProductController::class, 'getData'])->name('products.data');
    Route::post('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/update', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
    // Eliminar un producto
    Route::post('/products/delete', [ProductController::class, 'destroy'])->name('products.delete');
});


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

    Route::get('/proveedores', function () {
        return view('modules.proveedores');
    })->name('proveedores');
    ;

    // MODULO DE USUARIOS
    // DATATABLE
    Route::get('/users/data', [UsersController::class, 'usersDatas'])->name('users.data');
    // CRUD
    Route::post('/users/create', [UsersController::class, 'usersCreate'])->name('users.create');
    Route::post('/users/update', [UsersController::class, 'usersUpdate'])->name('users.update');
    Route::post('/users/deteleActive', [UsersController::class, 'usersDeleteActive'])->name('users.deleteactive');

});


// Ruta para mostrar la vista de proveedores
Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');

// Ruta para obtener los datos de los proveedores (DataTables)
Route::get('/proveedores/data', [ProveedorController::class, 'data'])->name('proveedores.data');

// Ruta para obtener los detalles de un proveedor para edición (GET)
Route::get('/proveedores/edit/{id}', [ProveedorController::class, 'edit'])->name('proveedores.edit');

// Ruta para crear un nuevo proveedor (POST)
Route::post('/proveedores/create', [ProveedorController::class, 'store'])->name('proveedores.create');

// Ruta para actualizar un proveedor (POST)
Route::post('/proveedores/update', [ProveedorController::class, 'update'])->name('proveedores.update');

// Ruta para activar/desactivar un proveedor (POST)
Route::post('/proveedores/toggle', [ProveedorController::class, 'toggle'])->name('proveedores.toggle');
