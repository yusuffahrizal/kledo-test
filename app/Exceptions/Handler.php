<?php

namespace App\Exceptions;

use App\Traits\ApiResponder;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponder;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return $this::apiError(message: 'Validation failed', errors: $exception->validator->errors(), code: 422);
            }

            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return $this::apiError(message: 'Unauthenticated', code: 401);
            }

            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return $this::apiError(message: 'Unauthorized', code: 403);
            }

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this::apiError(message: 'Resource not found', code: 404);
            }

            if ($exception instanceof Exception) {
                $code = $exception->getCode();
                $code = in_array($code, [
                    401,
                    403,
                    404,
                    422,
                    500
                ]) ? $code : 500;
                return $this::apiError(message: $exception->getMessage(), code: $code);
            }
        }

        return parent::render($request, $exception);
    }
}
