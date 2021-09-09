<?php

namespace App\Providers;

use App\Models\{Comment, Design, Invitation, Team};
use App\Policies\{CommentPolicy, DesignPolicy, InvitationPolicy, TeamPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Design::class => DesignPolicy::class,
        Comment::class => CommentPolicy::class,
        Team::class => TeamPolicy::class,
        Invitation::class => InvitationPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
