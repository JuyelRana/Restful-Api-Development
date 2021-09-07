<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Repositories\Contracts\User\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();

        return UserResource::collection($users);
    }

    public function getMe()
    {
        if (Auth::check()) {
            return new UserResource(Auth::user());
        }

        return response()->json(null, 200);
    }
}
