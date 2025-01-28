<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Services\UsernameGenerator;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Coordinator;

class CoordinatorUserController extends Controller
{
    public function index()
    {
        $users = User::role('coordinator')->get();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        $validated['department_id'] = $request->department_id;
        $validated['username'] = UsernameGenerator::generate('coordinator');
        $validated['password'] = bcrypt("password");


        $user = User::create($validated);
        $user->assignRole('coordinator');
        Coordinator::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id']
        ]);


        $user->load('coordinator.department');
        return new UserResource($user);
    }

    public function show(User $user)
    {

        $user->load('coordinator.department');
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update($validated);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => "user deleted Successfully"
        ]);
    }
}
