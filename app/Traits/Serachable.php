<?php

namespace App\Traits;

trait Searchable
{
    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'LIKE', "%{$term}%");
    }
}
