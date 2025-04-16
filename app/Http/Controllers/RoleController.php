<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    public function index()
    {
        // sleep(2);
        // $roles = Role::with('permissions')->get();
        $roles = Role::all();
        return RoleResource::collection($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:roles,name']
        ]);

        $role = Role::create($validated);
        return new RoleResource($role);
    }

    public function show(Role $role)
    {

        $role->load('permissions');
        return new RoleResource($role);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'unique:roles,name,' . $role->id]

        ]);
        $role->update($validated);

        return new RoleResource($role);
    }

    public function destroy(Role $role)
    {
        // dd($role);
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully'], 204);
    }
}
