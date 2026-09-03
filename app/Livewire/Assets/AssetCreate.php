<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AssetCreate extends Component
{
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

    public function mount(): void
    {
        $this->authorize('create', Asset::class);
    }

    protected function rules(): array
    {
        return [
            'asset_tag' => ['required', 'string', 'max:100', 'unique:assets,asset_tag'],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:assets,serial_number'],
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

    public function save()
    {
        $validated = $this->validate();

        $asset = Asset::create($validated);

        if ($asset->assigned_user_id) {
            $asset->assignments()->create([
                'user_id' => $asset->assigned_user_id,
                'assigned_at' => now(),
            ]);
        }

        session()->flash('success', "Asset {$asset->asset_tag} created.");

        return redirect()->route('assets.show', $asset);
    }

    public function render(): View
    {
        return view('livewire.assets.create', [
            'users' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }
}