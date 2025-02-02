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

    /**
     * @OA\Post(
     *   path="/api/expenses",
     *   tags={"Expense"},
     *   summary="Create new Expense",
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"amount"},
     *       @OA\Property(property="amount", type="number", ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="amount", type="number", example=150000000),
     *         @OA\Property(property="status", type="string", example="pending"),
     *         @OA\Property(property="user_Id", type="number", example="1"), 
     *       ),
     *       @OA\Property(property="message", type="string", example="Expense created successfully"),
     *       @OA\Property(property="errors", type="mixed", example=null),
     *     )
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     *   @OA\Response(response=403, ref="#/components/responses/403"),
     *   @OA\Response(response=422, ref="#/components/responses/422")
     *   
     * )
     */
    public function create(CreateExpenseRequest $request)
    {
        $expense = $this->expenseRepository->create($request);
        return $this->apiSuccess(data: $expense, message: 'Expense created successfully', code: 201);
    }

    /**
     * @OA\Patch(
     *   path="/api/expenses/{expense}",
     *   tags={"Expense"},
     *   summary="Change Expense Status",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="expense",
     *     in="path",
     *     required=true,
     *     description="The ID or unique identifier of the expense id to edit",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"amount"},
     *       @OA\Property(property="amount", type="number", ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="mixed", example=null),
     *       @OA\Property(property="message", type="string", example="Approver updated successfully"),
     *       @OA\Property(property="errors", type="mixed", example=null),
     *     ),
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     *   @OA\Response(response=403, ref="#/components/responses/403"),
     *   @OA\Response(response=404, ref="#/components/responses/404"),
     *   @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function changeStatus(ChangeExpenseStatusRequest $request, $expense)
    {
        $expense = $this->expenseRepository->changeStatus($request, $expense);
        return $this->apiSuccess(data: $expense, message: 'Expense status changed successfully', code: 200);
    }

    /**
     * @OA\Get(
     *   path="/api/expenses/{expense}",
     *   tags={"Expense"},
     *   summary="Get Expense",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="expense",
     *     in="path",
     *     required=true,
     *     description="The ID or unique identifier of the expense to retrieve",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="amount", type="number", example=150000000),
     *         @OA\Property(property="status", type="string", example="pending"),
     *         @OA\Property(property="user_Id", type="number", example="1"), 
     *       ),
     *       @OA\Property(property="message", type="string", example="Expense retrieved successfully"),
     *       @OA\Property(property="errors", type="mixed", example=null),
     *     ),
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     *   @OA\Response(response=404, ref="#/components/responses/404"),
     * )
     */
    public function show($expense)
    {
        $expense = $this->expenseRepository->show($expense);
        return $this->apiSuccess(data: $expense, message: 'Expense retrieved successfully', code: 200);
    }
}
