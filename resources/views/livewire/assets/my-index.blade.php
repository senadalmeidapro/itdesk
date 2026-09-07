<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Mes équipements</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Le matériel qui vous est assigné.</p>
        </div>
    </div>

    <div class="surface mt-6 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-xs uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900">
                    <th class="px-5 py-3">Tag</th>
                    <th class="px-5 py-3">Nom</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Statut</th>
                    <th class="px-5 py-3">Catégorie</th>
                    <th class="px-5 py-3 text-right">Tickets ouverts</th>
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
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $asset->category?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-right text-zinc-600 dark:text-zinc-400">{{ $asset->tickets_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-zinc-400">
                            Aucun équipement ne vous est assigné pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $assets->links() }}
    </div>
</div>