<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $status
 * @property string $priority
 * @property int|null $category_id
 * @property int $requester_id
 * @property int|null $assigned_agent_id
 * @property int|null $sla_policy_id
 * @property Carbon|null $sla_response_due_at
 * @property Carbon|null $sla_resolution_due_at
 * @property Carbon|null $resolved_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'title', 'description', 'type', 'status', 'priority',
    'category_id', 'requester_id', 'assigned_agent_id', 'sla_policy_id',
    'sla_response_due_at', 'sla_resolution_due_at', 'resolved_at', 'closed_at',
])]
class Ticket extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sla_response_due_at' => 'datetime',
            'sla_resolution_due_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Allowed status transitions, keyed by current status.
     * Types that require approval (service_request, change) start at
     * "open" and move to "pending_approval" before "assigned".
     */
    public const TRANSITIONS = [
        'open' => ['pending_approval', 'assigned'],
        'pending_approval' => ['assigned', 'closed'], // closed on rejection
        'assigned' => ['in_progress', 'pending'],
        'in_progress' => ['pending', 'resolved'],
        'pending' => ['in_progress', 'resolved'],
        'resolved' => ['closed', 'assigned'], // assigned = reopened
        'closed' => [], // terminal — no transitions out
    ];

    public const APPROVAL_REQUIRED_TYPES = ['service_request', 'change'];

    /**
     * Check whether a transition to $newStatus is allowed from the current status.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::TRANSITIONS[$this->status] ?? [], true);
    }

    /**
     * Attempt to transition the ticket to a new status.
     * Throws if the transition is not allowed (e.g. closed -> anything,
     * or resolved -> assigned is fine, but closed -> assigned is not).
     */
    public function transitionTo(string $newStatus): self
    {
        if (! $this->canTransitionTo($newStatus)) {
            throw new \DomainException(
                "Cannot transition ticket #{$this->id} from '{$this->status}' to '{$newStatus}'."
            );
        }

        $this->status = $newStatus;

        if ($newStatus === 'resolved') {
            $this->resolved_at = now();
        }

        if ($newStatus === 'closed') {
            $this->closed_at = now();
        }

        // Reopening: clear resolved_at since it's no longer resolved
        if ($newStatus === 'assigned' && $this->getOriginal('status') === 'resolved') {
            $this->resolved_at = null;
        }

        $this->save();

        return $this;
    }

    /**
     * Convenience: does this ticket type require an approval step
     * before it can be assigned?
     */
    public function requiresApproval(): bool
    {
        return in_array($this->type, self::APPROVAL_REQUIRED_TYPES, true);
    }

    /**
     * Resolve and apply the appropriate SLA policy for this ticket,
     * computing response/resolution due dates from the ticket's
     * creation time. Call this once, right after creation.
     */
    public function applySlaPolicy(): void
    {
        $policy = SlaPolicy::resolveFor($this);

        if (! $policy) {
            return;
        }

        $this->sla_policy_id = $policy->id;
        $this->sla_response_due_at = $this->created_at->copy()->addMinutes($policy->response_time_minutes);
        $this->sla_resolution_due_at = $this->created_at->copy()->addMinutes($policy->resolution_time_minutes);
        $this->save();
    }

    /**
     * True if the resolution due date has passed and the ticket is
     * still open (not resolved or closed).
     */
    public function isResolutionBreached(): bool
    {
        return $this->sla_resolution_due_at
            && $this->sla_resolution_due_at->isPast()
            && ! in_array($this->status, ['resolved', 'closed'], true);
    }

    /**
     * True if the response due date has passed and the ticket hasn't
     * moved past "open" yet (no agent has picked it up).
     */
    public function isResponseBreached(): bool
    {
        return $this->sla_response_due_at
            && $this->sla_response_due_at->isPast()
            && $this->status === 'open';
    }

    // Relationships

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    public function changeDetail(): HasOne
    {
        return $this->hasOne(ChangeDetail::class);
    }

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'ticket_assets');
    }

    /**
     * Incidents linked to this problem ticket (only meaningful when type = problem).
     */
    public function linkedIncidents(): BelongsToMany
    {
        return $this->belongsToMany(
            Ticket::class,
            'problem_incidents',
            'problem_ticket_id',
            'incident_ticket_id'
        );
    }

    /**
     * Problems this incident is linked to (only meaningful when type = incident).
     */
    public function linkedProblems(): BelongsToMany
    {
        return $this->belongsToMany(
            Ticket::class,
            'problem_incidents',
            'incident_ticket_id',
            'problem_ticket_id'
        );
    }
}
