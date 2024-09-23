<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use  App\Models\Book;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence, // Generate a random title
            'author_id' => \App\Models\Author::factory(), // Create a related author
            'description'=> $this->faker->paragraph(4),
            'published_at' => $this->faker->date, // Random date for published_at
        ];
    }
}
