<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory, Searchable;
    protected $fillable = ['title', 'description', 'author_id', 'published_at'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // Define which fields should be searchable
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            // 'author' => $this->author,
            'author_id' => $this->author_id, // Assuming author relationship
        ];
    }
}
