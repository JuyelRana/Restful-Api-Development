<?php

namespace App\Repositories\Contracts\User;

use Illuminate\Http\Request;

interface IUser
{
    public function findByEmail($email);

    public function search(Request $request);
}
