<?php

namespace App\Repositories\Eloquent\Comment;

use App\Models\Comment;
use App\Repositories\Contracts\Comment\IComment;
use App\Repositories\Eloquent\BaseRepository;

class CommentRepository extends BaseRepository implements IComment
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Comment::class;
    }
}
