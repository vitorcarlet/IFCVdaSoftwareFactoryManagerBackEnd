<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Resetar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista de permissões
        $permissions = [
            'create users',
            'edit users',
            'delete users',
            'view reports',
            'manage roles',
        ];

        // Criar permissões
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar papéis e associar permissões
        $roles = [
            'admin' => ['create users', 'edit users', 'delete users', 'view reports', 'manage roles'],
            'editor' => ['edit users', 'view reports'],
            'viewer' => ['view reports'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Associar um papel a um usuário de exemplo
        $user = \App\Models\User::first(); // Assumindo que você já tenha um usuário no banco
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
