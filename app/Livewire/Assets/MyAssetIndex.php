<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MyAssetIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorize('viewOwnAny', Asset::class);
    }

    public function render(): View
    {
        $assets = Asset::query()
            ->where('assigned_user_id', auth()->id())
            ->with('category')
            ->withCount('tickets')
            ->latest()
            ->paginate(15);

        return view('livewire.assets.my-index', [
            'assets' => $assets,
        ]);
    }
}