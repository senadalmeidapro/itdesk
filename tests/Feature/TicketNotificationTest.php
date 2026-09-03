<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketCommentedNotification;
use App\Notifications\TicketResolvedNotification;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TicketNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(PermissionsSeeder::class);
    }

    public function test_assigning_an_agent_notifies_them(): void
    {
        Notification::fake();

        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $ticket = Ticket::factory()->create();

        $ticket->update(['assigned_agent_id' => $agent->id]);

        Notification::assertSentTo($agent, TicketAssignedNotification::class);
    }

    public function test_reassigning_to_the_same_agent_does_not_renotify(): void
    {
        Notification::fake();

        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $ticket = Ticket::factory()->create(['assigned_agent_id' => $agent->id]);

        // No actual change - assigning the same agent again.
        $ticket->update(['assigned_agent_id' => $agent->id]);

        Notification::assertNotSentTo($agent, TicketAssignedNotification::class);
    }

    public function test_resolving_a_ticket_notifies_the_requester(): void
    {
        Notification::fake();

        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->withStatus('in_progress')->create(['requester_id' => $requester->id]);

        $ticket->transitionTo('resolved');

        Notification::assertSentTo($requester, TicketResolvedNotification::class);
    }

    public function test_closing_a_ticket_does_not_trigger_resolved_notification(): void
    {
        Notification::fake();

        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->withStatus('resolved')->create(['requester_id' => $requester->id]);

        $ticket->transitionTo('closed');

        Notification::assertNotSentTo($requester, TicketResolvedNotification::class);
    }

    public function test_requester_comment_notifies_assigned_agent(): void
    {
        Notification::fake();

        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->create([
            'requester_id' => $requester->id,
            'assigned_agent_id' => $agent->id,
        ]);

        $ticket->comments()->create([
            'user_id' => $requester->id,
            'body' => 'Any update?',
            'is_internal' => false,
        ]);

        Notification::assertSentTo($agent, TicketCommentedNotification::class);
    }

    public function test_staff_public_comment_notifies_requester_but_internal_note_does_not(): void
    {
        Notification::fake();

        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->create([
            'requester_id' => $requester->id,
            'assigned_agent_id' => $agent->id,
        ]);

        $ticket->comments()->create([
            'user_id' => $agent->id,
            'body' => 'Working on it.',
            'is_internal' => false,
        ]);

        Notification::assertSentTo($requester, TicketCommentedNotification::class);

        Notification::fake(); // reset counts

        $ticket->comments()->create([
            'user_id' => $agent->id,
            'body' => 'Internal note: escalating to network team.',
            'is_internal' => true,
        ]);

        Notification::assertNotSentTo($requester, TicketCommentedNotification::class);
    }
}