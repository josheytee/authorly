<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_validates_the_login_request()
    {
        $response = $this->postJson('/api/login', [
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
    public function it_fails_if_credentials_do_not_match()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure(['status', 'message'])
            ->assertJson([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ]);
    }

    #[Test]
    public function it_logs_in_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'token'])
            ->assertJson([
                'status' => true,
                'message' => 'User Logged In Successfully',
            ]);

        // Ensure the user is authenticated
        $this->assertAuthenticatedAs($user);
    }
}
