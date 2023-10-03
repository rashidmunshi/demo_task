<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class LoginController extends Controller
{
    public function login()
    {
        $credentials = request()->only('mobile_number', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userData = auth()->user();
        return (new UserResource($userData))->additional([
            'status' => true,
            'message' => 'Successfully logged in',
            'token' => $token,
        ]);
    }


    public function logout(Request $request)
    {
        JWTAuth::parseToken()->invalidate();

        return response()->json([
            'status' => false,
            'message' => 'Logged out successfully'
        ]);
    }
}
