<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Repositories\Contracts\User\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $users;

    /**
     * @param IUser $users
     */
    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();

        return UserResource::collection($users);
    }

    /**
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function getMe()
    {
        if (Auth::check()) {
            return new UserResource(Auth::user());
        }

        return response()->json(null, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $designers = $this->users->search($request);

        return UserResource::collection($designers);
    }

    /**
     * @param $username
     * @return UserResource
     */
    public function findByUsername($username): UserResource
    {
        $user = $this->users->findWhereFirst('username', $username);

        return new UserResource($user);
    }
}
