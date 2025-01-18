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
     * Display the authenticated user's permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMyPermissions()
    {
        $user = Auth::user();
        $permissions = $user->permissions;

        return response()->json([
            'permissions' => $permissions
        ]);
    }

    /**
     * Display the specified role.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($userId)
    {
        $authId = Auth::id();
        $authUser = \App\Models\User::findOrFail($authId);
        if (!$authUser->can('edit permissions')) {
            return response()->json(['error' => 'You do not have permission to manage roles'], 403);
        }
        $user = \App\Models\User::findOrFail($userId);
        $permissions = $user->permissions;
        return response()->json([
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $userId)
    {
        $authId = Auth::id();
        $authUser = \App\Models\User::findOrFail($authId);
        if (!$authUser->can('edit permissions')) {
            return response()->json(['error' => 'You do not have permission to manage roles'], 403);
        }

        $user = \App\Models\User::findOrFail($userId);


        $request->validate([
            'permissions' => 'required|array', // Ensure 'permissions' is an array
            'permissions.*' => 'string|exists:permissions,name', // Each permission must exist
        ]);

        // Update the role name
        foreach ($request->permissions as $permissionName => $value) {
            if ($value === true) {
                $user->givePermissionTo($permissionName);
            } else {
                $user->revokePermissionTo($permissionName);
            }
        }

        // Return the updated role and its permissions
        return response()->json([
            'permissions' => $user->permissions,
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
