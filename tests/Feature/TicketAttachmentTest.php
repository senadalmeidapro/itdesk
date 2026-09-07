<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketAttachmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(PermissionsSeeder::class);
    }

    public function test_attachment_is_stored_and_linked_to_ticket(): void
    {
        $ticket = Ticket::factory()->create();
        $uploader = User::factory()->create();

        $file = UploadedFile::fake()->create('screenshot.png', 500, 'image/png');
        $path = $file->store('ticket-attachments/'.$ticket->id, 'local');

        $attachment = $ticket->attachments()->create([
            'user_id' => $uploader->id,
            'original_filename' => 'screenshot.png',
            'disk' => 'local',
            'path' => $path,
            'mime_type' => 'image/png',
            'size_bytes' => $file->getSize(),
        ]);

        Storage::disk('local')->assertExists($path);
        $this->assertSame($ticket->id, $attachment->ticket_id);
        $this->assertSame('500 KB', $attachment->humanSize());
    }

    public function test_deleting_attachment_removes_the_file(): void
    {
        $ticket = Ticket::factory()->create();
        $uploader = User::factory()->create();

        $file = UploadedFile::fake()->create('log.txt', 10);
        $path = $file->store('ticket-attachments/'.$ticket->id, 'local');

        $attachment = $ticket->attachments()->create([
            'user_id' => $uploader->id,
            'original_filename' => 'log.txt',
            'disk' => 'local',
            'path' => $path,
            'size_bytes' => $file->getSize(),
        ]);

        $attachment->deleteWithFile();

        Storage::disk('local')->assertMissing($path);
        $this->assertDatabaseMissing('ticket_attachments', ['id' => $attachment->id]);
    }

    public function test_download_route_is_denied_for_users_who_cannot_view_the_ticket(): void
    {
        $ticket = Ticket::factory()->create();
        $uploader = User::factory()->create();
        $stranger = User::factory()->create();

        $file = UploadedFile::fake()->create('private.pdf', 20);
        $path = $file->store('ticket-attachments/'.$ticket->id, 'local');

        $attachment = $ticket->attachments()->create([
            'user_id' => $uploader->id,
            'original_filename' => 'private.pdf',
            'disk' => 'local',
            'path' => $path,
            'size_bytes' => $file->getSize(),
        ]);

        $this->actingAs($stranger)
            ->get(route('attachments.download', $attachment))
            ->assertForbidden();
    }

    public function test_requester_can_download_attachment_on_own_ticket(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $ticket = Ticket::factory()->create(['requester_id' => $requester->id]);

        $file = UploadedFile::fake()->create('receipt.pdf', 20);
        $path = $file->store('ticket-attachments/'.$ticket->id, 'local');

        $attachment = $ticket->attachments()->create([
            'user_id' => $requester->id,
            'original_filename' => 'receipt.pdf',
            'disk' => 'local',
            'path' => $path,
            'size_bytes' => $file->getSize(),
        ]);

        $this->actingAs($requester)
            ->get(route('attachments.download', $attachment))
            ->assertOk();
    }
}
