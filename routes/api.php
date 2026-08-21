<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/products', [CustomerController::class, 'productsJson'])->name('api.products');
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
})->name('api.health');
