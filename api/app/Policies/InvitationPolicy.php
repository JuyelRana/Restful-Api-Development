<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invitation $invitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invitation $invitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * @param User $user
     * @param Invitation $invitation
     * @return bool
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->sender_id;
    }

    /**
     * @param User $user
     * @param Invitation $invitation
     * @return bool
     */
    public function respond(User $user, Invitation $invitation): bool
    {
        return $user->email === $invitation->recipient_email;
    }

    /**
     * @param User $user
     * @param Invitation $invitation
     * @return bool
     */
    public function resend(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->sender_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invitation $invitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Invitation $invitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Invitation $invitation)
    {
        //
    }
}
