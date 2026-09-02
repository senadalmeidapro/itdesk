<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $approver_id
 * @property string $decision
 * @property Carbon|null $decided_at
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['ticket_id', 'approver_id', 'decision', 'decided_at', 'comment'])]
class Approval extends Model
{
    protected function casts(): array
    {
        return [
            'decided_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function approve(?string $comment = null): void
    {
        $this->update([
            'decision' => 'approved',
            'decided_at' => now(),
            'comment' => $comment,
        ]);

        $this->ticket->transitionTo('assigned');
    }

    public function reject(?string $comment = null): void
    {
        $this->update([
            'decision' => 'rejected',
            'decided_at' => now(),
            'comment' => $comment,
        ]);

        $this->ticket->transitionTo('closed');
    }
}