<?php
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->post('/login', [AuthController::class, 'login']);


// Route::middleware('auth:sanctum')->group(function () {
    //     Route::post('/books', [BookController::class, 'store']);       // Create a book
    //     Route::put('/books/{book}', [BookController::class, 'update']); // Update a book
    //     Route::delete('/books/{book}', [BookController::class, 'destroy']); // Delete a book
    // });


    Route::middleware('auth:sanctum')->group(function () {
        // Route::prefix('v1')->group(function () {
    Route::get('/books/search', [BookController::class, 'search'])->name('books.search');

   Route::apiResource('books', BookController::class);
   Route::apiResource('authors', AuthorController::class);
// });
});
