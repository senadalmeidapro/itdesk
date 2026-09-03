<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Permission list, grouped by resource. Naming convention: "resource.action".
     */
    protected array $permissions = [
        // Tickets
        'tickets.view_own',
        'tickets.view_all',
        'tickets.create',
        'tickets.update_own',
        'tickets.update_any',
        'tickets.delete',
        'tickets.transition',
        'tickets.approve',
        'tickets.comment',
        'tickets.comment_internal',

        // Assets
        'assets.view',
        'assets.view_own',
        'assets.create',
        'assets.update',
        'assets.delete',
        'assets.assign',

        // Categories / SLA / Departments (admin config screens)
        'settings.manage',
    ];

    /**
     * Role -> permissions map. Each role gets exactly these; running this
     * seeder again resets role permissions to this list (idempotent).
     */
    protected array $rolePermissions = [
        'requester' => [
            'tickets.view_own',
            'tickets.create',
            'tickets.update_own',
            'tickets.comment',
            'assets.view_own',
        ],
        'agent' => [
            'tickets.view_all',
            'tickets.create',
            'tickets.update_any',
            'tickets.transition',
            'tickets.comment',
            'tickets.comment_internal',
            'assets.view',
        ],
        'network_tech' => [
            'tickets.view_all',
            'tickets.create',
            'tickets.update_any',
            'tickets.transition',
            'tickets.comment',
            'tickets.comment_internal',
            'assets.view',
            'assets.create',
            'assets.update',
            'assets.assign',
        ],
        'admin' => [
            // admins get everything
            'tickets.view_own', 'tickets.view_all', 'tickets.create',
            'tickets.update_own', 'tickets.update_any', 'tickets.delete',
            'tickets.transition', 'tickets.approve',
            'tickets.comment', 'tickets.comment_internal',
            'assets.view', 'assets.view_own', 'assets.create', 'assets.update', 'assets.delete', 'assets.assign',
            'settings.manage',
        ],
    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach ($this->rolePermissions as $roleName => $permissionNames) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissionNames);
        }
    }
}
