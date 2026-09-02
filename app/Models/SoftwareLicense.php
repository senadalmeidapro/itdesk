<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $vendor
 * @property string|null $license_key
 * @property int $seats_total
 * @property int $seats_used
 * @property \Illuminate\Support\Carbon|null $purchased_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property int|null $department_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name', 'vendor', 'license_key', 'seats_total', 'seats_used',
    'purchased_at', 'expires_at', 'department_id', 'notes',
])]
class SoftwareLicense extends Model
{
    protected function casts(): array
    {
        return [
            'purchased_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function seatsAvailable(): int
    {
        return max(0, $this->seats_total - $this->seats_used);
    }
}