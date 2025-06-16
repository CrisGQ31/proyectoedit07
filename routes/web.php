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
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\TipoJuicioController;
use App\Http\Controllers\TipoEstatusController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\CarpetaController;
use App\Http\Controllers\PermisosUsuariosController;

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

    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/data/{activo}', [UsersController::class, 'data'])->name('data');
        Route::post('/store', [UsersController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
        Route::post('/toggle/{id}', [UsersController::class, 'toggle'])->name('toggle');
    });

//    Route::prefix('usuarios')->name('usuarios.')->group(function () {
//        Route::get('/', [UsersController::class, 'index'])->name('index');
//        Route::get('/data', [UsersController::class, 'data'])->name('data');
//        Route::get('/get', [UsersController::class, 'get'])->name('get');
//        Route::post('/store', [UsersController::class, 'store'])->name('store');
//        Route::post('/update', [UsersController::class, 'update'])->name('update');
//        Route::post('/toggle', [UsersController::class, 'toggle'])->name('toggle');
//    });


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
Route::prefix('acciones')->group(function () {
    Route::get('/', [AccionController::class, 'index'])->name('acciones.index');            // Vista principal
    Route::get('/data', [AccionController::class, 'data'])->name('acciones.data');          // DataTables (activo/inactivo)
    Route::post('/store', [AccionController::class, 'store'])->name('acciones.store');      // Crear acción
    Route::get('/edit/{id}', [AccionController::class, 'edit'])->name('acciones.edit');     // Obtener datos para editar
    Route::post('/update/{id}', [AccionController::class, 'update'])->name('acciones.update'); // Actualizar acción
    Route::post('/toggle', [AccionController::class, 'toggle'])->name('acciones.toggle');   // Activar/Desactivar
    Route::delete('/delete/{id}', [AccionController::class, 'destroy'])->name('acciones.destroy'); // Eliminar
});


// Permisos
Route::get('permisos', [PermisoController::class, 'index'])->name('permisos.index');
Route::get('permisos/data', [PermisoController::class, 'data'])->name('permisos.data');
Route::post('permisos/store', [PermisoController::class, 'store'])->name('permisos.store');
Route::get('permisos/edit/{id}', [PermisoController::class, 'edit']);
Route::put('permisos/update/{id}', [PermisoController::class, 'update']);
Route::post('permisos/toggle', [PermisoController::class, 'toggle']);

//solicitantes
Route::get('solicitantes', [SolicitanteController::class, 'index'])->name('solicitantes.index');
Route::get('solicitantes/data', [SolicitanteController::class, 'data'])->name('solicitantes.data');
Route::post('solicitantes/store', [SolicitanteController::class, 'store'])->name('solicitantes.store');
Route::get('solicitantes/{id}/edit', [SolicitanteController::class, 'edit']);
Route::put('solicitantes/update/{id}', [SolicitanteController::class, 'update']);
//Route::post('solicitantes/toggle/{id}', [SolicitanteController::class, 'toggle']);
Route::post('solicitantes/toggle', [SolicitanteController::class, 'toggle']);


//tipo de juicio
Route::get('tipojuicio', [TipoJuicioController::class, 'index'])->name('tipojuicio.index');
Route::get('tipojuicio/data/{estado}', [TipoJuicioController::class, 'data'])->name('tipojuicio.data');
Route::post('tipojuicio/store', [TipoJuicioController::class, 'store'])->name('tipojuicio.store');
Route::put('tipojuicio/update/{id}', [TipoJuicioController::class, 'update'])->name('tipojuicio.update');
Route::get('tipojuicio/{id}/edit', [TipoJuicioController::class, 'edit']);
Route::post('tipojuicio/toggle/{id}', [TipoJuicioController::class, 'toggle']);

//Tipo de estatus
Route::get('tipoestatus', [TipoEstatusController::class, 'index'])->name('tipoestatus.index');
Route::get('tipoestatus/data', [TipoEstatusController::class, 'data'])->name('tipoestatus.data');
Route::post('tipoestatus/store', [TipoEstatusController::class, 'store']);
Route::get('tipoestatus/{id}/edit', [TipoEstatusController::class, 'edit']);
Route::put('tipoestatus/update/{id}', [TipoEstatusController::class, 'update']);
Route::post('tipoestatus/toggle/{id}', [TipoEstatusController::class, 'toggle']);

//materias
Route::get('materias', [MateriaController::class, 'index'])->name('materias.index');
Route::get('materias/data', [MateriaController::class, 'data'])->name('materias.data');
Route::post('materias/store', [MateriaController::class, 'store'])->name('materias.store');
Route::get('materias/{id}/edit', [MateriaController::class, 'edit']);
Route::put('materias/update/{id}', [MateriaController::class, 'update']);
Route::post('materias/toggle/{id}', [MateriaController::class, 'toggle']);


//Bitacora
Route::prefix('bitacora')->group(function () {
    Route::get('/', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/data', [BitacoraController::class, 'data'])->name('bitacora.data');
});

//rutas para carpetas:




Route::prefix('carpetas')->name('carpetas.')->group(function () {
    Route::get('/', [CarpetaController::class, 'index'])->name('index');
    Route::get('/data', [CarpetaController::class, 'data'])->name('data');
    Route::post('/store', [CarpetaController::class, 'store'])->name('store');
    Route::get('/edit/{idcarpeta}', [CarpetaController::class, 'edit'])->name('edit');
    Route::post('/toggle/{idcarpeta}', [CarpetaController::class, 'toggle'])->name('toggle');
    Route::delete('/delete/{idcarpeta}', [CarpetaController::class, 'destroy'])->name('delete');
});





// Agrupadas para claridad (opcional)
Route::get('permisosusuarios', [PermisosUsuariosController::class, 'index'])->name('permisosusuarios.index');
Route::get('permisosusuarios/create', [PermisosUsuariosController::class, 'create'])->name('permisosusuarios.create');
Route::post('permisosusuarios', [PermisosUsuariosController::class, 'store'])->name('permisosusuarios.store');
Route::get('permisosusuarios/{id}', [PermisosUsuariosController::class, 'show'])->name('permisosusuarios.show');
Route::get('permisosusuarios/{id}/edit', [PermisosUsuariosController::class, 'edit'])->name('permisosusuarios.edit');
Route::put('permisosusuarios/{id}', [PermisosUsuariosController::class, 'update'])->name('permisosusuarios.update');
Route::delete('permisosusuarios/{id}', [PermisosUsuariosController::class, 'destroy'])->name('permisosusuarios.destroy');


use App\Http\Controllers\ExportarController;

Route::post('/carpetas/reporte', [ExportarController::class, 'generarReporte'])->name('carpetas.reporte');
Route::get('/carpetas/excel-alternativo', [ExportarController::class, 'exportarExcelAlternativo'])->name('carpetas.excel.alternativo');
Route::get('/reporte-carpetas', [ExportarController::class, 'generarReporte'])->name('reporte.carpetas');







//PRUEBA PARA REVISAR LAS TABLAS DE LA BD
use Illuminate\Support\Facades\DB;

Route::get('/check-db', function () {
    $tablas = DB::select('SHOW TABLES');
    return dd($tablas);
});


