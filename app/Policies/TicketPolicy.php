<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Anyone authenticated can list tickets; the controller/query
     * scopes results to what they're allowed to see (own tickets
     * for requesters, all tickets for agents/admins).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole(['admin', 'agent', 'network_tech'])
            || $ticket->requester_id === $user->id;
    }

    /**
     * Anyone can create a ticket (submit a request or report an incident).
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only agents/admins can update ticket status, assignment, etc.
     * Requesters can only comment (handled separately in CommentPolicy)
     * or update their own ticket while it's still "open".
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->hasAnyRole(['admin', 'agent', 'network_tech'])) {
            return true;
        }

        return $ticket->requester_id === $user->id && $ticket->status === 'open';
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Transition status (assign, resolve, close, reopen).
     * Reserved for agents/admins - requesters never drive status directly.
     */
    public function transition(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole(['admin', 'agent', 'network_tech']);
    }

    /**
     * Approve/reject a pending_approval ticket. Only admins for now -
     * refine later if approvals need to route to specific managers.
     */
    public function approve(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }
}