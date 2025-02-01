<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    use ApiResponder;

    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   operationId="authLogin",
     *   summary="Login user",
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"email", "password"},
     *           @OA\Property(property="email", type="string"),
     *           @OA\Property(property="password", type="string")
     *       )
     *   ),
     *   @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="success", type="boolean", example=true),
     *           @OA\Property(
     *             property="data", 
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@mail.com", format="email"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjIwZjQ"),
     *             @OA\Property(property="role", type="array", @OA\Items(type="string", example="super_admin")),
     *           ),
     *           @OA\Property(property="message", type="string", example="Login successful"),
     *           @OA\Property(property="errors", type="mixed", example=null)
     *       )
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     *   @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authRepository->login($request);

        return $this::apiSuccess(data: $user, message: 'Login successful');
    }

    /**
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/api/logout",
     *   operationId="authLogout",
     *   summary="Logout user",
     *   @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="success", type="boolean", example=true),
     *           @OA\Property(property="data", type="mixed", example=null),
     *           @OA\Property(property="message", type="string", example="Logout successful"),
     *           @OA\Property(property="errors", type="mixed", example=null)
     *       )
     *   ),
     *   
     *   @OA\Response(response=401, ref="#/components/responses/401"),
     * )
     */
    public function logout(): JsonResponse
    {
        $this->authRepository->logout();

        return $this::apiSuccess(message: 'Logout successful');
    }
}
