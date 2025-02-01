<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Expense\ChangeExpenseStatusRequest;
use App\Http\Requests\Expense\CreateExpenseRequest;

interface ExpenseRepositoryInterface
{
    /**
     * @param CreateExpenseRequest $request
     * 
     * @return [type]
     */
    public function create(CreateExpenseRequest $request);

    /**
     * @param ChangeExpenseStatusRequest $request
     * @param mixed $expense
     * 
     * @return [type]
     */
    public function changeStatus(ChangeExpenseStatusRequest $request, $expense);

    /**
     * @param mixed $expense
     * 
     * @return [type]
     */
    public function show($expense);
}
