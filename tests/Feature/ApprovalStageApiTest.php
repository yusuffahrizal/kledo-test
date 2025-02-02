<?php

namespace Tests\Feature;

use App\Models\ApprovalStage;
use App\Models\User;
use Tests\TestCase;

class ApprovalStageApiTest extends TestCase
{
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

    public function test_can_create_approval_stage()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval1['email'])->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approval_stage.store'), [
            'user_id' => $user->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user_id',
                    'stage',
                    'id'
                ],
                'message',
                'errors'
            ]);
    }

    public function test_unauthorize_to_create_approval_stage()
    {
        $this->login(AuthApiTest::$userAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval2['email'])->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approval_stage.store'), [
            'user_id' => $user->id
        ]);

        $response->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_forbidden_to_create_approval_stage()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', AuthApiTest::$userAccount['email'])->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approval_stage.store'), [
            'user_id' => $user->id
        ]);

        $response->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_validation_create_approval_stage()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval1['email'])->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approval_stage.store'), [
            'user_id' => $user->id
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors' => [
                    'user_id'
                ]
            ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approval_stage.store'), [
            'user_id' => null
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors' => [
                    'user_id'
                ]
            ]);
    }

    public function test_create_approval_stage_2()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval2['email'])->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approval_stage.store'), [
            'user_id' => $user->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user_id',
                    'stage',
                    'id'
                ],
                'message',
                'errors'
            ]);
    }

    public function test_create_approval_stage_3()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval3['email'])->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.approval_stage.store'), [
            'user_id' => $user->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user_id',
                    'stage',
                    'id'
                ],
                'message',
                'errors'
            ]);
    }

    public function test_can_update_approval_stage()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval1['email'])->first();
        $user_update = User::where('email', ApproverApiTest::$adminApproval2['email'])->first();

        $approval_stage = ApprovalStage::where('user_id', $user->id)->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson(route('api.approval_stage.update', $approval_stage->id), [
            'user_id' => $user_update->id
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_unauthorize_to_update_approval_stage()
    {
        $this->login(AuthApiTest::$userAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval1['email'])->first();
        $user_update = User::where('email', ApproverApiTest::$adminApproval2['email'])->first();

        $approval_stage = ApprovalStage::where('user_id', $user->id)->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson(route('api.approval_stage.update', $approval_stage->id), [
            'user_id' => $user_update->id
        ]);

        $response->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_forbidden_to_update_approval_stage()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval2['email'])->first();
        $user_update = User::where('email', AuthApiTest::$userAccount['email'])->first();

        $approval_stage = ApprovalStage::where('user_id', $user->id)->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson(route('api.approval_stage.update', $approval_stage->id), [
            'user_id' => $user_update->id
        ]);

        $response->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_validation_update_approval_stage()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval1['email'])->first();

        $approval_stage = ApprovalStage::where('user_id', $user->id)->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson(route('api.approval_stage.update', $approval_stage->id), [
            'user_id' => null
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors' => [
                    'user_id'
                ]
            ]);
    }

    public function test_rollback_updated_stage()
    {
        $this->login(AuthApiTest::$superAdminAccount);
        $user = User::where('email', ApproverApiTest::$adminApproval1['email'])->first();
        $user_update = User::where('email', ApproverApiTest::$adminApproval2['email'])->first();

        $approval_stage = ApprovalStage::where('user_id', $user->id)->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson(route('api.approval_stage.update', $approval_stage->id), [
            'user_id' => $user_update->id
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }
}
