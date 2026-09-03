<?php

namespace App\Observers;

use App\Models\TicketComment;
use App\Models\User;
use App\Notifications\TicketCommentedNotification;

class TicketCommentObserver
{
    public function created(TicketComment $comment): void
    {
        $ticket = $comment->ticket;
        $isStaffComment = $comment->user->can('tickets.comment_internal');

        if ($isStaffComment) {
            // Staff commented: notify the requester, unless the note is internal.
            if (! $comment->is_internal && $comment->user_id !== $ticket->requester_id) {
                $ticket->requester->notify(new TicketCommentedNotification($comment));
            }

            // Internal notes: loop in the assigned agent if someone else wrote it.
            if ($comment->is_internal
                && $ticket->assigned_agent_id
                && $ticket->assigned_agent_id !== $comment->user_id) {
                $ticket->assignedAgent->notify(new TicketCommentedNotification($comment));
            }

            return;
        }

        // Requester commented: notify the assigned agent, or all
        // agents/admins with the transition permission if unassigned.
        if ($ticket->assigned_agent_id) {
            $ticket->assignedAgent->notify(new TicketCommentedNotification($comment));

            return;
        }

        User::permission('tickets.transition')->get()->each(
            fn (User $agent) => $agent->notify(new TicketCommentedNotification($comment))
        );
    }
}