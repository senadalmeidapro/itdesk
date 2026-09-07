<?php

namespace Tests\Feature;

use App\Livewire\Assets\AssetEdit;
use App\Livewire\Assets\AssetShow;
use App\Models\Asset;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AssetEditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(PermissionsSeeder::class);
    }

    public function test_network_tech_can_edit_asset(): void
    {
        $tech = User::factory()->create();
        $tech->assignRole('network_tech');

        $asset = Asset::factory()->create([
            'name' => 'Old name',
            'location' => 'Old location',
        ]);

        Livewire::actingAs($tech)
            ->test(AssetEdit::class, ['asset' => $asset])
            ->set('name', 'New name')
            ->set('location', 'New location')
            ->call('save')
            ->assertRedirect(route('assets.show', $asset));

        $this->assertSame('New name', $asset->fresh()->name);
        $this->assertSame('New location', $asset->fresh()->location);
    }

    public function test_agent_cannot_edit_asset(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('agent');

        $asset = Asset::factory()->create();

        Livewire::actingAs($agent)
            ->test(AssetEdit::class, ['asset' => $asset])
            ->assertForbidden();
    }

    public function test_admin_can_delete_asset(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $asset = Asset::factory()->create();

        Livewire::actingAs($admin)
            ->test(AssetShow::class, ['asset' => $asset])
            ->call('delete')
            ->assertRedirect(route('assets.index'));

        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }
}
