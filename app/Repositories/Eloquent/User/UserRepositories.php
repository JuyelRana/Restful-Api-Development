<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User;
use App\Repositories\Contracts\User\IUser;
use App\Repositories\Eloquent\BaseRepository;

class UserRepositories extends BaseRepository implements IUser
{
    public function model()
    {
        return User::class;
    }
}
