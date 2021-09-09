<?php

namespace App\Repositories\Contracts\User;

interface IUser
{
    public function findByEmail($email);
}
