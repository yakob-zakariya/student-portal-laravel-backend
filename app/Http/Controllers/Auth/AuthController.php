<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="User Login",
 *     description="Authenticate user and return a token",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="your-jwt-token")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // sleep(2);
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
            ], 422);
        }




        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response([
            'token' => $token,
            'user' => $user,
            'role' => $user->roles()->first()->name
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
