<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AssetEdit extends Component
{
    public Asset $asset;

    public string $asset_tag = '';

    public string $serial_number = '';

    public string $name = '';

    public string $type = 'laptop';

    public string $status = 'in_stock';

    public string $manufacturer = '';

    public string $model = '';

    public string $ip_address = '';

    public string $mac_address = '';

    public ?int $assigned_user_id = null;

    public ?int $category_id = null;

    public string $supplier = '';

    public ?string $purchase_date = null;

    public ?string $warranty_expires_at = null;

    public string $location = '';

    public string $notes = '';

    public function mount(Asset $asset): void
    {
        $this->authorize('update', $asset);

        $this->asset = $asset;

        $this->asset_tag = $asset->asset_tag;
        $this->serial_number = $asset->serial_number ?? '';
        $this->name = $asset->name;
        $this->type = $asset->type;
        $this->status = $asset->status;
        $this->manufacturer = $asset->manufacturer ?? '';
        $this->model = $asset->model ?? '';
        $this->ip_address = $asset->ip_address ?? '';
        $this->mac_address = $asset->mac_address ?? '';
        $this->assigned_user_id = $asset->assigned_user_id;
        $this->category_id = $asset->category_id;
        $this->supplier = $asset->supplier ?? '';
        $this->purchase_date = $asset->purchase_date?->toDateString();
        $this->warranty_expires_at = $asset->warranty_expires_at?->toDateString();
        $this->location = $asset->location ?? '';
        $this->notes = $asset->notes ?? '';
    }

    protected function rules(): array
    {
        return [
            'asset_tag' => ['required', 'string', 'max:100', 'unique:assets,asset_tag,'.$this->asset->id],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:assets,serial_number,'.$this->asset->id],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:laptop,desktop,cpu,monitor,hard_disk,keyboard,mouse,printer,switch,router,camera,other'],
            'status' => ['required', 'in:in_use,in_stock,retired,repair'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'ip_address' => ['nullable', 'ip'],
            'mac_address' => ['nullable', 'regex:/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'warranty_expires_at' => ['nullable', 'date', 'after_or_equal:purchase_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->asset);

        $validated = $this->validate();

        $this->asset->update($validated);

        session()->flash('success', "Asset {$this->asset->asset_tag} updated.");

        $this->redirectRoute('assets.show', $this->asset);
    }

    public function render(): View
    {
        return view('livewire.assets.edit', [
            'users' => User::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
