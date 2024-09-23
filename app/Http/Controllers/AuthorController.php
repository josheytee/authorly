<?php
namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'author_id' => 'required|exists:authors,id',
        ]);

        $book = $this->bookService->createBook($request->all());
        return response()->json($book, 201);
    }


}
