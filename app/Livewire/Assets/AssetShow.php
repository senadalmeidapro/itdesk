<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AssetShow extends Component
{
    public Asset $asset;

    public ?int $reassignUserId = null;

    public bool $showReassignForm = false;

    public function mount(Asset $asset): void
    {
        $this->authorize('view', $asset);
        $this->asset = $asset;
    }

    public function reassign(): void
    {
        $this->authorize('assign', $this->asset);

        $this->validate([
            'reassignUserId' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($this->reassignUserId);
        $this->asset->assignTo($user);
        $this->asset->refresh();

        $this->reset('reassignUserId', 'showReassignForm');

        session()->flash('success', "Asset reassigned to {$user->name}.");
    }

    public function render(): View
    {
        $this->asset->load(['assignedUser', 'category', 'tickets', 'assignments' => function ($q) {
            $q->with('user')->latest('assigned_at');
        }])->loadCount('tickets');

        return view('livewire.assets.show', [
            'users' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }
}