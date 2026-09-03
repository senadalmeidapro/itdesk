<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\SlaBreachedNotification;
use Illuminate\Console\Command;

class CheckSlaBreaches extends Command
{
    protected $signature = 'tickets:check-sla-breaches';

    protected $description = 'Find tickets that have breached their SLA response or resolution deadline and notify agents';

    public function handle(): int
    {
        $responseBreaches = Ticket::query()
            ->whereNotNull('sla_response_due_at')
            ->where('sla_response_due_at', '<', now())
            ->where('status', 'open')
            ->get();

        $resolutionBreaches = Ticket::query()
            ->whereNotNull('sla_resolution_due_at')
            ->where('sla_resolution_due_at', '<', now())
            ->whereNotIn('status', ['resolved', 'closed'])
            ->get();

        $count = 0;

        foreach ($responseBreaches as $ticket) {
            $this->notifyFor($ticket, 'response');
            $count++;
        }

        foreach ($resolutionBreaches as $ticket) {
            $this->notifyFor($ticket, 'resolution');
            $count++;
        }

        $this->info("Checked SLA breaches: {$count} notification(s) dispatched.");

        return self::SUCCESS;
    }

    protected function notifyFor(Ticket $ticket, string $breachType): void
    {
        // Notify the assigned agent if there is one, otherwise notify
        // everyone with the 'tickets.transition' permission (agents/admins).
        $recipients = $ticket->assigned_agent_id
            ? User::where('id', $ticket->assigned_agent_id)->get()
            : User::permission('tickets.transition')->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(new SlaBreachedNotification($ticket, $breachType));
        }
    }
}