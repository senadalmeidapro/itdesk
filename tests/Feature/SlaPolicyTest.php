<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SlaPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_applying_sla_by_priority_sets_due_dates(): void
    {
        SlaPolicy::create([
            'name' => 'High priority',
            'priority' => 'high',
            'response_time_minutes' => 60,
            'resolution_time_minutes' => 480,
        ]);

        $ticket = Ticket::factory()->create(['priority' => 'high', 'category_id' => null]);
        $ticket->applySlaPolicy();
        $ticket->refresh();

        $this->assertNotNull($ticket->sla_policy_id);
        $this->assertEqualsWithDelta(
            $ticket->created_at->copy()->addMinutes(60)->timestamp,
            $ticket->sla_response_due_at->timestamp,
            2
        );
        $this->assertEqualsWithDelta(
            $ticket->created_at->copy()->addMinutes(480)->timestamp,
            $ticket->sla_resolution_due_at->timestamp,
            2
        );
    }

    public function test_category_default_policy_takes_priority_over_ticket_priority_match(): void
    {
        $categoryPolicy = SlaPolicy::create([
            'name' => 'Category override',
            'priority' => 'low',
            'response_time_minutes' => 15,
            'resolution_time_minutes' => 60,
        ]);

        SlaPolicy::create([
            'name' => 'Medium default',
            'priority' => 'medium',
            'response_time_minutes' => 120,
            'resolution_time_minutes' => 1440,
        ]);

        $category = Category::create([
            'name' => 'Network outage',
            'default_sla_policy_id' => $categoryPolicy->id,
        ]);

        $ticket = Ticket::factory()->create([
            'priority' => 'medium',
            'category_id' => $category->id,
        ]);

        $ticket->applySlaPolicy();
        $ticket->refresh();

        $this->assertSame($categoryPolicy->id, $ticket->sla_policy_id);
    }

    public function test_ticket_with_no_matching_policy_gets_no_sla(): void
    {
        $ticket = Ticket::factory()->create(['priority' => 'critical', 'category_id' => null]);

        $ticket->applySlaPolicy();
        $ticket->refresh();

        $this->assertNull($ticket->sla_policy_id);
        $this->assertNull($ticket->sla_response_due_at);
    }

    public function test_resolution_breach_detected_after_due_date_passes(): void
    {
        $ticket = Ticket::factory()->withStatus('in_progress')->create([
            'sla_resolution_due_at' => Carbon::now()->subMinute(),
        ]);

        $this->assertTrue($ticket->isResolutionBreached());
    }

    public function test_resolved_ticket_is_never_considered_breached(): void
    {
        $ticket = Ticket::factory()->withStatus('resolved')->create([
            'sla_resolution_due_at' => Carbon::now()->subDay(),
            'resolved_at' => now(),
        ]);

        $this->assertFalse($ticket->isResolutionBreached());
    }

    public function test_response_breach_only_applies_while_still_open(): void
    {
        $openTicket = Ticket::factory()->withStatus('open')->create([
            'sla_response_due_at' => Carbon::now()->subMinute(),
        ]);
        $this->assertTrue($openTicket->isResponseBreached());

        $assignedTicket = Ticket::factory()->withStatus('assigned')->create([
            'sla_response_due_at' => Carbon::now()->subMinute(),
        ]);
        $this->assertFalse($assignedTicket->isResponseBreached());
    }
}