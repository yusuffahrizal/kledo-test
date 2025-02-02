<?php

namespace Tests\Feature;

use App\Models\Expense;
use Tests\TestCase;

class ExpenseApiTest extends TestCase
{
    private const AMOUNT = 150000000;
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

    private function change_status(string $status)
    {
        $expense = Expense::where('status', Expense::STATUS_PENDING)->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson(route('api.expense.update', ['expense' => $expense->id]), [
            'status' => $status
        ]);

        return $response;
    }

    private function create_expense()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.expense.create'), [
            'amount' => self::AMOUNT
        ]);

        return $response;
    }

    public function test_can_create_expense()
    {
        $this->login(AuthApiTest::$userAccount);
        $response = $this->create_expense();

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user_id',
                    'amount',
                    'status',
                    'id'
                ],
                'message',
                'errors'
            ]);
    }

    public function test_validation_create_expense()
    {
        $this->login(AuthApiTest::$userAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson(route('api.expense.create'), []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_can_show_expense()
    {
        $this->login(AuthApiTest::$userAccount);

        $expense = Expense::first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson(route('api.expense.show', ['expense' => $expense->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user_id',
                    'amount',
                    'status',
                    'id',
                    'user',
                    'approvals'
                ],
                'message',
                'errors'
            ]);
    }

    public function test_failed_show_expense()
    {
        $this->login(AuthApiTest::$userAccount);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson(route('api.expense.show', ['expense' => 0]));

        $response->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_can_approve_expense_1()
    {
        $this->login(ApproverApiTest::$adminApproval1);
        $response = $this->change_status(Expense::STATUS_APPROVED);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_can_approve_expense_2()
    {
        $this->login(ApproverApiTest::$adminApproval2);
        $response = $this->change_status(Expense::STATUS_APPROVED);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_can_approve_expense_3()
    {
        $this->login(ApproverApiTest::$adminApproval3);
        $response = $this->change_status(Expense::STATUS_APPROVED);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_can_reject_expense()
    {
        $this->login(ApproverApiTest::$adminApproval1);
        $this->create_expense();

        $response = $this->change_status(Expense::STATUS_REJECTED);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    public function test_out_of_order_approval()
    {
        $this->login(ApproverApiTest::$adminApproval1);
        $this->create_expense();

        $response = $this->change_status(Expense::STATUS_APPROVED);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }

    // 403 Not Allowed to approve
    public function test_out_of_order_approval2()
    {
        $this->login(ApproverApiTest::$adminApproval3);
        $this->create_expense();

        $response = $this->change_status(Expense::STATUS_APPROVED);
        $response->assertStatus(403)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
                'errors'
            ]);
    }
}
