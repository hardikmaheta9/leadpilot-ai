<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // All system permissions
        $permissions = [

            // Dashboard
            'view dashboard',

            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Companies
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',

            // Contacts
            'view contacts',
            'create contacts',
            'edit contacts',
            'delete contacts',

            // Leads
            'view leads',
            'create leads',
            'edit leads',
            'delete leads',

            // AI
            'use ai',

            // Reports
            'view reports',

            // Settings
            'manage settings',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $tenantAdmin = Role::firstOrCreate([
            'name' => 'Tenant Admin',
            'guard_name' => 'web',
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        $employee = Role::firstOrCreate([
            'name' => 'Employee',
            'guard_name' => 'web',
        ]);

        // Super Admin gets everything
        $superAdmin->syncPermissions(Permission::all());

        // Tenant Admin Permissions
        $tenantAdmin->syncPermissions([
            'view dashboard',

            'view companies',
            'create companies',
            'edit companies',
            'delete companies',

            'view contacts',
            'create contacts',
            'edit contacts',
            'delete contacts',

            'view leads',
            'create leads',
            'edit leads',
            'delete leads',

            'view reports',
            'use ai',
        ]);

        // Manager Permissions
        $manager->syncPermissions([
            'view dashboard',

            'view companies',
            'create companies',
            'edit companies',

            'view contacts',
            'create contacts',
            'edit contacts',

            'view leads',
            'create leads',
            'edit leads',

            'view reports',
            'use ai',
        ]);

        // Employee Permissions
        $employee->syncPermissions([
            'view dashboard',

            'view companies',

            'view contacts',

            'view leads',

            'use ai',
        ]);

        // Assign first registered user as Super Admin
        $user = User::first();

        if ($user) {
            $user->assignRole($superAdmin);
        }
    }
}