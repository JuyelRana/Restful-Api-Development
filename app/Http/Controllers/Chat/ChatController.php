<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\Message\MessageResource;
use App\Repositories\Contracts\Chat\IChat;
use App\Repositories\Contracts\Message\IMessage;
use App\Repositories\Eloquent\Criteria\WithTrashed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chats, $messages;

    public function __construct(IChat $chats, IMessage $messages)
    {
        $this->chats = $chats;
        $this->messages = $messages;
    }

    /**
     * @param Request $request
     * @return MessageResource
     * @throws \Illuminate\Validation\ValidationException
     * Send message to user
     */
    public function sendMessage(Request $request): MessageResource
    {
        // Validate the request
        $this->validate($request, [
            'recipient' => ['required'],
            'body' => ['required']
        ]);

        $recipient = $request->recipient;
        $user = Auth::user();
        $body = $request->body;

        // Check if their is an existing chat
        // between the auth user and the recipient
        $chat = $user->getChatWithUser($recipient);

        if (!$chat) {
            $chat = $this->chats->create([]);
            $this->chats->createParticipants($chat->id, [$user->id, $recipient]);
        }

        // Add the message to the chat
        $message = $this->messages->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body,
            'last_read_at' => null
        ]);

        return new MessageResource($message);

    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * Get chats for user
     */
    public function getUserChats(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $chats = $this->chats->getUserChats(Auth::id());
        return ChatResource::collection($chats);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * Get messages for chat
     */
    public function getChatMessages($id)
    {
        $messages = $this->messages->withCriteria([new WithTrashed()])->findWhere('chat_id', $id);

        return MessageResource::collection($messages);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * Marked chat as read
     */
    public function markedAsRead($id): \Illuminate\Http\JsonResponse
    {
        $chat = $this->chats->find($id);
        $chat->markedAsReadForUser(Auth::id());

        return response()->json(['message' => 'Successful'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * Destroy message
     */
    public function destroyMessage($id): \Illuminate\Http\JsonResponse
    {
        $message = $this->messages->find($id);

        $this->authorize('delete', $message);

        $this->messages->delete($id);

        return response()->json(['message' => 'Message successfully deleted'], 200);
    }
}
