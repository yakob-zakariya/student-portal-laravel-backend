<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Enums\Role as RoleEnum;
use App\Services\UsernameGenerator;

class RegistrarUserController extends Controller
{
    public function index()
    {
        $users = User::role(RoleEnum::REGISTRAR->value)->get();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['username'] = UsernameGenerator::generate(RoleEnum::REGISTRAR->value);
        // dd($validated);
        $validated['password'] = bcrypt("password");

        $user = User::create($validated);
        $user->assignRole(RoleEnum::REGISTRAR->value);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update($validated);

        return new UserResource($user);
    }


    public function destory(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => "user deleted Successfully"
        ]);
    }
}
