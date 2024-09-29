<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use App\Services\AuthorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Mockery;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    private $authorService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authorService = Mockery::mock(AuthorService::class);
        $this->app->instance(AuthorService::class, $this->authorService);

        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_should_return_all_authors()
    {
        $authors = Author::factory(3)->make();

        // Mock the service call to return these authors
        $this->authorService->shouldReceive('getAllAuthors')
            ->once()
            ->andReturn($authors);

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
            ->assertJson($authors->toArray());
    }

    #[Test]
    public function it_should_store_a_new_author()
    {
        $data = [
            'name' => 'New Author',
        ];

        // Mock the service call to create the author
        $this->authorService->shouldReceive('createauthor')
            ->once()
            ->with($data)
            ->andReturn(new Author($data));

        $response = $this->postJson('/api/authors', $data);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'New Author',
            ]);
    }


    public function it_should_show_an_author_by_id()
    {

        // Create a user and simulate authentication
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an author in the database
        $author = Author::factory()->create();

        // Make a request to retrieve the author by ID
        $response = $this->getJson("/api/authors/{$author->id}");

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJson([
                'id' => $author->id,
                'name' => $author->name,
            ]);
    }

    #[Test]
    public function it_should_update_an_author()
    {

        // Mock the AuthorService
        $authorServiceMock = $this->mock(AuthorService::class);

        // Simulate an author
        $author = Author::factory()->create(['name' => 'Original Name']);
        $updatedData = ['name' => 'Updated Name'];

        // Expect the mock to be called with the author's id and the updated data
        $authorServiceMock->shouldReceive('getAuthorById')
            ->with($author->id)
            ->andReturn($author);

        $authorServiceMock->shouldReceive('updateAuthor')
            ->with($author->id, $updatedData)
            ->andReturn(true);

        // Act as an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Send the update request
        $response = $this->putJson("/api/authors/{$author->id}", $updatedData);

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Author updated successfully',
                'author' => [
                    'id' => $author->id,
                    'name' => $updatedData['name'],
                ],
            ]);
    }

    #[Test]
    public function it_should_delete_an_author()
    {


        $authorId = 1;

        // Mock the service call for deleting the author
        $this->authorService->shouldReceive('deleteAuthor')
            ->once()
            ->with($authorId)
            ->andReturn(true);

        $response = $this->deleteJson("/api/authors/{$authorId}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Author deleted successfully',
            ]);
    }

    #[Test]
    public function it_should_return_not_found_if_author_does_not_exist_on_delete()
    {

        $authorId = 1;

        // Mock the service call to throw a ModelNotFoundException
        $this->authorService->shouldReceive('deleteAuthor')
            ->once()
            ->with($authorId)
            ->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException);

        $response = $this->deleteJson("/api/authors/{$authorId}");

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Author not found',
            ]);
    }
}
