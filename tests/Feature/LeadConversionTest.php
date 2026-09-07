<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use App\Services\LeadConverter;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeadConversionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([PermissionsSeeder::class, RolesAndPermissionsSeeder::class]);
    }

    private function makeLead(array $attributes = []): ContactMessage
    {
        return ContactMessage::create(array_merge([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'phone' => '0600000000',
            'audience' => 'entreprise',
            'service_slug' => 'support-helpdesk',
            'subject' => 'Contrat de maintenance',
            'message' => 'Nous voulons un contrat de maintenance pour notre parc de douze postes.',
            'status' => ContactMessage::STATUS_CONTACTED,
        ], $attributes));
    }

    public function test_lead_is_converted_into_assigned_service_ticket(): void
    {
        Notification::fake();

        $lead = $this->makeLead();
        $client = User::factory()->create(['email' => 'client@example.com']);
        $agent = User::factory()->create(['email' => 'agent@example.com']);

        $ticket = app(LeadConverter::class)->convert($lead, [
            'client_id' => $client->id,
            'assigned_agent_id' => $agent->id,
            'priority' => 'high',
        ]);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'requester_id' => $client->id,
            'assigned_agent_id' => $agent->id,
            'type' => 'service_request',
            'status' => 'assigned',
            'priority' => 'high',
        ]);

        $lead->refresh();
        $this->assertSame(ContactMessage::STATUS_CONVERTED, $lead->status);
        $this->assertSame($ticket->id, $lead->converted_ticket_id);
        $this->assertNotNull($lead->converted_at);

        Notification::assertSentTo($agent, TicketAssignedNotification::class);
    }

    public function test_conversion_is_idempotent(): void
    {
        $lead = $this->makeLead();
        $client = User::factory()->create();

        $first = app(LeadConverter::class)->convert($lead, ['client_id' => $client->id]);
        $second = app(LeadConverter::class)->convert($lead, ['client_id' => $client->id]);

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, Ticket::count());
    }

    public function test_contacted_and_rejected_leads_keep_status(): void
    {
        $lead = $this->makeLead();
        $lead->markContacted();
        $lead->refresh();
        $this->assertSame(ContactMessage::STATUS_CONTACTED, $lead->status);

        $lead->markRejected();
        $lead->refresh();
        $this->assertSame(ContactMessage::STATUS_REJECTED, $lead->status);

        $lead->markContacted();
        $lead->refresh();
        $this->assertSame(ContactMessage::STATUS_REJECTED, $lead->status);
    }
}
