<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Équipements</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Parc informatique, réseau et infogérance.</p>
        </div>
        @can('create', \App\Models\Asset::class)
            <a href="{{ route('assets.create') }}" wire:navigate class="btn btn-primary">
                <x-icon-plus class="size-4" /> Nouvel équipement
            </a>
        @endcan
    </div>

    <div class="mt-6 grid gap-3 md:grid-cols-3">
        <div class="relative">
            <x-icon-magnifying-glass class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-zinc-400" />
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Rechercher (tag, nom, série, IP…)"
                class="input !pl-9"
            >
        </div>

        <select wire:model.live="type" class="input">
            <option value="">Tous les types</option>
            @foreach (['laptop', 'desktop', 'cpu', 'monitor', 'hard_disk', 'keyboard', 'mouse', 'printer', 'switch', 'router', 'camera', 'other'] as $type)
                <option value="{{ $type }}">{{ str_replace('_', ' ', ucfirst($type)) }}</option>
            @endforeach
        </select>

        <select wire:model.live="status" class="input">
            <option value="">Tous les statuts</option>
            <option value="in_use">En usage</option>
            <option value="in_stock">En stock</option>
            <option value="repair">En réparation</option>
            <option value="retired">Retiré</option>
        </select>
    </div>

    <div class="surface mt-4 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-xs uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900">
                    <th class="px-5 py-3">Tag</th>
                    <th class="px-5 py-3">Nom</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Statut</th>
                    <th class="px-5 py-3">Assigné à</th>
                    <th class="px-5 py-3">Emplacement</th>
                    <th class="px-5 py-3 text-right">Tickets</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assets as $asset)
                    <tr class="border-b border-zinc-100 last:border-0 hover:bg-brand-50/40 dark:border-zinc-800 dark:hover:bg-brand-950/20">
                        <td class="px-5 py-3">
                            <a href="{{ route('assets.show', $asset) }}" wire:navigate class="font-semibold text-brand-600 hover:text-brand-700">
                                {{ $asset->asset_tag }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-zinc-800 dark:text-zinc-200">{{ $asset->name }}</td>
                        <td class="px-5 py-3 capitalize text-zinc-600 dark:text-zinc-400">{{ str_replace('_', ' ', $asset->type) }}</td>
                        <td class="px-5 py-3"><x-status-pill :status="$asset->status" /></td>
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $asset->assignedUser?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $asset->location ?? '—' }}</td>
                        <td class="px-5 py-3 text-right text-zinc-600 dark:text-zinc-400">{{ $asset->tickets_count }}</td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            @can('update', $asset)
                                <a href="{{ route('assets.edit', $asset) }}" wire:navigate class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                    Modifier
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-zinc-400">Aucun équipement ne correspond à votre recherche.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $assets->links() }}
    </div>
</div>