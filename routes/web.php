<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController; // Importamos el controlador de Categorías
use App\Http\Controllers\ProductController; // Importamos el controlador de Productos
use App\Http\Controllers\OrderController; // Importamos el controlador de Pedidos
use Illuminate\Support\Facades\Route;

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

    // --- NUESTRAS RUTAS PROTEGIDAS DEL INVENTARIO ---
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    // ------------------------------------------------
    // --- NUESTRAS RUTAS PROTEGIDAS DE PEDIDOS ---
    Route::resource('orders', OrderController::class);
    // ------------------------------------------------
});

require __DIR__.'/auth.php';
