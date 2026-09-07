<div class="max-w-5xl space-y-8">
    @if (session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-flow-200 bg-flow-50 p-4 text-sm text-flow-800">
            <x-icon-check class="mt-0.5 size-5 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                Bonjour, {{ auth()->user()->name }} 👋
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Bienvenue dans votre espace client. Besoin d'aide ? Ouvrez une demande.
            </p>
        </div>
        <a href="{{ route('tickets.create') }}" wire:navigate class="btn btn-primary">
            <x-icon-plus class="size-4" /> Nouvelle demande
        </a>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="surface p-5">
            <p class="flex items-center gap-1.5 text-sm text-zinc-500">
                <x-icon-lifebuoy class="size-4 text-brand-500" />
                Mes demandes ouvertes
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-brand-700 dark:text-brand-400">{{ $openTicketsCount }}</p>
        </div>

        <div class="surface p-5">
            <p class="flex items-center gap-1.5 text-sm text-zinc-500">
                <x-icon-computer-desktop class="size-4 text-flow-500" />
                Mes équipements
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-flow-700 dark:text-flow-400">{{ $myAssets->count() }}</p>
        </div>

        <div class="surface p-5 {{ $pendingApprovalsCount > 0 ? '!border-brand-300 !bg-brand-50' : '' }} dark:!bg-brand-950/30">
            <p class="flex items-center gap-1.5 text-sm {{ $pendingApprovalsCount > 0 ? 'text-brand-600' : 'text-zinc-500' }}">
                <x-icon-shield-check class="size-4" />
                Approbations en attente
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight {{ $pendingApprovalsCount > 0 ? 'text-brand-600' : 'text-zinc-900 dark:text-zinc-50' }}">
                {{ $pendingApprovalsCount }}
            </p>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Recent tickets --}}
        <div class="surface overflow-hidden">
            <div class="flex items-center justify-between border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">Mes demandes récentes</h2>
                @if ($myTickets->count() > 0)
                    <a href="{{ route('tickets.index') }}" wire:navigate class="text-sm font-semibold text-brand-600 hover:text-brand-700">
                        Tout voir
                    </a>
                @endif
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 text-left text-xs uppercase tracking-wide text-zinc-500 dark:border-zinc-700">
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Titre</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3">Priorité</th>
                        <th class="px-6 py-3">Technicien</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($myTickets as $ticket)
                        <tr class="border-b border-zinc-100 last:border-0 hover:bg-brand-50/40 dark:border-zinc-800 dark:hover:bg-brand-950/20">
                            <td class="px-6 py-3">
                                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="font-semibold text-brand-600 hover:text-brand-700">
                                    #{{ $ticket->id }}
                                </a>
                            </td>
                            <td class="px-6 py-3 text-zinc-800 dark:text-zinc-200">{{ $ticket->title }}</td>
                            <td class="px-6 py-3"><x-status-pill :status="$ticket->status" /></td>
                            <td class="px-6 py-3 capitalize text-zinc-600 dark:text-zinc-400">{{ $ticket->priority }}</td>
                            <td class="px-6 py-3 text-zinc-600 dark:text-zinc-400">{{ $ticket->assignedAgent?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-zinc-400">
                                Aucune demande pour le moment.
                                <a href="{{ route('tickets.create') }}" wire:navigate class="font-semibold text-brand-600 hover:text-brand-700"> En créer une</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- My assets --}}
        <div class="surface overflow-hidden">
            <div class="flex items-center justify-between border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">Mes équipements</h2>
                <a href="{{ route('assets.mine') }}" wire:navigate class="text-sm font-semibold text-brand-600 hover:text-brand-700">
                    Tout voir
                </a>
            </div>
            <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse ($myAssets as $asset)
                    <li class="flex items-center justify-between px-6 py-3 hover:bg-brand-50/40 dark:hover:bg-brand-950/20">
                        <a href="{{ route('assets.show', $asset) }}" wire:navigate class="text-sm font-medium text-zinc-800 hover:text-brand-600 dark:text-zinc-200">
                            {{ $asset->name }}
                        </a>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-zinc-400">{{ $asset->asset_tag }}</span>
                            <span class="badge badge-gray">{{ str_replace('_', ' ', $asset->type) }}</span>
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-sm text-zinc-400">Aucun équipement ne vous est encore assigné.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>