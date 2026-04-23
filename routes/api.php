<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ctrlCategory;
use App\Http\Controllers\ctrlProduct;

Route::apiResource('categorias', ctrlCategory::class);
Route::apiResource('productos', ctrlProduct::class);
