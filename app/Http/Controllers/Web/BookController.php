<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        // Return books in a view for the frontend (Inertia response)
        return Inertia::render('Books/Index', [
            'books' => Book::all(),
        ]);
    }
    public function create()
    {
        // Return books in a view for the frontend (Inertia response)
        return Inertia::render('Books/Index', [
            'books' => Book::all(),
        ]);
    }

    public function edit(Book $book)
    {
        // Return books in a view for the frontend (Inertia response)
        return Inertia::render('Books/Index', [
            'book' => Book::all(),
        ]);
    }

    public function store(Request $request)
    {
        // Store a new book from the frontend
        $validated = $request->validate([
            'title' => 'required',
            'author' => 'required',
        ]);

        Book::create($validated);

        return redirect()->route('books');
    }
}
