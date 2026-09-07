<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tickets.view_own') || $user->can('tickets.view_all');
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->can('tickets.view_all')
            || ($user->can('tickets.view_own') && $ticket->requester_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->can('tickets.create');
    }

    /**
     * update_any covers agents/admins editing any ticket.
     * update_own covers a requester editing their own ticket, but only
     * while it's still "open" — that business rule stays here since
     * it depends on ticket state, not just permission.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->can('tickets.update_any')) {
            return true;
        }

        return $user->can('tickets.update_own')
            && $ticket->requester_id === $user->id
            && $ticket->status === 'open';
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->can('tickets.delete');
    }

    public function transition(User $user, Ticket $ticket): bool
    {
        return $user->can('tickets.transition');
    }

    public function approve(User $user, Ticket $ticket): bool
    {
        return $user->can('tickets.approve');
    }

    public function comment(User $user, Ticket $ticket): bool
    {
        return $user->can('tickets.comment') && $this->view($user, $ticket);
    }

    public function commentInternal(User $user, Ticket $ticket): bool
    {
        return $user->can('tickets.comment_internal');
    }
}
