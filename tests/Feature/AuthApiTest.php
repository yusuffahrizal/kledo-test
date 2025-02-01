<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\PersonalAccessClient;
use Tests\TestCase;

class AuthApiTest extends TestCase
{

    /**
     * Super Admin Can Login
     *
     * @return void
     */
    public function test_superadmin_can_login(): void
    {
        $response = $this->postJson(route('api.login'), [
            'email' => 'admin@gmail.com',
            'password' => 'password'
        ]);

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

    /**
     * User Can Login
     *
     * @return void
     */
    public function test_user_can_login(): void
    {
        $response = $this->postJson(route('api.login'), [
            'email' => 'user@gmail.com',
            'password' => 'password'
        ]);

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
}
