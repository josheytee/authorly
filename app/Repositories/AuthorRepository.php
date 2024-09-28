<?php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorRepository
{
    protected $model;

    public function __construct(Author $author)
    {
        $this->model = $author;
    }

    /**
     * Get all Authors
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Find a Author by ID
     *
     * @param int $id
     * @return \App\Models\Author
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new Author
     *
     * @param array $data
     * @return \App\Models\Author
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a Author by ID
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Author
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $data)
    {
        $Author = $this->findById($id); // Find or throw exception if not found
        $Author->update($data);
        return $Author;
    }

    /**
     * Delete a Author by ID
     *
     * @param int $id
     * @return bool|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete($id)
    {
        $Author = $this->findById($id); // Find or throw exception if not found
        return $Author->delete();
    }
    public function findByAuthor($authorId): Collection
    {
        return $this->model->where('author_id', $authorId)->get();
    }

    public function findRelated(Author $Author, int $limit = 5): Collection
    {
        return $this->model->where('genre', $Author->genre)
            ->where('id', '!=', $Author->id)
            ->limit($limit)
            ->get();
    }

    public function toggleAvailability($id): Model
    {
        $Author = $this->findById($id);
        $Author->is_available = !$Author->is_available;
        $Author->save();
        return $Author;
    }
}
