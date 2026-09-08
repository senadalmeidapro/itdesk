<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactMessage $message) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Nouvelle demande de contact — {$this->message->subject}")
            ->greeting('Nouvelle demande reçue sur le site')
            ->line("De : {$this->message->name} ({$this->message->email})")
            ->line(strtoupper($this->message->audience).' — '.$this->message->subject)
            ->line($this->message->message);

        if ($this->message->phone) {
            $mail->line("Téléphone : {$this->message->phone}");
        }

        foreach ($this->message->formAnswers() as $label => $value) {
            $mail->line("{$label} : {$value}");
        }

        return $mail;
    }
}
