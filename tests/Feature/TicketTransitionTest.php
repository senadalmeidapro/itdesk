<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTransitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_open_ticket_can_be_assigned(): void
    {
        $ticket = Ticket::factory()->withStatus('open')->create();

        $ticket->transitionTo('assigned');

        $this->assertSame('assigned', $ticket->fresh()->status);
    }

    public function test_service_request_moves_to_pending_approval_first(): void
    {
        $ticket = Ticket::factory()->ofType('service_request')->withStatus('open')->create();

        $this->assertTrue($ticket->requiresApproval());
        $this->assertTrue($ticket->canTransitionTo('pending_approval'));

        $ticket->transitionTo('pending_approval');

        $this->assertSame('pending_approval', $ticket->status);
    }

    public function test_incident_does_not_require_approval(): void
    {
        $ticket = Ticket::factory()->ofType('incident')->create();

        $this->assertFalse($ticket->requiresApproval());
    }

    public function test_full_lifecycle_to_resolved_and_closed(): void
    {
        $ticket = Ticket::factory()->withStatus('open')->create();

        $ticket->transitionTo('assigned');
        $ticket->transitionTo('in_progress');
        $ticket->transitionTo('resolved');

        $this->assertSame('resolved', $ticket->status);
        $this->assertNotNull($ticket->resolved_at);

        $ticket->transitionTo('closed');

        $this->assertSame('closed', $ticket->status);
        $this->assertNotNull($ticket->closed_at);
    }

    public function test_resolved_ticket_can_be_reopened(): void
    {
        $ticket = Ticket::factory()->withStatus('resolved')->create([
            'resolved_at' => now(),
        ]);

        $this->assertTrue($ticket->canTransitionTo('assigned'));

        $ticket->transitionTo('assigned');

        $this->assertSame('assigned', $ticket->status);
        $this->assertNull($ticket->resolved_at, 'Reopening should clear resolved_at.');
    }

    /**
     * Core business rule: a closed ticket is terminal. It cannot be
     * reopened - only a resolved ticket can. This is the rule Sena
     * explicitly locked in during domain modeling.
     */
    public function test_closed_ticket_cannot_be_reopened(): void
    {
        $ticket = Ticket::factory()->withStatus('closed')->create([
            'closed_at' => now(),
        ]);

        $this->assertFalse($ticket->canTransitionTo('assigned'));

        $this->expectException(\DomainException::class);

        $ticket->transitionTo('assigned');
    }

    public function test_closed_ticket_has_no_valid_transitions_at_all(): void
    {
        $ticket = Ticket::factory()->withStatus('closed')->create();

        $this->assertSame([], Ticket::TRANSITIONS['closed']);

        foreach (['open', 'pending_approval', 'assigned', 'in_progress', 'pending', 'resolved', 'closed'] as $status) {
            $this->assertFalse($ticket->canTransitionTo($status));
        }
    }

    public function test_invalid_transition_throws_domain_exception(): void
    {
        $ticket = Ticket::factory()->withStatus('open')->create();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Cannot transition ticket #{$ticket->id} from 'open' to 'resolved'.");

        // Skipping straight from open to resolved is not an allowed transition.
        $ticket->transitionTo('resolved');
    }

    public function test_pending_approval_can_be_rejected_to_closed(): void
    {
        $ticket = Ticket::factory()->ofType('change')->withStatus('pending_approval')->create();

        $ticket->transitionTo('closed');

        $this->assertSame('closed', $ticket->status);
    }
}