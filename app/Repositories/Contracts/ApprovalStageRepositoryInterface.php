<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\ApprovalStage\CreateStageRequest;
use App\Http\Requests\ApprovalStage\EditStageRequest;

interface ApprovalStageRepositoryInterface
{
    /**
     * @param CreateStageRequest $request
     * 
     * @return [type]
     */
    public function create(CreateStageRequest $request);

    /**
     * @param EditStageRequest $request
     * @param mixed $stage
     * 
     * @return [type]
     */
    public function update(EditStageRequest $request, $stage);
}
