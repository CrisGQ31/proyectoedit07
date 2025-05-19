<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
//use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AccionController;
use App\Http\Controllers\PermisoController;


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
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');

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
Route::get('/proveedores/data', [ProveedorController::class, 'getData'])->name('proveedores.data');



// Ruta para obtener los detalles de un proveedor para edición (GET)
Route::get('/proveedores/edit/{id}', [ProveedorController::class, 'edit'])->name('proveedores.edit');
// Ruta para crear un nuevo proveedor (POST)
Route::post('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
// Ruta para actualizar un proveedor (POST)
Route::post('/proveedores/update', [ProveedorController::class, 'update'])->name('proveedores.update');
// Ruta para activar/desactivar un proveedor (POST)
Route::post('/proveedores/toggle', [ProveedorController::class, 'toggle'])->name('proveedores.toggle');

////RUTAS PARA LOS EMPLEADOS
// Rutas para la gestión de empleados
Route::prefix('empleados')->group(function () {
    Route::get('/', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::get('/data', [EmpleadoController::class, 'data'])->name('empleados.data');
    Route::post('/store', [EmpleadoController::class, 'store'])->name('empleados.store'); // ← en lugar de "create"
    Route::post('/update', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::post('/toggle', [EmpleadoController::class, 'toggle'])->name('empleados.toggle');
    Route::get('/edit/{id}', [EmpleadoController::class, 'edit'])->name('empleados.edit');
});
//Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
////Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
//Route::get('/empleados/data', [EmpleadoController::class, 'data'])->name('empleados.data');
//Route::post('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
//Route::post('/empleados/update', [EmpleadoController::class, 'update'])->name('empleados.update');
//Route::post('/empleados/toggle', [EmpleadoController::class, 'toggle'])->name('empleados.toggle');
//Route::get('/empleados/edit/{id}', [EmpleadoController::class, 'edit'])->name('empleados.edit');



// Rutas para Acciones
    Route::get('acciones/', [App\Http\Controllers\AccionController::class, 'index'])->name('acciones.index');
    Route::get('/acciones/data', [App\Http\Controllers\AccionController::class, 'data'])->name('acciones.data');
    Route::post('/acciones/store', [App\Http\Controllers\AccionController::class, 'store'])->name('acciones.store');
    Route::get('/acciones/edit/{id}', [App\Http\Controllers\AccionController::class, 'edit']);
    Route::post('/acciones/toggle', [App\Http\Controllers\AccionController::class, 'toggle']);
    Route::delete('/acciones/delete/{id}', [AccionController::class, 'destroy']);

// Permisos
Route::get('/permisos', [App\Http\Controllers\PermisoController::class, 'index'])->name('permisos.index');
Route::get('/permisos/data', [App\Http\Controllers\PermisoController::class, 'data'])->name('permisos.data');
Route::post('/permisos/store', [App\Http\Controllers\PermisoController::class, 'store'])->name('permisos.store');
Route::get('/permisos/edit/{id}', [App\Http\Controllers\PermisoController::class, 'edit']);
Route::post('/permisos/toggle', [App\Http\Controllers\PermisoController::class, 'toggle']);
Route::delete('/permisos/delete/{id}', [App\Http\Controllers\PermisoController::class, 'destroy']);











//PRUEBA PARA REVISAR LAS TABLAS DE LA BD
use Illuminate\Support\Facades\DB;

Route::get('/check-db', function () {
    $tablas = DB::select('SHOW TABLES');
    return dd($tablas);
});


