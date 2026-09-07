<?php

namespace Tests\Feature;

use App\Livewire\Tickets\TicketEdit;
use App\Livewire\Tickets\TicketShow;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketEditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(PermissionsSeeder::class);
    }

    public function test_invalid_transition_throws_domain_exception(): void
    {
        $ticket = Ticket::factory()->withStatus('closed')->create();

        $this->expectException(\DomainException::class);

        $ticket->transitionTo('open');
    }

    public function test_agent_can_edit_ticket_fields(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $ticket = Ticket::factory()->create();

        Livewire::actingAs($agent)
            ->test(TicketEdit::class, ['ticket' => $ticket])
            ->set('title', 'Updated title')
            ->set('description', 'Updated description')
            ->set('priority', 'high')
            ->call('save')
            ->assertRedirect(route('tickets.show', $ticket));

        $this->assertSame('Updated title', $ticket->fresh()->title);
        $this->assertSame('Updated description', $ticket->fresh()->description);
        $this->assertSame('high', $ticket->fresh()->priority);
    }

    public function test_requester_can_edit_own_open_ticket(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->withStatus('open')->create(['requester_id' => $requester->id]);

        Livewire::actingAs($requester)
            ->test(TicketEdit::class, ['ticket' => $ticket])
            ->set('title', 'My own update')
            ->call('save');

        $this->assertSame('My own update', $ticket->fresh()->title);
    }

    public function test_requester_cannot_edit_assigned_ticket(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->withStatus('assigned')->create(['requester_id' => $requester->id]);

        Livewire::actingAs($requester)
            ->test(TicketEdit::class, ['ticket' => $ticket])
            ->assertForbidden();
    }

    public function test_delete_removes_ticket_and_redirects(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $ticket = Ticket::factory()->create();

        Livewire::actingAs($admin)
            ->test(TicketShow::class, ['ticket' => $ticket])
            ->call('delete')
            ->assertRedirect(route('tickets.index'));

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_agent_cannot_delete_ticket(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $ticket = Ticket::factory()->create();

        Livewire::actingAs($agent)
            ->test(TicketShow::class, ['ticket' => $ticket])
            ->call('delete')
            ->assertForbidden();

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id]);
    }
}
