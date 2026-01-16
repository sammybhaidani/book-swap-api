<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::post('/', [BookController::class, 'store']);
    Route::put('/claim/{id}', [BookController::class, 'claim']);  // CHANGED THIS LINE
    Route::put('/{id}/return', [BookController::class, 'returnBook']);
    Route::delete('/{id}', [BookController::class, 'destroy']);
    Route::post('/{bookId}/reviews', [ReviewController::class, 'store']);
});

Route::get('/genres', [GenreController::class, 'index']);
