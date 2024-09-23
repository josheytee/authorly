<?php

namespace App\Policies;

use App\Models\User;

class BookPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Book $book)
{
    return $user->id === $book->author_id;
}
}
