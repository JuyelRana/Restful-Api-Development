<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getMe()
    {
        if (Auth::check()) {
//            return response()->json(["user" => Auth::user()], 200);
            return new UserResource(Auth::user());
        }

        return response()->json(null, 200);
    }
}
