<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ApproverApiTest extends TestCase
{
    public static array $adminApproval1 = [
        'name' => 'John Doe',
        'email' => 'johndoe@gmail.com',
        'password' => 'password'
    ];

    public static array $adminApproval2 = [
        'name' => 'Marlin Murz',
        'email' => 'marlin.murz@gmail.com',
        'password' => 'password'
    ];

    public static array $adminApproval3 = [
        'name' => 'Anne Marie',
        'email' => 'annem123@gmail.com',
        'password' => 'password'
    ];

    public string $token;

    private function login(array $payload)
    {
        $response = $this->postJson(route('api.login'), $payload);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token'
                ],
                'message',
                'errors'
            ]);

        $this->token = $response->json()['data']['token'];
    }

    public function test_can_create_approver_user()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approver.store'), $this::$adminApproval1);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_forbidden_create_approver_user()
    {
        $this->login(AuthApiTest::$userAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approver.store'), $this::$adminApproval2);

        $response->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_validation_create_approver_user_1()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson(route('api.approver.store'), [
            'name' => null,
            'email' => null,
            'password' => null,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password'
                ]
            ]);
    }

    public function test_validation_create_approver_user_2()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson(route('api.approver.store'), [
            'name' => 'Kimmi Hana',
            'email' => null,
            'password' => null,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors' => [
                    'email',
                    'password'
                ]
            ]);
    }

    public function test_validation_create_approver_user_3()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson(route('api.approver.store'), [
            'name' => 'Kimmi Hana',
            'email' => 'kimmi.hana@gmail.com',
            'password' => null,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors' => [
                    'password'
                ]
            ]);
    }

    public function test_can_update_approver_user()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $approver = User::select('id')
            ->where('email', $this::$adminApproval1['email'])
            ->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson(route('api.approver.update', ['approver' => $approver->id]), [
            'name' => 'Change Name',
            'email' => 'changemail@gmail.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson(route('api.approver.update', ['approver' => $approver->id]), $this::$adminApproval1);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_validation_update_approver_user()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $approver = User::select('id')
            ->where('email', $this::$adminApproval1['email'])
            ->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson(route('api.approver.update', ['approver' => $approver->id]), [
            'name' => null,
            'email' => 'lkjldklsdf'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors' => [
                    'email'
                ]
            ]);
    }

    public function test_can_delete_approver_user()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $new_approver = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approver.store'), [
            'name' => 'Delete User',
            'email' => 'delete@gmail.com',
            'password' => 'password'
        ]);

        $new_approver->assertStatus(201);
        $new_approver_id = $new_approver->json()['data']['id'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson(route('api.approver.destroy', ['approver' => $new_approver_id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors',
            ]);
    }

    public function test_forbidden_delete_approver_user()
    {
        $this->login(AuthApiTest::$userAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson(route('api.approver.destroy', ['approver' => 1]));

        $response->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors',
            ]);
    }

    public function test_not_found_delete_approver_user()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $user = User::where('email', AuthApiTest::$superAdminAccount['email'])
            ->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson(route('api.approver.destroy', ['approver' => $user->id]));

        $response->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors',
            ]);
    }

    public function test_create_approver_user_2()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approver.store'), $this::$adminApproval2);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_create_approver_user_3()
    {
        $this->login(AuthApiTest::$superAdminAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approver.store'), $this::$adminApproval3);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }
}
