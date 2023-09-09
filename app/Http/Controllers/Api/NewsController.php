<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsCreateResource;
use App\Http\Resources\News;
use App\Http\Resources\NewsDetailResource;
use App\Http\Resources\NewsUpdateResource;
use App\Services\News\NewsServiceContract;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsServiceContract $newsService)
    {
        $this->newsService = $newsService;
    }

    public function create(Request $request)
    {
        try {
            $news = $this->newsService->create($request);
            return new NewsCreateResource($news);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            $message = self::SOMETHING_WENT_WRONG;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof InvalidArgumentException) {
                $message = json_decode($th->getMessage(), true);
                $code = Response::HTTP_BAD_REQUEST;
            } else if ($th instanceof AuthenticationException) {
                $message = self::UNAUTHORIZED;
                $code = Response::HTTP_UNAUTHORIZED;
            }

            return response()->json([
                'status' => self::ERROR,
                'message' => $message,
                'data' => [],
            ], $code);
        }
    }

    public function all(Request $request)
    {
        try {
            $news = $this->newsService->all();
            return new News($news);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            return response()->json([
                'status' => self::ERROR,
                'message' => self::SOMETHING_WENT_WRONG,
                'data' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get(Request $request, $id)
    {
        try {
            $news = $this->newsService->get($id);
            return new NewsDetailResource($news);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'status' => self::ERROR,
                'message' => self::SOMETHING_WENT_WRONG,
                'data' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $news = $this->newsService->update($request, $id);
            return new NewsUpdateResource($news);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            $message = self::SOMETHING_WENT_WRONG;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof InvalidArgumentException) {
                $message = json_decode($th->getMessage(), true);
                $code = Response::HTTP_BAD_REQUEST;
            }

            return response()->json([
                'status' => self::ERROR,
                'message' => $message,
                'data' => [],
            ], $code);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $this->newsService->delete($request, $id);
            return response()->json([
                'status' => self::SUCCESS,
                'message' => self::SUCCESS_DELETE,
                'data' => [],
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'status' => self::ERROR,
                'message' => self::SOMETHING_WENT_WRONG,
                'data' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
