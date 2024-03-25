<?php
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);

Route::get('/{id}', [ProductController::class, 'show'])->name('.detail');

// Route::resource('s', Controller::class);

Route::get('products/create' ,[ProductController::class, 'create']);
Route::post('products/create' ,[ProductController::class, 'store']);

Route::get('products/{id}/edit',[ProductController::class, 'edit']);
Route::put('products/{id}/edit',[ProductController::class, 'update']);

Route::get('products/{id}/delete',[ProductController::class, 'destroy']);