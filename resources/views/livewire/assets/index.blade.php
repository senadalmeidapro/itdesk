<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Assets</h1>
        @can('create', \App\Models\Asset::class)
            <a href="{{ route('assets.create') }}" class="btn btn-primary">New asset</a>
        @endcan
    </div>

    <div class="flex gap-3 mb-4">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search tag, name, serial..."
            class="border rounded px-2 py-1 flex-1"
        >

        <select wire:model.live="type" class="border rounded px-2 py-1">
            <option value="">All types</option>
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

        <select wire:model.live="status" class="border rounded px-2 py-1">
            <option value="">All statuses</option>
            <option value="in_use">In use</option>
            <option value="in_stock">In stock</option>
            <option value="retired">Retired</option>
            <option value="repair">Repair</option>
        </select>
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">Tag</th>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Assigned to</th>
                <th>Location</th>
                <th>Tickets</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assets as $asset)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">
                        <a href="{{ route('assets.show', $asset) }}" wire:navigate>{{ $asset->asset_tag }}</a>
                    </td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ str_replace('_', ' ', $asset->type) }}</td>
                    <td><span class="badge">{{ str_replace('_', ' ', $asset->status) }}</span></td>
                    <td>{{ $asset->assignedUser?->name ?? '—' }}</td>
                    <td>{{ $asset->location ?? '—' }}</td>
                    <td>{{ $asset->tickets_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $assets->links() }}
    </div>
</div>