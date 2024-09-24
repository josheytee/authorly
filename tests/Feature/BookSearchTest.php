<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Models\User; // Import User model
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class BookSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create some authors and books for testing
        $author = Author::factory()->create(['name' => 'J.K. Rowling']);
        Book::factory()->create([
            'title' => 'Harry Potter',
            'author_id' => $author->id,
        ]);

        // Create a user and authenticate
        $this->actingAs(User::factory()->create()); // Authenticate as a user
    }

    #[Test]
    public function it_can_search_books_by_title_using_query_based_search()
    {
        // Mock the configuration to use query-based search
        \Config::set('search.method', 'query');

        // Perform the search
        $response = $this->getJson('/api/books/search?q=Harry Potter');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Harry Potter']);
    }

    #[Test]
    public function it_can_search_books_by_author_using_query_based_search()
    {
        // Mock the configuration to use query-based search
        \Config::set('search.method', 'query');

        // Perform the search by author name
        $response = $this->getJson('/api/books/search?q=Rowling');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Harry Potter']);
    }

    #[Test]
    public function it_can_search_books_using_laravel_scout()
    {
        // Mock the configuration to use Laravel Scout
        \Config::set('search.method', 'scout');

        // $user = User::factory()->create();
        // $this->actingAs($user);

        // Perform the search (you may need to mock Scout behavior for large datasets)
        $response = $this->getJson('/api/books/search?q=Harry Potter');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Harry Potter']);
    }

    #[Test]
    public function it_returns_empty_results_if_no_books_found()
    {
        // Mock the configuration to use query-based search
        \Config::set('search.method', 'query');

        // Perform the search with no matching books
        $response = $this->getJson('/api/books/search?q=Unknown Title');

        $response->assertStatus(200)
            ->assertJsonCount(0); // Expecting no results
    }
}
