<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $priority
 * @property int $response_time_minutes
 * @property int $resolution_time_minutes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'priority', 'response_time_minutes', 'resolution_time_minutes'])]
class SlaPolicy extends Model
{
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Resolve the SLA policy that should apply to a given ticket:
     * 1. The ticket's category default, if set.
     * 2. Otherwise, the policy matching the ticket's priority.
     * 3. Null if neither exists (ticket gets no SLA tracking).
     */
    public static function resolveFor(Ticket $ticket): ?self
    {
        if ($ticket->category?->default_sla_policy_id) {
            $policy = self::find($ticket->category->default_sla_policy_id);
            if ($policy) {
                return $policy;
            }
        }

        return self::where('priority', $ticket->priority)->first();
    }
}