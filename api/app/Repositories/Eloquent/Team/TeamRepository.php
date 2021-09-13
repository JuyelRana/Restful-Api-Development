<?php

namespace App\Repositories\Eloquent\Team;

use App\Models\Team;
use App\Repositories\Contracts\Team\ITeam;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;

class TeamRepository extends BaseRepository implements ITeam
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Team::class;
    }

    /**
     * @return mixed
     */
    public function fetchUserTeams()
    {
        return Auth::user()->teams;
    }
}
