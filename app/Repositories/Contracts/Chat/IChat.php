<?php

namespace App\Repositories\Contracts\Chat;

interface IChat
{
    public function createParticipants($chatId, array $data);

    public function getUserChats();
}
