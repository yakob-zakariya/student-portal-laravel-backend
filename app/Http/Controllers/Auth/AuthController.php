<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

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
            ]);
        }

        $user = Auth::user();
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

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response([
            'message' => 'Logged out'
        ]);
    }
}
