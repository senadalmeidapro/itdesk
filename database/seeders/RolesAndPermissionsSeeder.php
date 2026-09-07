<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Roles referenced across TicketPolicy and AssetPolicy:
     * - requester: submits tickets, comments, views own tickets/assets
     * - agent: manages tickets (assign, resolve, comment internally)
     * - network_tech: agent + full asset/CMDB management
     * - admin: everything, including delete and approvals
     */
    public function run(): void
    {
        $roles = ['requester', 'agent', 'network_tech', 'admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
