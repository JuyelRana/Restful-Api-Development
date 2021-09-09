<?php

namespace App\Repositories\Contracts\Invitation;

interface IInvitation
{
    public function addUserToTeam($team, $user_id);

    public function removeUserFromTeam($team, $user_id);
}
