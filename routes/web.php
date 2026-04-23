<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ctrlDatos;
use App\Http\Controllers\ctrlCategory;
use App\Http\Controllers\ctrlProduct;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Vista con datos
Route::get('/datos', [ctrlDatos::class, 'AccesoDatos']);


// Vista con datos Link
Route::get('/datoslink',[ctrlDatos::class, 'AccesoDatosLink']);

Route::get('/datosotro',[ctrlDatos::class, 'AccesoDatosOtro']);

// Vista que agarra json del sitio en mi host
Route::get('/sitio', [ctrlDatos::class, 'SitioWeb']);
// Vista de los detalles
Route::get('/sitio/{id}', [ctrlDatos::class, 'SitioWebDetalle'])->name('sitio.detalle');


// Vista de Categorias
Route::get('/categorias', [ctrlDatos::class, 'AccesoCategory']);
Route::post('/categorias', [ctrlCategory::class, 'store']);
Route::put('/categorias/{id}', [ctrlCategory::class, 'update']);
Route::delete('/categorias/{id}', [ctrlCategory::class, 'destroy']);

// Vista Productos
Route::get('/productos', [ctrlDatos::class, 'AccesoProductos']);
Route::post('/productos', [ctrlProduct::class, 'store']);
Route::put('/productos/{id}', [ctrlProduct::class, 'update']);
Route::delete('/productos/{id}', [ctrlProduct::class, 'destroy']);