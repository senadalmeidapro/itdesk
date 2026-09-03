<div class="max-w-3xl">
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between mb-2">
        <h1 class="text-xl font-semibold">{{ $asset->asset_tag }} — {{ $asset->name }}</h1>
        <span class="badge">{{ str_replace('_', ' ', $asset->status) }}</span>
    </div>

    <div class="grid grid-cols-2 gap-2 text-sm mb-4">
        <div><span class="font-medium">Type:</span> {{ str_replace('_', ' ', $asset->type) }}</div>
        <div><span class="font-medium">Serial:</span> {{ $asset->serial_number ?? '—' }}</div>
        <div><span class="font-medium">Manufacturer:</span> {{ $asset->manufacturer ?? '—' }}</div>
        <div><span class="font-medium">Model:</span> {{ $asset->model ?? '—' }}</div>
        <div><span class="font-medium">IP address:</span> {{ $asset->ip_address ?? '—' }}</div>
        <div><span class="font-medium">MAC address:</span> {{ $asset->mac_address ?? '—' }}</div>
        <div><span class="font-medium">Location:</span> {{ $asset->location ?? '—' }}</div>
        <div><span class="font-medium">Supplier:</span> {{ $asset->supplier ?? '—' }}</div>
        <div><span class="font-medium">Purchase date:</span> {{ $asset->purchase_date?->format('Y-m-d') ?? '—' }}</div>
        <div><span class="font-medium">Warranty expires:</span> {{ $asset->warranty_expires_at?->format('Y-m-d') ?? '—' }}</div>
    </div>

    @if ($asset->notes)
        <div class="mb-4 text-sm">
            <span class="font-medium">Notes:</span> {{ $asset->notes }}
        </div>
    @endif

    <div class="border rounded p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <span class="font-medium">Currently assigned to:</span>
                {{ $asset->assignedUser?->name ?? 'Unassigned' }}
            </div>
            @can('assign', $asset)
                <button wire:click="$set('showReassignForm', true)" class="btn btn-secondary">
                    Reassign
                </button>
            @endcan
        </div>

        @if ($showReassignForm)
            <div class="mt-3 flex gap-2 items-end">
                <div>
                    <label class="block text-sm font-medium">New assignee</label>
                    <select wire:model="reassignUserId" class="border rounded px-2 py-1">
                        <option value="">Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('reassignUserId') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
                </div>
                <button wire:click="reassign" wire:confirm="Reassign this asset?" class="btn btn-primary">
                    Confirm
                </button>
                <button wire:click="$set('showReassignForm', false)" class="btn btn-ghost">
                    Cancel
                </button>
            </div>
        @endif
    </div>

    <h2 class="font-semibold mb-2">Assignment history</h2>
    <table class="w-full border-collapse mb-6 text-sm">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">User</th>
                <th>Assigned</th>
                <th>Unassigned</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($asset->assignments as $assignment)
                <tr class="border-b">
                    <td class="py-2">{{ $assignment->user->name }}</td>
                    <td>{{ $assignment->assigned_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $assignment->unassigned_at?->format('Y-m-d H:i') ?? 'Current' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-2 text-gray-500">No assignment history.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="font-semibold mb-2">Linked tickets ({{ $asset->tickets_count }})</h2>
    <ul class="space-y-1 text-sm">
        @forelse ($asset->tickets as $ticket)
            <li>
                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate>
                    #{{ $ticket->id }} — {{ $ticket->title }}
                </a>
                <span class="badge ml-2">{{ str_replace('_', ' ', $ticket->status) }}</span>
            </li>
        @empty
            <li class="text-gray-500">No tickets linked to this asset.</li>
        @endforelse
    </ul>
</div>