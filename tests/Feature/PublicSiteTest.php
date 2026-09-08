<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
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

    public function test_service_detail_page_is_displayed(): void
    {
        $this->get('/services/maintenance-depannage')
            ->assertOk()
            ->assertSee('Votre parc, au point', false)
            ->assertSee('Demander ce service');
    }

    public function test_service_detail_page_returns_404_for_unknown_slug(): void
    {
        $this->get('/services/n-existe-pas')->assertNotFound();
    }

    public function test_every_service_has_a_dedicated_illustration_and_renders(): void
    {
        $services = config('public-services.services');

        $this->assertNotEmpty($services, 'No service in the catalog.');

        foreach ($services as $service) {
            $this->assertNotNull($service['illustration'], "Missing illustration for {$service['slug']}.");
            $this->assertFileExists(resource_path("views/components/scene-{$service['illustration']}.blade.php"));

            $this->get(route('services.show', $service['slug']))
                ->assertOk()
                ->assertSee(explode("'", $service['headline'])[0], false);
        }
    }

    public function test_about_page_is_displayed(): void
    {
        $this->get('/a-propos')
            ->assertOk()
            ->assertSee('Vos systèmes, entre', false)
            ->assertSee('de bonnes mains.', false);
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

    public function test_contact_form_stores_service_slug_and_status_new(): void
    {
        Notification::fake();

        $response = $this->post('/contact', [
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'audience' => 'particulier',
            'service_slug' => 'reseaux-connectivite',
            'subject' => 'Problème de réseau / Wi-Fi',
            'message' => 'Le Wi-Fi ne couvre pas le fond de la maison, la box doit probablement être déplacée.',
            'form_data' => [
                'location' => 'maison',
                'user_count' => 6,
                'current_setup' => 'box_fai',
                'wifi_issue' => 'couverture',
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'marie@example.com',
            'service_slug' => 'reseaux-connectivite',
            'status' => 'new',
            'is_read' => false,
        ]);

        $message = ContactMessage::where('email', 'marie@example.com')->firstOrFail();
        $this->assertSame('maison', $message->form_data['location']);
        $this->assertSame('box_fai', $message->form_data['current_setup']);
    }

    public function test_contact_form_stores_custom_form_answers_by_service(): void
    {
        Notification::fake();

        $response = $this->post('/contact', [
            'name' => 'Paul Petit',
            'email' => 'paul@example.com',
            'audience' => 'entreprise',
            'service_slug' => 'maintenance-depannage',
            'subject' => 'Dépannage matériel',
            'message' => 'Un de nos postes de caisse ne démarre plus depuis ce matin.',
            'form_data' => [
                'equipment_type' => 'poste',
                'equipment_count' => 1,
                'intervention_mode' => 'sur_place',
                'urgency' => 'urgente',
            ],
        ]);

        $response->assertRedirect();

        $message = ContactMessage::where('email', 'paul@example.com')->firstOrFail();
        $this->assertSame([
            'equipment_type' => 'poste',
            'equipment_count' => 1,
            'intervention_mode' => 'sur_place',
            'urgency' => 'urgente',
        ], $message->form_data);
    }

    public function test_contact_form_rejects_unknown_form_field(): void
    {
        Notification::fake();

        $this->post('/contact', [
            'name' => 'Julie Rose',
            'email' => 'julie@example.com',
            'audience' => 'particulier',
            'service_slug' => 'maintenance-depannage',
            'subject' => 'Dépannage matériel',
            'message' => 'Mon poste est lent et je souhaite une intervention.',
            'form_data' => [
                'equipment_type' => 'portable',
                'equipment_count' => 1,
                'intervention_mode' => 'sur_place',
                'urgency' => 'normale',
                'hacker_field' => 'injecté',
            ],
        ])->assertRedirect();

        $message = ContactMessage::where('email', 'julie@example.com')->firstOrFail();
        $this->assertArrayNotHasKey('hacker_field', $message->form_data);
    }

    public function test_contact_form_requires_service_specific_fields(): void
    {
        Notification::fake();

        $this->post('/contact', [
            'name' => 'Sonia Durand',
            'email' => 'sonia@example.com',
            'audience' => 'particulier',
            'service_slug' => 'maintenance-depannage',
            'subject' => 'Dépannage matériel',
            'message' => 'Il manque des précisions sur mon équipement.',
        ])->assertSessionHasErrors(['form_data.equipment_type']);

        $this->assertDatabaseMissing('contact_messages', ['email' => 'sonia@example.com']);
    }

    public function test_contact_form_validation(): void
    {
        $this->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
        ])->assertSessionHasErrors(['name', 'email', 'audience', 'subject', 'message']);
    }
}
