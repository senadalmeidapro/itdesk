<div class="max-w-2xl">
    <h1 class="text-xl font-semibold mb-4">New asset</h1>

    <form wire:submit="save" class="space-y-4">
        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block font-medium">Asset tag</label>
                <input type="text" wire:model="asset_tag" class="w-full border rounded px-2 py-1">
                @error('asset_tag') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label class="block font-medium">Serial number</label>
                <input type="text" wire:model="serial_number" class="w-full border rounded px-2 py-1">
                @error('serial_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block font-medium">Name</label>
            <input type="text" wire:model="name" class="w-full border rounded px-2 py-1">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <div>
                <label class="block font-medium">Type</label>
                <select wire:model="type" class="border rounded px-2 py-1">
                    <option value="laptop">Laptop</option>
                    <option value="desktop">Desktop</option>
                    <option value="cpu">CPU</option>
                    <option value="monitor">Monitor</option>
                    <option value="hard_disk">Hard disk</option>
                    <option value="keyboard">Keyboard</option>
                    <option value="mouse">Mouse</option>
                    <option value="printer">Printer</option>
                    <option value="switch">Switch</option>
                    <option value="router">Router</option>
                    <option value="camera">Camera</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="block font-medium">Status</label>
                <select wire:model="status" class="border rounded px-2 py-1">
                    <option value="in_stock">In stock</option>
                    <option value="in_use">In use</option>
                    <option value="repair">Repair</option>
                    <option value="retired">Retired</option>
                </select>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block font-medium">Manufacturer</label>
                <input type="text" wire:model="manufacturer" class="w-full border rounded px-2 py-1">
            </div>
            <div class="flex-1">
                <label class="block font-medium">Model</label>
                <input type="text" wire:model="model" class="w-full border rounded px-2 py-1">
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block font-medium">IP address</label>
                <input type="text" wire:model="ip_address" class="w-full border rounded px-2 py-1">
                @error('ip_address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label class="block font-medium">MAC address</label>
                <input type="text" wire:model="mac_address" placeholder="00:1A:2B:3C:4D:5E" class="w-full border rounded px-2 py-1">
                @error('mac_address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block font-medium">Assign to</label>
            <select wire:model="assigned_user_id" class="border rounded px-2 py-1">
                <option value="">Unassigned</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block font-medium">Purchase date</label>
                <input type="date" wire:model="purchase_date" class="w-full border rounded px-2 py-1">
            </div>
            <div class="flex-1">
                <label class="block font-medium">Warranty expires</label>
                <input type="date" wire:model="warranty_expires_at" class="w-full border rounded px-2 py-1">
                @error('warranty_expires_at') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block font-medium">Supplier</label>
            <input type="text" wire:model="supplier" class="w-full border rounded px-2 py-1">
        </div>

        <div>
            <label class="block font-medium">Location</label>
            <input type="text" wire:model="location" class="w-full border rounded px-2 py-1">
        </div>

        <div>
            <label class="block font-medium">Notes</label>
            <textarea wire:model="notes" rows="3" class="w-full border rounded px-2 py-1"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create asset</button>
    </form>
</div>