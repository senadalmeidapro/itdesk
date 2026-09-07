<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigning_an_unassigned_asset_creates_one_history_row(): void
    {
        $asset = Asset::factory()->create(['status' => 'in_stock']);
        $user = User::factory()->create();

        $asset->assignTo($user);

        $this->assertSame($user->id, $asset->fresh()->assigned_user_id);
        $this->assertSame('in_use', $asset->fresh()->status);
        $this->assertCount(1, $asset->assignments);
        $this->assertNull($asset->assignments->first()->unassigned_at);
    }

    public function test_reassigning_closes_previous_assignment_and_logs_new_one(): void
    {
        $asset = Asset::factory()->create();
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $asset->assignTo($firstUser);
        $asset->assignTo($secondUser);

        $asset->refresh();

        $this->assertSame($secondUser->id, $asset->assigned_user_id);
        $this->assertCount(2, $asset->assignments);

        $previous = $asset->assignments()->where('user_id', $firstUser->id)->first();
        $current = $asset->assignments()->where('user_id', $secondUser->id)->first();

        $this->assertNotNull($previous->unassigned_at, 'Previous assignment should be closed out.');
        $this->assertNull($current->unassigned_at, 'Current assignment should remain open.');
    }
}
