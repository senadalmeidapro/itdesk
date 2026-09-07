<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Tickets</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Toutes les demandes et interventions.</p>
        </div>
        @can('create', \App\Models\Ticket::class)
            <a href="{{ route('tickets.create') }}" wire:navigate class="btn btn-primary">
                <x-icon-plus class="size-4" /> Nouveau ticket
            </a>
        @endcan
    </div>

    <div class="mt-6 grid gap-3 md:grid-cols-4">
        <div class="relative">
            <x-icon-magnifying-glass class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-zinc-400" />
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Rechercher..."
                class="input !pl-9"
            >
        </div>

        <select wire:model.live="status" class="input">
            <option value="">Tous les statuts</option>
            @foreach (array_keys(\App\Models\Ticket::TRANSITIONS) as $status)
                <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>

        <select wire:model.live="type" class="input">
            <option value="">Tous les types</option>
            <option value="incident">Incident</option>
            <option value="service_request">Demande de service</option>
            <option value="problem">Problème</option>
            <option value="change">Changement</option>
        </select>

        <select wire:model.live="priority" class="input">
            <option value="">Toutes les priorités</option>
            <option value="low">Basse</option>
            <option value="medium">Moyenne</option>
            <option value="high">Haute</option>
            <option value="critical">Critique</option>
        </select>
    </div>

    <div class="surface mt-4 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-xs uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900">
                    <th class="px-5 py-3">#</th>
                    <th class="px-5 py-3">Titre</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Statut</th>
                    <th class="px-5 py-3">Priorité</th>
                    <th class="px-5 py-3">Demandeur</th>
                    <th class="px-5 py-3">Technicien</th>
                    <th class="px-5 py-3">SLA</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr class="border-b border-zinc-100 last:border-0 hover:bg-brand-50/40 dark:border-zinc-800 dark:hover:bg-brand-950/20">
                        <td class="px-5 py-3">
                            <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="font-semibold text-brand-600 hover:text-brand-700">
                                #{{ $ticket->id }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-zinc-800 dark:text-zinc-200">{{ $ticket->title }}</td>
                        <td class="px-5 py-3 capitalize text-zinc-600 dark:text-zinc-400">{{ str_replace('_', ' ', $ticket->type) }}</td>
                        <td class="px-5 py-3"><x-status-pill :status="$ticket->status" /></td>
                        <td class="px-5 py-3">
                            <span class="badge {{ match ($ticket->priority) {
                                'low' => 'badge-gray',
                                'medium' => 'bg-flow-50 text-flow-700',
                                'high' => 'bg-amber-50 text-amber-700',
                                'critical' => 'bg-red-50 text-red-700',
                                default => 'badge-gray',
                            } }}">{{ ucfirst($ticket->priority) }}</span>
                        </td>
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $ticket->requester->name }}</td>
                        <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $ticket->assignedAgent?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if ($ticket->isResponseBreached() || $ticket->isResolutionBreached())
                                <span class="badge bg-red-100 text-red-700">Dépassé</span>
                            @else
                                <span class="text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            @can('update', $ticket)
                                <a href="{{ route('tickets.edit', $ticket) }}" wire:navigate class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                    Modifier
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-zinc-400">Aucun ticket ne correspond à votre recherche.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>