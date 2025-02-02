<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Approvers\CreateApproverRequest;
use App\Http\Requests\Approvers\EditApproverRequest;
use App\Repositories\Contracts\ApproverRepositoryInterface;
use App\Traits\ApiResponder;

class ApproverController extends BaseController
{
    use ApiResponder;

    protected $approverRepository;

    public function __construct(ApproverRepositoryInterface $approverRepository)
    {
        $this->approverRepository = $approverRepository;
    }

    /**
     * @OA\Post(
     *   path="/api/approver",
     *   tags={"Approver"},
     *   summary="Create new Approver",
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name", "email", "password"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string"),
     *     )
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
     *         @OA\Property(property="name", type="string", example="John Doe"),
     *         @OA\Property(property="email", type="string", example="john.doe@mail.com", format="email"),
     *         @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjIwZjQ"),
     *         @OA\Property(property="role", type="array", @OA\Items(type="string", example="admin_approval")),
     *       ),
     *       @OA\Property(property="message", type="string", example="Approver created successfully"),
     *       @OA\Property(property="errors", type="mixed", example=null),
     *     ),
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     *   @OA\Response(response=422, ref="#/components/responses/422")
     * )
     */
    public function store(CreateApproverRequest $request)
    {
        $approver = $this->approverRepository->create($request);

        return $this->apiCreated(data: $approver, message: 'Approver created successfully');
    }

    /**
     * @OA\Patch(
     *   path="/api/approver/{approver}",
     *   tags={"Approver"},
     *   summary="Edit Approver",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="approver",
     *     in="path",
     *     required=true,
     *     description="The ID or unique identifier of the approver to edit",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
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
     *   @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function update(EditApproverRequest $request, $id)
    {
        $this->approverRepository->update($request, $id);

        return $this->apiSuccess(data: null, message: 'Approver updated successfully');
    }

    /**
     * @OA\Delete(
     *   path="/api/approver/{approver}",
     *   tags={"Approver"},
     *   summary="Delete Approver",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="approver",
     *     in="path",
     *     required=true,
     *     description="The ID or unique identifier of the approver to delete",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="mixed", example=null),
     *       @OA\Property(property="message", type="string", example="Approver deleted successfully"),
     *       @OA\Property(property="errors", type="mixed", example=null),
     *     )
     *   ),
     *   
     *   @OA\Response(response=403, ref="#/components/responses/403"),
     *   @OA\Response(response=404, ref="#/components/responses/404"),
     * )
     */
    public function destroy($id)
    {
        $this->approverRepository->delete($id);

        return $this->apiSuccess(data: null, message: 'Approver deleted successfully');
    }
}
