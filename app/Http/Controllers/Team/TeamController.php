<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Resources\Team\TeamResource;
use App\Repositories\Contracts\Invitation\IInvitation;
use App\Repositories\Contracts\Team\ITeam;
use App\Repositories\Contracts\User\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TeamController extends Controller
{

    protected $teams;
    protected $users;
    protected $invitations;

    public function __construct(ITeam $teams, IUser $users, IInvitation $invitations)
    {
        $this->teams = $teams;
        $this->users = $users;
        $this->invitations = $invitations;
    }

    /**
     * @param Request $request
     * Get list of all teams (eg for search)
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * @param Request $request
     * @return TeamResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name']
        ]);

        // Create team database
        $team = $this->teams->create([
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    /**
     * @param $id
     * @return TeamResource
     */
    public function show($id)
    {
        $team = $this->teams->withCriteria([
            new EagerLoad(['owner', 'members', 'designs'])
        ])->find($id);

        return new TeamResource($team);
    }

    /**
     * Update team information
     * @param Request $request
     * @param $id
     * @return TeamResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $team = $this->teams->find($id);
        $this->authorize('update', $team);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name,' . $id]
        ]);

        $team = $this->teams->update($id, [
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    /**
     * Fetch the teams that the current user belongs to
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function fetchUserTeams(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $teams = $this->teams->fetchUserTeams();

        return TeamResource::collection($teams);
    }

    /**
     * @param $slug
     * Get teams by slug for the Public view
     */
    public function findBySlug($slug)
    {

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->teams->find($id));
        $this->teams->delete($id);

        return response()->json(['message' => 'Team deleted successfully'], 200);
    }

    /**
     * @param $teamId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromTeam($teamId, $userId)
    {
        // Get the team
        $team = $this->teams->find($teamId);

        // Get the user
        $user = $this->users->find($userId);

        // Check that the user is not the owner
        if ($user->isOwnerOfTeam($team)) {
            return response()->json([
                'message' => 'You are the team owner'
            ], 401);
        }

        /**
         * Check that the person sending the request
         * is either the owner of the team or the person
         * who wants to leave the team
         */
        if (!Auth::user()->isOwnerOfTeam($team) && Auth::id() !== $user->id) {
            return response()->json([
                'message' => 'You cannot do this'
            ], 401);
        }

        $this->invitations->removeUserFromTeam($team, $userId);

        return response()->json(['message' => 'Successfully removed user from the team'], 200);
    }
}
