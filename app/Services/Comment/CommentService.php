<?php

namespace App\Services\Comment;

use App\Repositories\Comment\CommentRepositoryContract;
use App\Repositories\News\NewsRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CommentService implements CommentServiceContract
{
    protected $createRules = [
        'comment' => 'required',
        'news_id' => 'required',
    ];

    protected $commentRepository;
    protected $newsRepository;

    public function __construct(
        CommentRepositoryContract $commentRepository,
        NewsRepositoryContract $newsRepository,
    ) {
        $this->commentRepository = $commentRepository;
        $this->newsRepository = $newsRepository;
    }

    public function create(array $data)
    {
        $validator = Validator::make($data, $this->createRules);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }

        DB::beginTransaction();
        try {
            $this->newsRepository->check($data['news_id']);
            $comment = $this->commentRepository->create($data);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $comment;
    }
}
