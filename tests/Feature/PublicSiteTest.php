<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\NewContactMessageNotification;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionsSeeder::class);
    }

    public function test_home_page_is_displayed(): void
    {
        $this->get('/')->assertOk()->assertSee('TAKTIC');
    }

    public function test_services_page_is_displayed(): void
    {
        $this->get('/services')->assertOk()->assertSee('Intervention & maintenance');
    }

    public function test_contact_page_is_displayed(): void
    {
        $this->get('/contact')->assertOk()->assertSee('Décrivez-nous votre besoin');
    }

    public function test_contact_form_stores_message_and_notifies_admins(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $admin->givePermissionTo('settings.manage');

        $response = $this->post('/contact', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'phone' => '0600000000',
            'audience' => 'entreprise',
            'subject' => 'Contrat de maintenance',
            'message' => 'Bonjour, nous cherchons un contrat de maintenance pour notre parc de 12 postes.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'audience' => 'entreprise',
            'is_read' => false,
        ]);

        Notification::assertSentTo($admin, NewContactMessageNotification::class);
    }

    public function test_contact_form_validation(): void
    {
        $this->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
        ])->assertSessionHasErrors(['name', 'email', 'audience', 'subject', 'message']);
    }
}
