<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'bio', 'author_id'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
