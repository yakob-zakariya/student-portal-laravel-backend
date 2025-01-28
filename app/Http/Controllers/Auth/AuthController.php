<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'The provided credentials are incorrect.',
                'errors' =>
                ['email' => [
                    'The provided credentials are incorrect.'
                ]]
            ], 401);
        }




        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function getUser()
    {
        return new UserResource(Auth::user());
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response([
            'message' => 'Logged out'
        ]);
    }
}
