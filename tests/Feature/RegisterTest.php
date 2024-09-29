<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;


class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_validates_the_registration_request()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure(['status', 'message', 'errors'])
            ->assertJson([
                'status' => false,
                'message' => 'validation error',
            ]);
    }

    #[Test]
    public function it_fails_if_email_is_already_taken()
    {
        // Create an existing user
        $existingUser = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure(['status', 'message', 'errors'])
            ->assertJson([
                'status' => false,
                'message' => 'validation error',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ]);
    }

    #[Test]
    public function it_registers_a_user_successfully_with_valid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'token'])
            ->assertJson([
                'status' => true,
                'message' => 'User Created Successfully',
            ]);

        // Assert the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // Ensure the token was created (for example, you can assert the response contains a token)
        $this->assertNotNull($response->json('token'));
    }
}
