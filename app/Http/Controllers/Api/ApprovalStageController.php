<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApprovalStage\CreateStageRequest;
use App\Http\Requests\ApprovalStage\EditStageRequest;
use App\Repositories\Contracts\ApprovalStageRepositoryInterface;
use App\Traits\ApiResponder;

class ApprovalStageController extends BaseController
{
    use ApiResponder;

    protected $approvalStageRepository;

    public function __construct(ApprovalStageRepositoryInterface $approvalStageRepository)
    {
        $this->approvalStageRepository = $approvalStageRepository;
    }

    /**
     * @OA\Post(
     *   path="/api/approval_stage",
     *   tags={"Approval Stage"},
     *   summary="Create new Approval Stage",
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"user_id"},
     *       @OA\Property(property="user_id", type="number"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object", example=null),
     *       @OA\Property(property="message", type="string", example="Approval stage created successfully"),
     *       @OA\Property(property="errors", type="mixed", example=null),
     *     )
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     *   @OA\Response(response=403, ref="#/components/responses/403"),
     *   @OA\Response(response=422, ref="#/components/responses/422")
     * )
     */
    public function store(CreateStageRequest $request)
    {
        $approvalStage = $this->approvalStageRepository->create($request);

        return $this->apiCreated(data: $approvalStage, message: 'Approval stage created successfully');
    }

    /**
     * @OA\Put(
     *   path="/api/approval_stage/{stage}",
     *   tags={"Approval Stage"},
     *   summary="Update Approval Stage",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="stage",
     *     in="path",
     *     description="Approval Stage ID",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"user_id"},
     *       @OA\Property(property="user_id", type="number"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object", example=null),
     *       @OA\Property(property="message", type="string", example="Approval stage updated successfully"),
     *       @OA\Property(property="errors", type="mixed", example=null),
     *     )
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     *   @OA\Response(response=403, ref="#/components/responses/403"),
     *   @OA\Response(response=404, ref="#/components/responses/404"),
     *   @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function update(EditStageRequest $request, $stage)
    {
        $approvalStage = $this->approvalStageRepository->update($request, $stage);

        return $this->apiSuccess(data: $approvalStage, message: 'Approval stage updated successfully', code: 200);
    }
}
