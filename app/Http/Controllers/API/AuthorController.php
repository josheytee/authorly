<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use  App\Http\Controllers\Controller;
use App\Services\AuthorService;

class AuthorController extends Controller
{
    private $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Display a listing of the authors.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $authors = $this->authorService->getAllAuthors();
        return response()->json($authors);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $author = $this->authorService->createauthor($request->all());
        return response()->json($author, 201);
    }

    public function update(Request $request, $id)
    {
        $author = $this->authorService->getAuthorById($id);
        $author->update($request->all());

        return response()->json([
            'message' => 'Author updated successfully',
            'author' => $author,
        ]);
    }
}
