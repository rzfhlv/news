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
    const SOMETHING_WENT_WRONG = "Something Went Wrong";
    const SUCCESS_LOGOUT = "Success Logout";
    const SUCCESS = "success";
    const ERROR = "error";

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
                throw new Exception(self::ERROR);
            }
            return new TokenResource($token);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            $message = self::SOMETHING_WENT_WRONG;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof InvalidArgumentException) {
                $message = json_decode($th->getMessage(), true);
                $code = Response::HTTP_BAD_REQUEST;
            } else if ($th instanceof AuthenticationException) {
                $message = $th->getMessage();
                $code = Response::HTTP_UNAUTHORIZED;
            }

            return response()->json([
                'status' => self::ERROR,
                'message' => $message,
                'data' => [],
            ], $code);
        }
    }

    public function register(Request $request)
    {
        try {
            $token = $this->userService->register($request->all());
            if (!($token instanceof Collection)) {
                throw new Exception(self::ERROR);
            }
            return new TokenResource($token);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            $message = self::SOMETHING_WENT_WRONG;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof InvalidArgumentException) {
                $message = json_decode($th->getMessage(), true);
                $code = Response::HTTP_BAD_REQUEST;
            } else if ($th instanceof AuthenticationException) {
                $message = $th->getMessage();
                $code = Response::HTTP_UNAUTHORIZED;
            }

            return response()->json([
                'status' => self::ERROR,
                'message' => $message,
                'data' => [],
            ], $code);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (empty($request)) {
                throw new AuthenticationException(self::ERROR);
            }
            $this->userService->logout($request);
            return response()->json([
                'status' => self::SUCCESS,
                'message' => self::SUCCESS_LOGOUT,
                'data' => [],
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            $message = self::SOMETHING_WENT_WRONG;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof AuthenticationException) {
                $message = $th->getMessage();
                $code = Response::HTTP_UNAUTHORIZED;
            }

            return response()->json([
                'status' => self::ERROR,
                'message' => $message,
                'data' => [],
            ], $code);
        }
    }
}
