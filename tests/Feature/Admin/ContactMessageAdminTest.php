<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([PermissionsSeeder::class, RolesAndPermissionsSeeder::class]);
    }

    public function test_admin_can_view_contact_messages_list(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        ContactMessage::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'audience' => 'entreprise',
            'service_slug' => 'support-helpdesk',
            'subject' => 'Contrat de maintenance',
            'message' => 'Nous voulons un contrat de maintenance pour notre parc de douze postes.',
            'status' => ContactMessage::STATUS_NEW,
        ]);

        $this->actingAs($admin)
            ->get('/admin/contact-messages')
            ->assertOk()
            ->assertSee('Demandes de contact')
            ->assertSee('Jean Dupont')
            ->assertSee('Contrat de maintenance')
            ->assertSee('Convertir en ticket');
    }

    public function test_non_admin_is_blocked_from_back_office(): void
    {
        $requester = User::factory()->create();
        $requester->assignRole('requester');

        $this->actingAs($requester)
            ->get('/admin/contact-messages')
            ->assertForbidden();
    }
}
