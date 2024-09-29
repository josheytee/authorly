<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use  App\Http\Controllers\Controller;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function show(Request $request, Author $author)
    {
        $author = $this->authorService->getAuthorById($author->id);
        return response()->json($author, 200);
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

    /**
     * Remove the specified author from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->authorService->deleteAuthor($id);
            return response()->json(['message' => 'Author deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Author not found'], 404);
        }
    }
}
