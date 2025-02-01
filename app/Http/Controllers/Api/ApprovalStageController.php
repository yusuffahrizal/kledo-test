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

    public function store(CreateStageRequest $request)
    {
        $approvalStage = $this->approvalStageRepository->create($request);

        return $this->apiCreated(data: $approvalStage, message: 'Approval stage created successfully');
    }

    public function update(EditStageRequest $request, $stage)
    {
        $approvalStage = $this->approvalStageRepository->update($request, $stage);

        return $this->apiSuccess(data: $approvalStage, message: 'Approval stage updated successfully', code: 200);
    }
}
