<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\Expense\ChangeExpenseStatusRequest;
use App\Http\Requests\Expense\CreateExpenseRequest;
use App\Models\ApprovalStage;
use App\Models\Expense;
use App\Models\ExpenseApproval;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected $model;

    public function __construct(Expense $model)
    {
        $this->model = $model;
    }

    /**
     * @param CreateExpenseRequest $request
     * 
     * @return [type]
     */
    public function create(CreateExpenseRequest $request)
    {
        $data = $request->validated();

        $payload = [
            'user_id' => auth()->id(),
            'amount' => $data['amount'],
            'status' => $this->model::STATUS_PENDING,
        ];
        $expense = $this->model->create($payload);

        unset($expense->created_at, $expense->updated_at);

        return $expense;
    }

    /**
     * @param ChangeExpenseStatusRequest $request
     * @param mixed $expense
     * 
     * @return [type]
     */
    public function changeStatus(ChangeExpenseStatusRequest $request, $expense)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $find_expense = $this->model->where('status', $this->model::STATUS_PENDING)->find($expense);
            if (!$find_expense) {
                throw new Exception('Expense not found', 404);
            }

            $exists_stage = ApprovalStage::count();

            if (!$exists_stage || ($exists_stage <= 0)) {
                throw new Exception('Approval stages not found', 404);
            }

            $lastStage = ExpenseApproval::where('expense_id', $expense)
                ->orderBy('stage', 'desc')
                ->first();

            $stage = $lastStage ? $lastStage->stage + 1 : 1;

            $user_id = auth()->id();

            $validate_approver = ApprovalStage::where('stage', $stage)
                ->where('user_id', $user_id)
                ->first();

            if (!$validate_approver) {
                throw new Exception('You are not allowed to approve this expense', 403);
            }

            $payload = [
                'expense_id' => $expense,
                'stage' => $stage,
                'user_id' => $user_id,
                'status' => $data['status'],
            ];

            ExpenseApproval::create($payload);

            if ($data['status'] === $this->model::STATUS_REJECTED) {
                $find_expense->update(['status' => $this->model::STATUS_REJECTED]);
                DB::commit();
                return;
            }

            if ($stage === $exists_stage) {
                $find_expense->update(['status' => $this->model::STATUS_APPROVED]);
                DB::commit();
                return;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param mixed $expense
     * 
     * @return [type]
     */
    public function show($expense)
    {
        $expense = $this->model->with([
            'user' => function ($u) {
                $u->select('id', 'name', 'email');
            },
            'approvals' => function ($a) {
                $a->select('id', 'expense_id', 'user_id', 'stage', 'status')->with(['user' => function ($u) {
                    $u->select('id', 'name', 'email');
                }]);
            }
        ])
            ->find($expense);
        if (!$expense) {
            throw new Exception('Expense not found', 404);
        }

        return $expense;
    }
}
