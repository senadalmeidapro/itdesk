<div class="max-w-3xl">
    <h1 class="text-xl font-semibold mb-4">SLA Policies</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="flex gap-2 items-start mb-6 flex-wrap">
        <div>
            <input type="text" wire:model="name" placeholder="Name" class="border rounded px-2 py-1">
            @error('name') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
        </div>

        <select wire:model="priority" class="border rounded px-2 py-1">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="critical">Critical</option>
        </select>

        <div>
            <input type="number" wire:model="response_time_minutes" placeholder="Response (min)" class="border rounded px-2 py-1 w-36">
            @error('response_time_minutes') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
        </div>

        <div>
            <input type="number" wire:model="resolution_time_minutes" placeholder="Resolution (min)" class="border rounded px-2 py-1 w-36">
            @error('resolution_time_minutes') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Add' }}</button>
        @if ($editingId)
            <button type="button" wire:click="cancelEdit" class="btn btn-ghost">Cancel</button>
        @endif
    </form>

    <table class="w-full border-collapse">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">Name</th>
                <th>Priority</th>
                <th>Response</th>
                <th>Resolution</th>
                <th>Tickets</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($policies as $policy)
                <tr class="border-b">
                    <td class="py-2">{{ $policy->name }}</td>
                    <td>{{ ucfirst($policy->priority) }}</td>
                    <td>{{ $policy->response_time_minutes }} min</td>
                    <td>{{ $policy->resolution_time_minutes }} min</td>
                    <td>{{ $policy->tickets_count }}</td>
                    <td class="text-right space-x-2">
                        <button wire:click="edit({{ $policy->id }})" class="text-blue-600">Edit</button>
                        <button
                            wire:click="delete({{ $policy->id }})"
                            wire:confirm="Delete this SLA policy?"
                            class="text-red-600"
                        >Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>