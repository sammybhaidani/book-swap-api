<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::post('/', [BookController::class, 'store']);
    Route::put('/{id}/claim', [BookController::class, 'claim']);
    Route::put('/{id}/return', [BookController::class, 'returnBook']);
    Route::delete('/{id}', [BookController::class, 'destroy']);
});

Route::get('/genres', [GenreController::class, 'index']);
