<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User; // Assuming you have authentication enabled
use PHPUnit\Framework\Attributes\Test;

class BookTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_authenticated_user_can_create_a_book()
    {
        // Create a valid author first
    $author = \App\Models\Author::factory()->create(); // Use factory or manually create the author

    // Attempt to create a book with a valid author_id
    $response = $this->actingAs(User::factory()->create()) // Assume user authentication is required
        ->postJson('/api/books', [
            'title' => 'New Book',
            'author_id' => $author->id, // Use the valid author ID
            'description'=> 'New Book Description',
            'published_at' => '2024-09-18',
        ]);

    // Check for the correct response
    $response->assertStatus(201) // Check if the response status is 201 (Created)
             ->assertJson(['message' => 'Book created successfully']);

    // Ensure the book was created in the database
    $this->assertDatabaseHas('books', ['title' => 'New Book']);
    }

    #[Test]
    public function an_unauthenticated_user_cannot_create_a_book()
    {
        $response = $this->postJson('/api/books', [
            'title' => 'Unauthorized Book',
            'author_id' => 1,
            'published_at' => '2024-09-18',
        ]);

        $response->assertStatus(401); // Ensure unauthenticated users get a 401 error
    }

    #[Test]
    public function a_book_can_be_updated()
    {
            // Create a valid author first
    $author = \App\Models\Author::factory()->create(); // Using factory or manually create an author

    // Create a book associated with the valid author
    $book = \App\Models\Book::factory()->create([
        'author_id' => $author->id, // Ensure the book has a valid author ID
        'title' => 'Original Title',
    ]);

    // Perform the update
    $response = $this->actingAs(User::factory()->create()) // Assuming authentication is required
        ->putJson("/api/books/{$book->id}", [
            'title' => 'Updated Title',
            'author_id' => $author->id, // Use a valid author ID
            'description' => 'New Book Description',
            'published_at' => '2021-08-02',
        ]);

    // Check for the correct response
    $response->assertStatus(200) // Ensure successful update response
             ->assertJson(['message' => 'Book updated successfully']);

    // Verify the book was updated in the database
    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'title' => 'Updated Title',
        'author_id' => $author->id, // Ensure the foreign key is valid
    ]);
    }

    #[Test]
    public function a_book_can_be_deleted()
    {
        $this->actingAs(User::factory()->create());

        $book = Book::factory()->create();

        $response = $this->deleteJson('/api/books/' . $book->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Book deleted successfully']);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    #[Test]
public function validation_error_when_creating_a_book_without_a_title()
{
    $this->actingAs(User::factory()->create());

    $response = $this->postJson('/api/books/', [
        'title' => '', // Missing title
        'author_id' => 1,
    ]);

    $response->assertStatus(422) // Ensure it's a validation error
             ->assertJsonValidationErrors('title');
}

#[Test]
public function it_returns_custom_json_error_for_database_query_exception()
{

    $this->actingAs(User::factory()->create());

        // Attempt to create a book with invalid data that triggers a query exception
        $response = $this->postJson('/api/books/', [
            'title' => 'Invalid Book',
            'description' => 'Invalid Book Description',
            'published_at' => '2021-08-02',
            'author_id' => 9999, // Invalid author_id that will trigger the exception
        ]);


    // Assert that the response contains the custom JSON error message
    $response->assertStatus(422) // Unprocessable Entity status
             ->assertJson([
                 'message' => 'The selected author id is invalid.',
                 'errors' => [
                     'author_id' => ['The selected author id is invalid.'],
                 ],
             ]);
}


}
