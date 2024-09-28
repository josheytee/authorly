<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;


class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_log_in_and_receive_an_http_only_cookie()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertCookie('token'); // Check if the token cookie is set

        // Optionally check if the cookie is HTTPOnly
        $this->assertTrue($response->headers->getCookies()[0]->isHttpOnly());
    }

    #[Test]
    public function a_user_cannot_log_in_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Invalid credentials']);
    }
}
