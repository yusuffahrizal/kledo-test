<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Expense\ChangeExpenseStatusRequest;
use App\Http\Requests\Expense\CreateExpenseRequest;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Traits\ApiResponder;

class ExpenseController extends BaseController
{
    use ApiResponder;

    protected $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function create(CreateExpenseRequest $request)
    {
        $expense = $this->expenseRepository->create($request);
        return $this->apiSuccess(data: $expense, message: 'Expense created successfully', code: 201);
    }

    public function changeStatus(ChangeExpenseStatusRequest $request, $expense)
    {
        $expense = $this->expenseRepository->changeStatus($request, $expense);
        return $this->apiSuccess(data: $expense, message: 'Expense status changed successfully', code: 200);
    }

    public function show($expense)
    {
        $expense = $this->expenseRepository->show($expense);
        return $this->apiSuccess(data: $expense, message: 'Expense retrieved successfully', code: 200);
    }
}
