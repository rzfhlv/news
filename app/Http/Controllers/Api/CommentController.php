<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Comment\CommentServiceContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\ProcessComment;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentServiceContract $commentService)
    {
        $this->commentService = $commentService;
    }

    public function create(Request $request)
    {
        $data = $request->only(['comment', 'news_id']);
        $data['user_id'] = auth()->user()->id;
        ProcessComment::dispatch($data);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
