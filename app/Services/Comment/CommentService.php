<?php

namespace App\Services\Comment;

use App\Repositories\Comment\CommentRepositoryContract;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CommentService implements CommentServiceContract
{
    protected $createRules = [
        'comment' => 'required',
        'news_id' => 'required',
    ];

    protected $commentRepository;

    public function __construct(CommentRepositoryContract $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function create(array $data)
    {
        $validator = Validator::make($data, $this->createRules);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }
        
        return $this->commentRepository->create($data);
    }
}
