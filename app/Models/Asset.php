<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $asset_tag
 * @property string|null $serial_number
 * @property string $name
 * @property string $type
 * @property string $status
 * @property string|null $manufacturer
 * @property string|null $model
 * @property array|null $specifications
 * @property string|null $ip_address
 * @property string|null $mac_address
 * @property int|null $assigned_user_id
 * @property int|null $category_id
 * @property string|null $supplier
 * @property \Illuminate\Support\Carbon|null $purchase_date
 * @property \Illuminate\Support\Carbon|null $warranty_expires_at
 * @property string|null $location
 * @property string|null $qr_code
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'asset_tag', 'serial_number', 'name', 'type', 'status',
    'manufacturer', 'model', 'specifications', 'ip_address', 'mac_address',
    'assigned_user_id', 'category_id', 'supplier',
    'purchase_date', 'warranty_expires_at', 'location', 'qr_code', 'notes',
])]
class Asset extends Model
{
    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'purchase_date' => 'date',
            'warranty_expires_at' => 'date',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_assets');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    /**
     * Reassign this asset to a new user, closing out the previous
     * open assignment (if any) and logging a new one.
     */
    public function assignTo(User $user): void
    {
        $this->assignments()
            ->whereNull('unassigned_at')
            ->update(['unassigned_at' => now()]);

        $this->assignments()->create([
            'user_id' => $user->id,
            'assigned_at' => now(),
        ]);

        $this->update([
            'assigned_user_id' => $user->id,
            'status' => 'in_use',
        ]);
    }
}