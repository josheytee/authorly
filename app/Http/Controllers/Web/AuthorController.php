<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use  App\Http\Controllers\Controller;
use App\Models\Author;
use App\Services\AuthorService;
use Inertia\Inertia;

class AuthorController extends Controller
{
    private $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    public function create(Request $request)
    {
        // Return authors in a view for the frontend (Inertia response)
        return Inertia::render('Author/Index', [
            'authors' => Author::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($request, [
            'title' => 'required|string',
            'author_id' => 'required|exists:authors,id',
        ]);

        $author = $this->authorService->createAuthor($request->all());
        return response()->json($author, 201);
    }
}
