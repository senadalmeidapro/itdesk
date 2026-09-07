<?php

namespace Tests\Feature;

use App\Models\Approval;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_moves_ticket_to_assigned(): void
    {
        $ticket = Ticket::factory()->ofType('service_request')->withStatus('pending_approval')->create();
        $approver = User::factory()->create();

        $approval = Approval::create([
            'ticket_id' => $ticket->id,
            'approver_id' => $approver->id,
            'decision' => 'pending',
        ]);

        $approval->approve('Looks good');

        $this->assertSame('approved', $approval->fresh()->decision);
        $this->assertSame('assigned', $ticket->fresh()->status);
        $this->assertNotNull($approval->fresh()->decided_at);
    }

    public function test_rejecting_closes_the_ticket(): void
    {
        $ticket = Ticket::factory()->ofType('change')->withStatus('pending_approval')->create();
        $approver = User::factory()->create();

        $approval = Approval::create([
            'ticket_id' => $ticket->id,
            'approver_id' => $approver->id,
            'decision' => 'pending',
        ]);

        $approval->reject('Too risky');

        $this->assertSame('rejected', $approval->fresh()->decision);
        $this->assertSame('closed', $ticket->fresh()->status);
    }
}
