<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Support\Facades\DB;

class LeadConverter
{
    /**
     * Convertit une demande site en ticket « service_request » déjà affecté.
     * La validation a déjà eu lieu (humainement) avant cet appel : le ticket
     * démarre donc directement en statut « assigned ».
     *
     * @param  array{client_id: int, title?: string, category_id?: int|null, priority?: string, assigned_agent_id?: int|null}  $data
     */
    public function convert(ContactMessage $lead, array $data): Ticket
    {
        if ($lead->converted_ticket_id !== null) {
            return $lead->convertedTicket;
        }

        return DB::transaction(function () use ($lead, $data): Ticket {
            $lead = ContactMessage::query()->lockForUpdate()->findOrFail($lead->id);

            if ($lead->converted_ticket_id !== null) {
                return $lead->convertedTicket;
            }
            $requester = User::findOrFail((int) $data['client_id']);

            $ticket = Ticket::create([
                'requester_id' => $requester->id,
                'title' => $data['title'] ?? mb_substr($lead->subject, 0, 80),
                'description' => $this->buildDescription($lead, $data),
                'type' => 'service_request',
                'status' => 'assigned',
                'priority' => $data['priority'] ?? 'medium',
                'category_id' => isset($data['category_id']) && $data['category_id'] !== '' && $data['category_id'] !== null
                    ? (int) $data['category_id']
                    : null,
                'assigned_agent_id' => isset($data['assigned_agent_id']) && $data['assigned_agent_id'] !== '' && $data['assigned_agent_id'] !== null
                    ? (int) $data['assigned_agent_id']
                    : null,
            ]);

            if ($ticket->assigned_agent_id !== null) {
                $ticket->assignedAgent->notify(new TicketAssignedNotification($ticket));
            }

            $lead->markConverted($ticket);

            return $ticket;
        });
    }

    /**
     * @param  array{client_id: int, title?: string, category_id?: int|null, priority?: string, assigned_agent_id?: int|null}  $data
     */
    private function buildDescription(ContactMessage $lead, array $data): string
    {
        $lines = [
            'Ticket créé depuis la demande de contact en ligne.',
            'Service concerné : '.($lead->serviceName() ?? $lead->subject).'.',
            "Auteur : {$lead->name} <{$lead->email}>".($lead->phone ? " — {$lead->phone}" : ''),
            'Profil : '.strtoupper($lead->audience),
            '',
            'Message :',
            $lead->message,
        ];

        return implode("\n", $lines);
    }
}
