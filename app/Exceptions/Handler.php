<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
            if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }

            if ($exception instanceof UnauthorizedHttpException || $exception instanceof UnauthorizedException) {
                return apiResponse(false, null, __('api.unauthorized'), null, 401);
            }
            if ($exception instanceof MethodNotAllowedHttpException) {
                return apiResponse(false, null, 'Method Not Allowed', null, 403);
            }
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $exception->errors()
                ], 201);
             }

        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return apiResponse(false, null, __('api.unauthorized'), null, 401);

        }

        return parent::render($request, $exception);
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return apiResponse(false, null, 'unauthorized', null, 401);
    }
}
