<?php

namespace App\Notifications;

use App\Models\TicketComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class TicketCommentedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public TicketComment $comment) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ticket = $this->comment->ticket;
        $excerpt = Str::limit($this->comment->body, 140);

        return (new MailMessage)
            ->subject("New comment on ticket #{$ticket->id}")
            ->line("{$this->comment->user->name} commented on ticket #{$ticket->id}: '{$ticket->title}'.")
            ->line("\"{$excerpt}\"")
            ->action('View ticket', route('tickets.show', $ticket));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->comment->ticket_id,
            'comment_id' => $this->comment->id,
            'commenter' => $this->comment->user->name,
            'event' => 'commented',
        ];
    }
}
