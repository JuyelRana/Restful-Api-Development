<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    /**
     * @param Request $request
     * @return bool
     */
    public function attemptLogin(Request $request): bool
    {
        // Attempt to issue a token to the user based on the login credentials
        $token = $this->guard()->attempt($this->credentials($request));

        if (!$token) {
            return false;
        }
        // Get the authenticated user
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return false;
        }

        // Set the user's token
        $this->guard()->setToken($token);

        return true;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request): \Illuminate\Http\JsonResponse
    {
        // Clear login attempts
        $this->clearLoginAttempts($request);

        // Get the token from the authentication guard (JWT)
        $token = (string)$this->guard()->getToken();

        // Extract the expiry date of the token
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "message" => "You need to verify your email account"
                ]
            ]);
        }

        throw ValidationException::withMessages([
            "message" => "Invalid Credentials"
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Logged out successfully!']);
    }
}
