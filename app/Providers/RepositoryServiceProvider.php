<?php

namespace App\Providers;

use App\Repositories\Contracts\Design\IDesign;
use App\Repositories\Contracts\User\IUser;
use App\Repositories\Eloquent\Design\DesignRepositories;
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
    }
}
