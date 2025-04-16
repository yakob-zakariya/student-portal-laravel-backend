<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class TeacherUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $users = User::role('teacher')->with('teacher')->get();
        return response()->json($users);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // sleep(5);
        $userResource = DB::transaction(function () use ($request) {
            $userController = new UserController();
            $userResource = $userController->store($request);
            $user = $userResource->resource;

            $user->teacher()->create();

            return new UserResource($user);
        });

        return $userResource;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // sleep(5);
        $validated = $request->validated();

        $user->update($validated);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 204);
    }
}
