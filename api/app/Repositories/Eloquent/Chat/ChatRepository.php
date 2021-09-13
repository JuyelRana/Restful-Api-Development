<?php

namespace App\Repositories\Eloquent\Chat;

use App\Models\Chat;
use App\Repositories\Contracts\Chat\IChat;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ChatRepository extends BaseRepository implements IChat
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Chat::class;
    }

    /**
     * @param $chatId
     * @param array $data
     */
    public function createParticipants($chatId, array $data)
    {
        $chat = $this->model->find($chatId);

        $chat->participants()->sync($data);
    }

    /**
     * @return mixed
     */
    public function getUserChats()
    {
        return Auth::user()->chats()
            ->with(['messages', 'participants'])
            ->get();
    }
}
