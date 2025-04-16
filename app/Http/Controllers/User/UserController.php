<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UsernameGenerator;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;


// normal users like registrar, admin are not related to any other entity in the system
// but users like coordinator, teacher, student are related to other entities in the system

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }




    public function store(Request $request)
    {

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'exists:roles,name'],

        ]);

        $validated['username'] = UsernameGenerator::generate($validated['role']);
        $validated['password'] = bcrypt('password');

        $user = User::create($validated);

        $user->assignRole($validated['role']);

        return new UserResource($user);
    }
}
