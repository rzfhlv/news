<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (Exception $e, Request $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException || $e instanceof \Illuminate\Auth\AuthenticationException) {
                if ($request->is('v1/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized',
                        'data' => [],
                    ], Response::HTTP_UNAUTHORIZED);
                }
            } else if ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
                if ($request->is('v1/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Forbiden Access',
                        'data' => [],
                    ], Response::HTTP_FORBIDDEN);
                }
            }
        });
    }
}
