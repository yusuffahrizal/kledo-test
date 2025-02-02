<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\ApprovalStage\CreateStageRequest;
use App\Http\Requests\ApprovalStage\EditStageRequest;
use App\Models\ApprovalStage;
use App\Models\User;
use App\Repositories\Contracts\ApprovalStageRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ApprovalStageRepository implements ApprovalStageRepositoryInterface
{

    protected $model;

    public function __construct(ApprovalStage $model)
    {
        $this->model = $model;
    }

    /**
     * @param CreateStageRequest $request
     * 
     * @return [type]
     */
    public function create(CreateStageRequest $request)
    {
        DB::beginTransaction();
        $data = $request->validated();

        $permission = User::select('id')
            ->find($data['user_id'])
            ->hasPermissionTo(config('permission.attributes.permission.approve_expense'));

        if (!$permission) {
            throw new Exception('User does not have permission to approve expenses', 403);
        }

        $lastStage = $this->model->orderBy('stage', 'desc')->first();

        $response = $this->model->create([
            'user_id' => $data['user_id'],
            'stage' => $lastStage ? $lastStage->stage + 1 : 1
        ]);

        unset($response->created_at, $response->updated_at);
        DB::commit();
        return $response;
    }

    /**
     * @param EditStageRequest $request
     * @param mixed $stage
     * 
     * @return [type]
     */
    public function update(EditStageRequest $request, $stage)
    {
        DB::beginTransaction();
        $data = $request->validated();

        $permission = User::select('id')
            ->find($data['user_id'])
            ->hasPermissionTo(config('permission.attributes.permission.approve_expense'));

        if (!$permission) {
            throw new Exception('User does not have permission to approve expenses', 403);
        }

        $find_stage = $this->model->find($stage);

        if (!$find_stage) {
            throw new Exception('Approval stage not found', 404);
        }

        $exists_stage = $this->model->where('user_id', $data['user_id'])->where('id', '!=', $stage)->first();
        if ($exists_stage) {
            $s_exists = $exists_stage->stage;
            $s_find = $find_stage->stage;

            $exists_stage->update(['stage' => $s_find]);
            $find_stage->update(['stage' => $s_exists]);
        } else {
            $find_stage->update($data);
        }

        unset($find_stage->created_at, $find_stage->updated_at);
        DB::commit();
    }
}
