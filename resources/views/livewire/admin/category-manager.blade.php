<div class="max-w-3xl">
    <h1 class="text-xl font-semibold mb-4">Categories</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="flex gap-2 items-start mb-6 flex-wrap">
        <div>
            <input type="text" wire:model="name" placeholder="Name" class="border rounded px-2 py-1">
            @error('name') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
        </div>

        <select wire:model="department_id" class="border rounded px-2 py-1">
            <option value="">No department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>

        <select wire:model="default_sla_policy_id" class="border rounded px-2 py-1">
            <option value="">No default SLA</option>
            @foreach ($slaPolicies as $policy)
                <option value="{{ $policy->id }}">{{ $policy->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Add' }}</button>
        @if ($editingId)
            <button type="button" wire:click="cancelEdit" class="btn btn-ghost">Cancel</button>
        @endif
    </form>

    <table class="w-full border-collapse">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">Name</th>
                <th>Department</th>
                <th>Default SLA</th>
                <th>Tickets</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr class="border-b">
                    <td class="py-2">{{ $category->name }}</td>
                    <td>{{ $category->department?->name ?? '—' }}</td>
                    <td>{{ $category->defaultSlaPolicy?->name ?? '—' }}</td>
                    <td>{{ $category->tickets_count }}</td>
                    <td class="text-right space-x-2">
                        <button wire:click="edit({{ $category->id }})" class="text-blue-600">Edit</button>
                        <button
                            wire:click="delete({{ $category->id }})"
                            wire:confirm="Delete this category?"
                            class="text-red-600"
                        >Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>