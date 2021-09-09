<?php

namespace App\Repositories\Eloquent\Team;

use App\Models\Team;
use App\Repositories\Contracts\Team\ITeam;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;

class TeamRepositories extends BaseRepository implements ITeam
{

    public function model(): string
    {
        return Team::class;
    }

    public function fetchUserTeams()
    {
        return Auth::user()->teams;
    }
}
