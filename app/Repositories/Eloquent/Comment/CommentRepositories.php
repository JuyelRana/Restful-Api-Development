<?php

namespace App\Repositories\Eloquent\Comment;

use App\Models\Comment;
use App\Repositories\Contracts\Comment\IComment;
use App\Repositories\Eloquent\BaseRepository;

class CommentRepositories extends BaseRepository implements IComment
{
    public function model(): string
    {
        return Comment::class;
    }
}
