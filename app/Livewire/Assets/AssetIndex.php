<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AssetIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $type = '';

    #[Url]
    public string $status = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Asset::class);
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'type', 'status'])) {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        $assets = Asset::query()
            ->with(['assignedUser', 'category'])
            ->withCount('tickets')
            ->when($this->search, function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($q) use ($term) {
                    $q->where('asset_tag', 'like', $term)
                        ->orWhere('name', 'like', $term)
                        ->orWhere('serial_number', 'like', $term);
                });
            })
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(15);

        return view('livewire.assets.index', [
            'assets' => $assets,
        ]);
    }
}