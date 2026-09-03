<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SlaBreachedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $breachType, // 'response' or 'resolution'
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = $this->breachType === 'response' ? 'first response' : 'resolution';

        return (new MailMessage)
            ->subject("SLA breach — Ticket #{$this->ticket->id}")
            ->line("Ticket #{$this->ticket->id} ('{$this->ticket->title}') has missed its {$label} SLA.")
            ->line("Priority: {$this->ticket->priority}")
            ->action('View ticket', route('tickets.show', $this->ticket))
            ->line('Please review and take action.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'breach_type' => $this->breachType,
        ];
    }
}