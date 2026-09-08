<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_CONVERTED = 'converted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_NEW => 'Nouveau',
        self::STATUS_CONTACTED => 'Contacté',
        self::STATUS_CONVERTED => 'Converti',
        self::STATUS_REJECTED => 'Rejeté',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'audience',
        'service_slug',
        'subject',
        'message',
        'form_data',
        'is_read',
        'status',
        'converted_ticket_id',
        'contacted_at',
        'converted_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'form_data' => 'array',
        'contacted_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function convertedTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'converted_ticket_id');
    }

    public function serviceName(): ?string
    {
        return collect(config('public-services.services'))
            ->firstWhere('slug', $this->service_slug)['name'] ?? null;
    }

    public function formSchema(): array
    {
        return (array) config('service-form-fields.'.$this->service_slug, []);
    }

    /**
     * Réponses du formulaire personnalisé, prêtes à l'affichage
     * (valeur des select résolue en libellé).
     *
     * @return array<string, string> label => valeur
     */
    public function formAnswers(): array
    {
        $answers = [];

        foreach ($this->formSchema() as $field) {
            $value = $this->form_data[$field['name']] ?? null;
            if ($value === null || $value === '') {
                continue;
            }
            if (($field['type'] ?? null) === 'select') {
                $value = $field['options'][$value] ?? $value;
            }
            $answers[$field['label']] = (string) $value;
        }

        return $answers;
    }

    public function markContacted(): void
    {
        if (in_array($this->status, [self::STATUS_CONVERTED, self::STATUS_REJECTED], true)) {
            return;
        }

        $this->update([
            'status' => self::STATUS_CONTACTED,
            'contacted_at' => $this->contacted_at ?? now(),
            'is_read' => true,
        ]);
    }

    public function markRejected(): void
    {
        if ($this->status === self::STATUS_CONVERTED) {
            return;
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'is_read' => true,
        ]);
    }

    public function markConverted(Ticket $ticket): void
    {
        $this->update([
            'status' => self::STATUS_CONVERTED,
            'converted_ticket_id' => $ticket->id,
            'converted_at' => now(),
            'is_read' => true,
        ]);

        unset($this->relations['convertedTicket']);
    }
}
