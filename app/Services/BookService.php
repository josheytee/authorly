<?php

namespace App\Services;

use App\Repositories\BookRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class BookService
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function search($query): Collection
    {
        return $this->bookRepository->search($query);
    }

    public function getAllBooks(): Collection
    {
        return $this->bookRepository->getAll();
    }

    public function getPaginatedBooks(int $perPage = 15): LengthAwarePaginator
    {
        return $this->bookRepository->paginate($perPage);
    }

    public function getBookById($id): ?Model
    {
        return $this->bookRepository->findById($id);
    }

    public function createBook(array $data): Model
    {
        return $this->bookRepository->create($data);
    }

    public function updateBook($id, array $data): Model
    {
        return $this->bookRepository->update($id, $data);
    }

    public function deleteBook($id): bool
    {
        return $this->bookRepository->delete($id);
    }

    public function searchBooks(string $query): Collection
    {
        return $this->bookRepository->search($query);
    }

    public function getBooksByAuthor($authorId): Collection
    {
        return $this->bookRepository->findByAuthor($authorId);
    }

    public function getRelatedBooks($bookId, $limit = 5): Collection
    {
        $book = $this->getBookById($bookId);
        if (!$book) {
            return new Collection();
        }
        return $this->bookRepository->findRelated($book, $limit);
    }

    public function toggleBookAvailability($id): Model
    {
        $book = $this->getBookById($id);
        $book->is_available = !$book->is_available;
        return $this->bookRepository->update($id, ['is_available' => $book->is_available]);
    }
}
