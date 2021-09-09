<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationToJoinTeam;
use App\Models\Team;
use App\Repositories\Contracts\Invitation\IInvitation;
use App\Repositories\Contracts\Team\ITeam;
use App\Repositories\Contracts\User\IUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    protected $invitations;
    protected $teams;
    protected $users;

    /**
     * @param IInvitation $invitations
     * @param ITeam $teams
     * @param IUser $users
     */
    public function __construct(IInvitation $invitations, ITeam $teams, IUser $users)
    {
        $this->invitations = $invitations;
        $this->teams = $teams;
        $this->users = $users;
    }

    /**
     * @param Request $request
     * @param $teamId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function invite(Request $request, $teamId)
    {
        // Get the team
        $team = $this->teams->find($teamId);
        $this->validate($request, [
            'email' => ['required', 'email']
        ]);

        $user = Auth::user();

        // Check if the user owns the team
        if (!$user->isOwnerOfTeam($team)) {
            return response()->json([
                'email' => 'You are not the team owner'
            ], 401);
        }

        // Check if the email has a pending invitation
        if ($team->hasPendingInvite($request->email)) {
            return response()->json([
                'email' => 'Email already has a pending invite'
            ], 422);
        }

        // Get the recipient by email
        $recipient = $this->users->findByEmail($request->email);

        // If the recipient does not exist, send invitation to join the team
        if (!$recipient) {

            $this->createInvitation(false, $team, $request->email);

            return response()->json([
                'message' => 'Invitation sent to user'
            ], 200);
        }

        // Check if the team already has the user
        if ($team->hasUser($recipient)) {
            return response()->json([
                'email' => 'This user seems to be a team member already'
            ], 422);
        }

        // Send the invitation to the user
        $this->createInvitation(true, $team, $request->email);

        return response()->json([
            'message' => 'Invitation sent to user'
        ], 200);


    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function resend($id)
    {
        $invitation = $this->invitations->find($id);

        // Check if the user owns the team
//        if (!Auth::user()->isOwnerOfTeam($invitation->team)) {
//            return response()->json([
//                'email' => 'You are not the team owner'
//            ], 401);
//        }
        $this->authorize('resend', $invitation);

        $recipient = $this->users->findByEmail($invitation->recipient_email);

        Mail::to($invitation->recipient_email)->send(new SendInvitationToJoinTeam($invitation, !is_null($recipient)));

        return response()->json([
            'message' => 'Invitation resent'
        ], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function respond(Request $request, $id)
    {
        $this->validate($request, [
            'token' => ['required'],
            'decision' => ['required']
        ]);

        $token = $request->token;
        $decision = $request->decision;  // 'accept' or 'deny'
        $invitation = $this->invitations->find($id);

        // Check if the invitation belongs to this user
//        if ($invitation->recipient_email !== Auth::user()->email) {
//            return response()->json(['message' => 'This is not your invitation '], 401);
//        }
        $this->authorize('respond', $invitation);

        // Check to make sure that the tokens match
        if ($invitation->token !== $token) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Check if accepted
        if ($decision !== 'deny') {
            $this->invitations->addUserToTeam($invitation->team, Auth::id());
        }

        $this->invitations->delete($invitation->id);

        return response()->json(['message' => 'Successfull'], 200);

    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $this->authorize('delete', $this->invitations->find($id));

        $this->invitations->delete($id);

        return response()->json(['message' => 'Invitation deleted successfully.'], 200);
    }

    /**
     * @param bool $user_exists
     * @param Team $team
     * @param string $email
     */
    protected function createInvitation(bool $user_exists, Team $team, string $email)
    {

        $invitation = $this->invitations->create([
            'team_id' => $team->id,
            'sender_id' => Auth::id(),
            'recipient_email' => $email,
            'token' => md5(uniqid(microtime()))
        ]);

        Mail::to($email)->send(new SendInvitationToJoinTeam($invitation, $user_exists));

    }
}

