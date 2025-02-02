<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public static array $superAdminAccount = [
        'email' => 'admin@gmail.com',
        'password' => 'password'
    ];

    public static array $userAccount = [
        'email' => 'user@gmail.com',
        'password' => 'password'
    ];

    public function test_super_admin_can_login(): void
    {
        $response = $this->postJson(route('api.login'), $this::$superAdminAccount);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'token'
                ],
                'message'
            ]);
    }

    public function test_user_can_login(): void
    {
        $response = $this->postJson(route('api.login'), $this::$userAccount);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'token'
                ],
                'message'
            ]);
    }

    public function test_wrong_credentials_cannot_login(): void
    {
        $response = $this->postJson(route('api.login'), [
            'email' => 'random@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_null_email_cannot_login(): void
    {
        $response = $this->postJson(route('api.login'), [
            'email' => null,
            'password' => 'password'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_null_password_cannot_login(): void
    {
        $response = $this->postJson(route('api.login'), [
            'email' => 'admin@gmail.com',
            'password' => null,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_super_admin_can_logout(): void
    {
        $login = $this->postJson(route('api.login'), $this::$superAdminAccount);

        $login->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token'
                ],
                'message'
            ]);

        $response = $login->json();
        $token = $response['data']['token'];
        $logout = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson(route('api.logout'));

        $logout->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_user_can_logout(): void
    {
        $login = $this->postJson(route('api.login'), $this::$userAccount);

        $login->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token'
                ],
                'message'
            ]);

        $response = $login->json();
        $token = $response['data']['token'];
        $logout = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson(route('api.logout'));

        $logout->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_error_logout(): void
    {
        $logout = $this->withHeaders([
            'Authorization' => 'Bearer eyaskjdfndfj'
        ])->postJson(route('api.logout'));

        $logout->assertStatus(401)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }
}
