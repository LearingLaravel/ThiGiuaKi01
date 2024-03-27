<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;

Route::get('/', [AdminProductController::class, 'index']);

Route::get('products/create' ,[AdminProductController::class, 'create']);
Route::post('products/create' ,[AdminProductController::class, 'store']);

Route::get('products/{id}/edit',[AdminProductController::class, 'edit']);
Route::put('products/{id}/edit',[AdminProductController::class, 'update']);

Route::get('products/{id}/delete',[AdminProductController::class, 'destroy']);
Route::get('/search', [AdminProductController::class, 'search']);
//-----------------------------------------------------------------

Route::get('products', [ProductController::class, 'index'])->name('products');

Route::get('products/{id}', [ProductController::class, 'show']);