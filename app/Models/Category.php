<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property int|null $department_id
 * @property int|null $default_sla_policy_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin IdeHelperCategory
 */
#[Fillable(['name', 'department_id', 'default_sla_policy_id'])]
class Category extends Model
{
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function defaultSlaPolicy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class, 'default_sla_policy_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
