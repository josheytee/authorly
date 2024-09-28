<?php

use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;

Route::middleware('guest')->post('/login', [AuthController::class, 'login'])->name('api.login');
Route::middleware('guest')->post('/register', [AuthController::class, 'register']);


// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/books', [BookController::class, 'store']);       // Create a book
//     Route::put('/books/{book}', [BookController::class, 'update']); // Update a book
//     Route::delete('/books/{book}', [BookController::class, 'destroy']); // Delete a book
// });


Route::get('/user', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    // Route::prefix('v1')->group(function () {

    Route::get('/books/search', [BookController::class, 'search'])->name('books.search');

    Route::apiResource('books', BookController::class);
    Route::apiResource('authors', AuthorController::class);
    // });
});
