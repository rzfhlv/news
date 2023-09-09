<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use App\Services\User\UserServiceContract;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceContract $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        try {
            $token = $this->userService->login($request->all());
            if (!($token instanceof Collection)) {
                throw new \Exception("error");
            }
            return new TokenResource($token);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            $message = "Something Went Wrong";
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof InvalidArgumentException) {
                $message = json_decode($th->getMessage(), true);
                $code = Response::HTTP_BAD_REQUEST;
            } else if ($th instanceof AuthenticationException) {
                $message = $th->getMessage();
                $code = Response::HTTP_UNAUTHORIZED;
            }

            return response()->json([
                'status' => 'error',
                'message' => $message,
                'data' => [],
            ], $code);
        }
    }
}
