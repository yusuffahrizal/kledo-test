<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Approvers\CreateApproverRequest;
use App\Http\Requests\Approvers\EditApproverRequest;

interface ApproverRepositoryInterface
{
    /**
     * @param CreateApproverRequest $request
     * 
     * @return [type]
     */
    public function create(CreateApproverRequest $request);

    /**
     * @param EditApproverRequest $request
     * @param mixed $id
     * 
     * @return [type]
     */
    public function update(EditApproverRequest $request, $id);

    /**
     * @param mixed $id
     * 
     * @return [type]
     */
    public function delete($id);
}
