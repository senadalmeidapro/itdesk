<div class="max-w-2xl">
    <h1 class="text-xl font-semibold mb-4">Departments</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="flex gap-2 items-start mb-6">
        <div>
            <input type="text" wire:model="name" placeholder="Name" class="border rounded px-2 py-1">
            @error('name') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
        </div>
        <div>
            <input type="text" wire:model="code" placeholder="Code" class="border rounded px-2 py-1 w-24">
            @error('code') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
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
                <th>Code</th>
                <th>Users</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($departments as $department)
                <tr class="border-b">
                    <td class="py-2">{{ $department->name }}</td>
                    <td>{{ $department->code }}</td>
                    <td>{{ $department->users_count }}</td>
                    <td class="text-right space-x-2">
                        <button wire:click="edit({{ $department->id }})" class="text-blue-600">Edit</button>
                        <button
                            wire:click="delete({{ $department->id }})"
                            wire:confirm="Delete this department?"
                            class="text-red-600"
                        >Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>