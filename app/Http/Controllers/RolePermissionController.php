<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use App\Htpp\Resources\RoleResource;

class RolePermissionController extends Controller
{

    public function permissions()
    {
        $permissions = Permission::all();
        return PermissionResource::collection($permissions);
    }



    public function assignPermissionsToRole(Request $request, Role $role)
    {

        $validated = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);




        $permissions = Permission::whereIn('id', $validated['permissions'])->get();


        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Permissions assigned to role successfully',
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function revokePermissionFromRole(Request $request, Role $role)
    {

        $validated = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'])->get();

        foreach ($permissions as $permission) {
            $role->revokePermissionTo($permission);
        }

        return response()->json([
            'message' => 'Permissions revoked from role successfully',
            'role' => $role,
            'permissions' => $permissions
        ]);
    }
}
