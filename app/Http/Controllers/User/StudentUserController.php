<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\Role as RoleEnum;
use App\Http\Resources\UserResource;


class StudentUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::role(RoleEnum::STUDENT)->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $userResource = DB::transaction(function () use ($request) {
            $userController = new UserController();

            $userResource = $userController->store($request);

            $user = $userResource->resource;

            $validated = $request->validate([
                'batch_id' => ['required', 'integer', 'exists:batches,id'],
                'section_id' => ['sometimes', 'integer', 'exists:sections,id']
            ]);
            $user->student()->create($validated);

            return $userResource;
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
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->student()->delete();
            $user->delete();
        });

        return response()->noContent();
    }
}
