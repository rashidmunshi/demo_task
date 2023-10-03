<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    function register(RegisterUserRequest $request)
    {
        $request['password'] = bcrypt($request['password']);
        if ($request->hasFile('image')) {
            $request['image'] = $request->file('image')->store('images', 'public');
        }
        $user = User::create($request->validated());
        $token = JWTAuth::fromUser($user);
        return (new UserResource($user))->additional([
            'status'=>true,
            'message' => 'User Registered Successfully',
            'token' => $token
        ]);
    }
}
