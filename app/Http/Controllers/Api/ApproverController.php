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
     *   tags={"Approver"},
     *   path="/api/path/{id}",
     *   summary="Approver store",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"xxx"},
     *       @OA\Property(property="xxx", type="string")
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ApproverResource")
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function store(CreateApproverRequest $request)
    {
        $approver = $this->approverRepository->create($request);

        return $this->apiCreated(data: $approver, message: 'Approver created successfully');
    }

    public function update(EditApproverRequest $request, $id)
    {
        $this->approverRepository->update($request, $id);

        return $this->apiSuccess(data: null, message: 'Approver updated successfully');
    }

    public function destroy($id)
    {
        $this->approverRepository->delete($id);

        return $this->apiSuccess(data: null, message: 'Approver deleted successfully');
    }
}
