<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\Approvers\CreateApproverRequest;
use App\Http\Requests\Approvers\EditApproverRequest;
use App\Models\User;
use App\Repositories\Contracts\ApproverRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApproverRepository implements ApproverRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param CreateApproverRequest $request
     * 
     * @return [type]
     */
    public function create(CreateApproverRequest $request)
    {
        DB::beginTransaction();

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = $this->model->create($data);
        $user->assignRole(config('permission.attributes.role.admin_approval'));

        DB::commit();
        return $user;
    }

    /**
     * @param EditApproverRequest $request
     * @param mixed $id
     * 
     * @return [type]
     */
    public function update(EditApproverRequest $request, $id)
    {
        DB::beginTransaction();

        $data = array_filter($request->validated(), fn($value) => !is_null($value));

        $user = $this->model->find($id);
        $user->update($data);

        DB::commit();
        return $user;
    }

    /**
     * @param mixed $id
     * 
     * @return [type]
     */
    public function delete($id)
    {
        DB::beginTransaction();

        $user = $this->model->find($id);

        if (!$user || ($user && $user->id === auth()->id())) {
            throw new Exception('User not found', 404);
        }

        $user->email = $user->email . '_deleted_' . time();
        $user->save();

        $user->delete();

        DB::commit();
        return $user;
    }
}
