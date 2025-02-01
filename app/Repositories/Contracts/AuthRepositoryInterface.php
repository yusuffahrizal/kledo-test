<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Auth\LoginRequest;

interface AuthRepositoryInterface
{
    /**
     * @param LoginRequest $request
     * 
     * @return [type]
     */
    public function login(LoginRequest $request);

    /**
     * @return [type]
     */
    public function logout();
}