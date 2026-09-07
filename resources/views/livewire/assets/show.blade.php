<div class="max-w-3xl space-y-6">
    @if (session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-flow-200 bg-flow-50 p-4 text-sm text-flow-800">
            <x-icon-check class="mt-0.5 size-5 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="surface flex flex-wrap items-start justify-between gap-4 p-6">
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ $asset->asset_tag }} — {{ $asset->name }}
            </h1>
            <x-status-pill :status="$asset->status" />
        </div>

        <div class="flex items-center gap-2">
            @can('update', $asset)
                <a href="{{ route('assets.edit', $asset) }}" wire:navigate class="btn btn-secondary">
                    <x-icon-pencil-square class="size-4" /> Modifier
                </a>
            @endcan
            @can('delete', $asset)
                <button
                    wire:click="delete"
                    wire:confirm="Supprimer définitivement cet équipement ?"
                    class="btn btn-danger"
                >
                    Supprimer
                </button>
            @endcan
        </div>
    </div>

    {{-- Details --}}
    <div class="surface p-6">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-500">Détails techniques</h2>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm lg:grid-cols-3">
            <div>
                <dt class="text-zinc-500">Type</dt>
                <dd class="mt-0.5 font-medium capitalize text-zinc-900 dark:text-zinc-100">{{ str_replace('_', ' ', $asset->type) }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Numéro de série</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->serial_number ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Fabricant</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->manufacturer ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Modèle</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->model ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Adresse IP</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->ip_address ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Adresse MAC</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->mac_address ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Emplacement</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->location ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Fournisseur</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->supplier ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Catégorie</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->category?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Date d'achat</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->purchase_date?->format('d/m/Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Fin de garantie</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->warranty_expires_at?->format('d/m/Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Assigné à</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $asset->assignedUser?->name ?? 'Non assigné' }}</dd>
            </div>
        </dl>

        @if ($asset->notes)
            <div class="mt-5 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                <dt class="text-sm text-zinc-500">Notes</dt>
                <dd class="mt-1 whitespace-pre-line text-sm text-zinc-800 dark:text-zinc-200">{{ $asset->notes }}</dd>
            </div>
        @endif
    </div>

    {{-- Reassign --}}
    @can('assign', $asset)
        <div class="surface p-6">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-500">Affectation</h2>
            <div class="flex flex-wrap items-end gap-3">
                <div class="w-64">
                    <label class="label">Nouvel utilisateur</label>
                    <select wire:model="reassignUserId" class="input">
                        <option value="">Sélectionner…</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('reassignUserId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <button wire:click="$set('showReassignForm', true)" class="btn btn-secondary">Réaffecter</button>
            </div>

            @if ($showReassignForm)
                <div class="mt-3 flex flex-wrap items-end gap-3 rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="w-64">
                        <label class="label">Confirmer l'attribution</label>
                        <select wire:model="reassignUserId" class="input">
                            <option value="">Sélectionner…</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('reassignUserId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button wire:click="reassign" wire:confirm="Réaffecter cet équipement à cet utilisateur ?" class="btn btn-primary">
                        Confirmer
                    </button>
                    <button wire:click="$set('showReassignForm', false)" class="btn btn-ghost">Annuler</button>
                </div>
            @endif
        </div>
    @endcan

    {{-- Assignment history --}}
    <div class="surface overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">Historique d'affectation</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-xs uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900">
                    <th class="px-5 py-3">Utilisateur</th>
                    <th class="px-5 py-3">Assigné le</th>
                    <th class="px-5 py-3">Retiré le</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($asset->assignments as $assignment)
                    <tr class="border-b border-zinc-100 last:border-0 dark:border-zinc-800">
                        <td class="px-5 py-3 text-zinc-800 dark:text-zinc-200">{{ $assignment->user->name }}</td>
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $assignment->assigned_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $assignment->unassigned_at?->format('d/m/Y H:i') ?? 'Actuel' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-8 text-center text-zinc-400">Aucun historique d'affectation.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Linked tickets --}}
    <div class="surface overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">
                Tickets liés ({{ $asset->tickets_count }})
            </h2>
        </div>
        <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse ($asset->tickets as $ticket)
                <li class="flex items-center justify-between gap-4 px-6 py-3 hover:bg-brand-50/40 dark:hover:bg-brand-950/20">
                    <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="text-sm font-medium text-brand-600 hover:text-brand-700">
                        #{{ $ticket->id }} — {{ $ticket->title }}
                    </a>
                    <x-status-pill :status="$ticket->status" />
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-zinc-400">Aucun ticket lié à cet équipement.</li>
            @endforelse
        </ul>
    </div>
</div>