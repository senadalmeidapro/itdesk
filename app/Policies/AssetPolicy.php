<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * True if the user can see the full CMDB list, OR at least their
     * own assigned assets (viewOwnAny gates the "My assets" page).
     */
    public function viewAny(User $user): bool
    {
        return $user->can('assets.view') || $user->can('assets.view_own');
    }

    /**
     * Scoped check specifically for the "My assets" list - separate from
     * viewAny so AssetIndex (full CMDB) and MyAssetIndex can gate independently.
     */
    public function viewOwnAny(User $user): bool
    {
        return $user->can('assets.view_own');
    }

    public function view(User $user, Asset $asset): bool
    {
        return $user->can('assets.view') || $asset->assigned_user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('assets.create');
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->can('assets.update');
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->can('assets.delete');
    }

    public function assign(User $user, Asset $asset): bool
    {
        return $user->can('assets.assign');
    }
}