<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class PermissionsController extends Controller
{
    /**
     * Display a listing of all roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * Create predefined roles: Manager, Admin, Student.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDefaultRoles()
    {
        $roles = ['Manager', 'Admin', 'Student'];
        $createdRoles = [];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $createdRoles[] = $role;
        }

        return response()->json($createdRoles, 201);
    }

    /**
     * Store a newly created role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json($role, 201);
    }

    /**
     * Display the specified role.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json($role);
    }

    /**
     * Update the specified role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $userId = Auth::id();
        $user = \App\Models\User::findOrFail($userId);
        if (!$user->can('edit permissions')) {
            return response()->json(['error' => 'You do not have permission to manage roles'], 403);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array', // Ensure 'permissions' is an array
            'permissions.*' => 'string|exists:permissions,name', // Each permission must exist
        ]);

        // Update the role name
        $role->update(['name' => $request->name]);

        // Sync the permissions for the role
        $role->syncPermissions($request->permissions);

        // Return the updated role and its permissions
        return response()->json([
            'role' => $role,
            'permissions' => $role->permissions,
        ]);
    }

    /**
     * Remove the specified role.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(null, 204);
    }
}
