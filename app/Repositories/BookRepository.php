<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookRepository
{
    protected $model;

    public function __construct(Book $book)
    {
        $this->model = $book;
    }

    public function search($query)
    {
        $searchMethod = config('search.method');

        if ($searchMethod === 'scout') {
            // Use Laravel Scout for search
            return $this->model->search($query)->get();
        } else {
            // Use query-based search
            return $this->model->where('title', 'LIKE', "%{$query}%")
                ->orWhereHas('author', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->get();
        }
    }

    /**
     * Get all books
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Find a book by ID
     *
     * @param int $id
     * @return \App\Models\Book
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new book
     *
     * @param array $data
     * @return \App\Models\Book
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a book by ID
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Book
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $data)
    {
        $book = $this->findById($id); // Find or throw exception if not found
        $book->update($data);
        return $book;
    }

    /**
     * Delete a book by ID
     *
     * @param int $id
     * @return bool|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete($id)
    {
        $book = $this->findById($id); // Find or throw exception if not found
        return $book->delete();
    }
}
