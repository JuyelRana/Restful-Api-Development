<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\User\IUser;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUser $users)
    {
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->users = $users;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        // Check if the url is a valid signed url
        if (!URL::hasValidSignature($request)) {
            return response()->json([
                "errors" => [
                    "message" => "Invalid verification link"
                ]
            ], 422);
        }

        // Check if the user already verify account
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "message" => "Email address already verified"
                ]
            ], 422);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified'], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resend(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = $this->users->findWhereFirst('email', $request->email);

        if (!$user) {
            return response()->json([
                'errors' => [
                    "email" => "No user could be found with this email address"
                ]
            ], 422);
        }

        // Check if the user already verify account
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "message" => "Email address already verified"
                ]
            ], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link resent']);
    }


}
