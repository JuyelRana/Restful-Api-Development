<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{

    use SendsPasswordResetEmails;

    /**
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => trans($response)], 200);
    }

    /**
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => trans($response)], 422);
    }
}
