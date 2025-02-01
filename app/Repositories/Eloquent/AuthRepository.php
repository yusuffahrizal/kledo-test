<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Exception;

class AuthRepository implements AuthRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param LoginRequest $request
     * 
     * @return [type]
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        
        $user = $this->model
            ->select('id', 'name', 'email', 'password')
            ->where('email', $data['email'])
            ->first();

        if (!$user || !\Hash::check($data['password'], $user->password)) {
            throw new Exception('Invalid credentials', 401);
        }

        $token = $user->createToken('authToken')->accessToken;
        $user->token = $token;
        $user->role = $user->getRoleNames();
        unset($user->roles);

        return $user;
    }

    /**
     * @return [type]
     */
    public function logout()
    {
        auth()->user()->token()->revoke();

        return true;
    }
}
