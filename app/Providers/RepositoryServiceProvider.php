<?php

namespace App\Providers;

use App\Repositories\Contracts\Comment\IComment;
use App\Repositories\Contracts\Design\IDesign;
use App\Repositories\Contracts\Invitation\IInvitation;
use App\Repositories\Contracts\Team\ITeam;
use App\Repositories\Contracts\User\IUser;
use App\Repositories\Eloquent\Comment\CommentRepositories;
use App\Repositories\Eloquent\Design\DesignRepositories;
use App\Repositories\Eloquent\Invitation\InvitationRepositories;
use App\Repositories\Eloquent\Team\TeamRepositories;
use App\Repositories\Eloquent\User\UserRepositories;
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
        $this->app->bind(IUser::class, UserRepositories::class);
        $this->app->bind(IDesign::class, DesignRepositories::class);
        $this->app->bind(IComment::class, CommentRepositories::class);
        $this->app->bind(ITeam::class, TeamRepositories::class);
        $this->app->bind(IInvitation::class, InvitationRepositories::class);
    }
}
