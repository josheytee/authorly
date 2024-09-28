<?php

namespace App\Http\Controllers\API;

use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use  App\Http\Controllers\Controller;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }


    public function search(Request $request)
    {
        $query = $request->input('q');
        $books = $this->bookService->search($query);

        return response()->json($books);
    }

    /**
     * Display a listing of the books.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function index()
    // {
    //     $books = $this->bookService->getAllBooks();
    //     return response()->json($books);
    // }
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Query books by title or author name
        // $books = Book::where('title', 'like', "%{$search}%")
        //     ->orWhereHas('author', function($query) use ($search) {
        //         $query->where('name', 'like', "%{$search}%");
        //     })
        //     ->with('author')
        //     ->get();
        //     return response()->json($books);
        return $this->bookService->search($search);
    }

    /**
     * Store a newly created book in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'description' => 'required|string',
            'published_at' => 'required|date',
        ]);

        $book = $this->bookService->createBook($data);

        return response()->json(['message' => 'Book created successfully', 'book' => $book], 201);
    }

    /**
     * Display the specified book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $book = $this->bookService->getBookById($id);
            return response()->json($book);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        }
    }

    /**
     * Update the specified book in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'author_id' => 'sometimes|required|exists:authors,id',
            'published_at' => 'sometimes|required|date',
        ]);

        try {
            $book = $this->bookService->updateBook($id, $data);
            return response()->json(['message' => 'Book updated successfully', 'book' => $book]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        }
    }

    /**
     * Remove the specified book from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->bookService->deleteBook($id);
            return response()->json(['message' => 'Book deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        }
    }
}
