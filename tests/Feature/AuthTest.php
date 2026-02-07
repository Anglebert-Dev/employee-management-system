<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'access_token',
                ],
                'status',
            ]);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $login = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $login->assertOk()->assertJsonStructure([
            'message',
            'data' => ['access_token'],
            'status'
        ]);

        $token = $login->json('data.access_token');

        $logout = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/auth/logout');

        $logout->assertOk()->assertJson([
            'message' => 'Logged out successfully',
            'status' => 200
        ]);
    }

    public function test_user_can_request_password_reset_otp(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertOk()->assertJson([
            'message' => __('passwords.sent'),
            'status' => 200
        ]);
    }

    public function test_user_can_reset_password_with_otp(): void
    {
        $user = User::factory()->create();
        $otp = '123456';

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now()
            ]
        );

        $response = $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'otp' => $otp,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertOk()->assertJson([
            'message' => __('passwords.reset'),
            'status' => 200
        ]);
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }
}
