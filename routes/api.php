<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Author routes
    Route::apiResource('authors', AuthorController::class);

    Route::get('/books/search', [BookController::class, 'search'])->name('books.search');

    // Books by author routes
    Route::get('/authors/{author_id}/books', [BookController::class, 'getBooksByAuthor']); // Get books by author
    Route::post('/authors/{author_id}/books', [BookController::class, 'addBookToAuthor']); // Add a book for an author

    // Book routes
    Route::apiResource('books', BookController::class);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
