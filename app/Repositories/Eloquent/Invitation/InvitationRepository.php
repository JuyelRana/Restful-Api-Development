<?php

namespace App\Repositories\Eloquent\Invitation;

use App\Models\Invitation;
use App\Repositories\Contracts\Invitation\IInvitation;
use App\Repositories\Eloquent\BaseRepository;

class InvitationRepository extends BaseRepository implements IInvitation
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Invitation::class;
    }

    /**
     * @param $team
     * @param $user_id
     */
    public function addUserToTeam($team, $user_id)
    {
        $team->members()->attach($user_id);
    }

    /**
     * @param $team
     * @param $user_id
     */
    public function removeUserFromTeam($team, $user_id)
    {
        $team->members()->detach($user_id);
    }
}
