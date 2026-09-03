<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $body
 * @property bool $is_internal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperTicketComment
 */
#[Fillable(['ticket_id', 'user_id', 'body', 'is_internal'])]
class TicketComment extends Model
{
    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}