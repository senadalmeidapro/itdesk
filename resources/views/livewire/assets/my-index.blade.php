<div>
    <h1 class="text-xl font-semibold mb-4">My assets</h1>

    <table class="w-full border-collapse">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">Tag</th>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Category</th>
                <th>Open tickets</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assets as $asset)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">
                        <a href="{{ route('assets.show', $asset) }}" wire:navigate>{{ $asset->asset_tag }}</a>
                    </td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ str_replace('_', ' ', $asset->type) }}</td>
                    <td><span class="badge">{{ str_replace('_', ' ', $asset->status) }}</span></td>
                    <td>{{ $asset->category?->name ?? '—' }}</td>
                    <td>{{ $asset->tickets_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-4 text-gray-500 text-center">
                        No assets are currently assigned to you.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $assets->links() }}
    </div>
</div>