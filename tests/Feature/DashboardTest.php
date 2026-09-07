<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(PermissionsSeeder::class);
    }

    public function test_requester_can_access_personal_dashboard(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $this->actingAs($requester)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Nouvelle demande');
    }

    public function test_agent_can_access_dashboard(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $this->actingAs($agent)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_sla_compliance_rate_is_computed_correctly(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('agent');

        // One resolved on time, one resolved late.
        Ticket::factory()->withStatus('resolved')->create([
            'sla_resolution_due_at' => Carbon::now()->addHour(),
            'resolved_at' => Carbon::now(),
        ]);

        Ticket::factory()->withStatus('resolved')->create([
            'sla_resolution_due_at' => Carbon::now()->subHour(),
            'resolved_at' => Carbon::now(),
        ]);

        Livewire::actingAs($agent)
            ->test(Dashboard::class)
            ->assertSee('50%');
    }

    public function test_currently_breached_count_excludes_resolved_tickets(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('agent');

        // Breached and still open.
        Ticket::factory()->withStatus('in_progress')->create([
            'sla_resolution_due_at' => Carbon::now()->subHour(),
        ]);

        // Would have breached, but already resolved - should not count.
        Ticket::factory()->withStatus('resolved')->create([
            'sla_resolution_due_at' => Carbon::now()->subHour(),
            'resolved_at' => Carbon::now(),
        ]);

        Livewire::actingAs($agent)
            ->test(Dashboard::class)
            ->assertViewHas('sla', fn ($sla) => $sla['currently_breached'] === 1);
    }
}
