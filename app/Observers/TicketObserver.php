<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketResolvedNotification;

class TicketObserver
{
    public function updated(Ticket $ticket): void
    {
        if ($ticket->wasChanged('assigned_agent_id') && $ticket->assigned_agent_id) {
            $ticket->assignedAgent->notify(new TicketAssignedNotification($ticket));
        }

        if ($ticket->wasChanged('status') && $ticket->status === 'resolved') {
            $ticket->requester->notify(new TicketResolvedNotification($ticket));
        }
    }
}