<?php

namespace App\Repositories\Eloquent\Message;

use App\Models\Message;
use App\Repositories\Contracts\Message\IMessage;
use App\Repositories\Eloquent\BaseRepository;

class MessageRepository extends BaseRepository implements IMessage
{
    public function model(): string
    {
        return Message::class;
    }
}
