<?php

namespace App\Services;

use App\Repositories\AuthorRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class AuthorService
{
    protected $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function getAllAuthors(): Collection
    {
        return $this->authorRepository->getAll();
    }

    public function getPaginatedAuthors(int $perPage = 15): LengthAwarePaginator
    {
        return $this->authorRepository->paginate($perPage);
    }

    public function getAuthorById($id): ?Model
    {
        return $this->authorRepository->findById($id);
    }

    public function createAuthor(array $data): Model
    {
        return $this->authorRepository->create($data);
    }

    public function updateAuthor($id, array $data): Model
    {
        return $this->authorRepository->update($id, $data);
    }

    public function deleteAuthor($id): bool
    {
        return $this->authorRepository->delete($id);
    }
}
