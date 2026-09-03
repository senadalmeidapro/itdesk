<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(PermissionsSeeder::class);
    }

    public function test_requester_cannot_delete_own_ticket(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->create(['requester_id' => $requester->id]);

        $this->assertFalse($requester->can('delete', $ticket));
    }

    public function test_admin_can_delete_any_ticket(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $ticket = Ticket::factory()->create();

        $this->assertTrue($admin->can('delete', $ticket));
    }

    public function test_requester_can_update_own_open_ticket_but_not_after_assignment(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->withStatus('open')->create(['requester_id' => $requester->id]);
        $this->assertTrue($requester->can('update', $ticket));

        $ticket->transitionTo('assigned');
        $this->assertFalse($requester->fresh()->can('update', $ticket->fresh()));
    }

    public function test_requester_cannot_view_others_tickets(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $otherTicket = Ticket::factory()->create();

        $this->assertFalse($requester->can('view', $otherTicket));
    }

    public function test_agent_can_view_all_tickets_but_not_delete(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $ticket = Ticket::factory()->create();

        $this->assertTrue($agent->can('view', $ticket));
        $this->assertTrue($agent->can('transition', $ticket));
        $this->assertFalse($agent->can('delete', $ticket));
    }

    public function test_only_admin_can_approve(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $ticket = Ticket::factory()->ofType('change')->withStatus('pending_approval')->create();

        $this->assertTrue($admin->can('approve', $ticket));
        $this->assertFalse($agent->can('approve', $ticket));
    }
}