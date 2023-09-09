<?php

namespace App\Repositories\Comment;

use App\Models\Comment;

class CommentRepository implements CommentRepositoryContract
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function create(array $data)
    {
        return $this->comment::create($data);
    }
}
