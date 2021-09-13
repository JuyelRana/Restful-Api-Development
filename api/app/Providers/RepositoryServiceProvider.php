<?php

namespace App\Providers;

use App\Repositories\Contracts\Chat\IChat;
use App\Repositories\Contracts\Comment\IComment;
use App\Repositories\Contracts\Design\IDesign;
use App\Repositories\Contracts\Invitation\IInvitation;
use App\Repositories\Contracts\Message\IMessage;
use App\Repositories\Contracts\Team\ITeam;
use App\Repositories\Contracts\User\IUser;
use App\Repositories\Eloquent\Chat\ChatRepository;
use App\Repositories\Eloquent\Comment\CommentRepository;
use App\Repositories\Eloquent\Design\DesignRepository;
use App\Repositories\Eloquent\Invitation\InvitationRepository;
use App\Repositories\Eloquent\Message\MessageRepository;
use App\Repositories\Eloquent\Team\TeamRepository;
use App\Repositories\Eloquent\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IDesign::class, DesignRepository::class);
        $this->app->bind(IComment::class, CommentRepository::class);
        $this->app->bind(ITeam::class, TeamRepository::class);
        $this->app->bind(IInvitation::class, InvitationRepository::class);
        $this->app->bind(IMessage::class, MessageRepository::class);
        $this->app->bind(IChat::class, ChatRepository::class);
    }
}
