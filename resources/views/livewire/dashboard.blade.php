<div class="max-w-6xl space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Vue d'ensemble</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Activité des tickets et performance SLA.</p>
        </div>
        <a href="{{ route('tickets.create') }}" wire:navigate class="btn btn-primary">
            <x-icon-plus class="size-4" /> Nouveau ticket
        </a>
    </div>

    {{-- Top-line SLA numbers --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="surface p-5">
            <p class="flex items-center gap-1.5 text-sm text-zinc-500">
                <x-icon-shield-check class="size-4 text-brand-500" />
                Conformité SLA
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ $sla['compliance_rate'] !== null ? $sla['compliance_rate'].'%' : '—' }}
            </p>
            <p class="mt-1 text-xs text-zinc-400">{{ $sla['on_time'] }} / {{ $sla['total_with_sla'] }} résolus à temps</p>
        </div>

        <div class="surface p-5 {{ $sla['currently_breached'] > 0 ? '!border-red-300 !bg-red-50' : '' }} dark:!bg-red-950/30">
            <p class="flex items-center gap-1.5 text-sm {{ $sla['currently_breached'] > 0 ? 'text-red-600' : 'text-zinc-500' }}">
                <x-icon-clock class="size-4" />
                Dépassements actuels
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight {{ $sla['currently_breached'] > 0 ? 'text-red-600' : 'text-zinc-900 dark:text-zinc-50' }}">
                {{ $sla['currently_breached'] }}
            </p>
            <p class="mt-1 text-xs text-zinc-400">tickets ouverts hors délai</p>
        </div>

        <div class="surface p-5">
            <p class="flex items-center gap-1.5 text-sm text-zinc-500">
                <x-icon-chart-bar class="size-4 text-flow-500" />
                Délai moyen de résolution
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ $avgResolutionHours !== null ? $avgResolutionHours.'h' : '—' }}
            </p>
            <p class="mt-1 text-xs text-zinc-400">depuis la création du ticket</p>
        </div>

        <div class="surface p-5">
            <p class="flex items-center gap-1.5 text-sm text-zinc-500">
                <x-icon-lifebuoy class="size-4 text-brand-500" />
                Tickets ouverts
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ $statusCounts['open'] + $statusCounts['assigned'] + $statusCounts['in_progress'] + $statusCounts['pending'] }}
            </p>
            <p class="mt-1 text-xs text-zinc-400">à traiter actuellement</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Status breakdown --}}
        <div class="surface p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">Tickets par statut</h2>
            <div class="mt-4 grid grid-cols-7 gap-2 text-center text-sm">
                @foreach ($statusCounts as $status => $count)
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 py-2 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">{{ $count }}</div>
                        <div class="mt-0.5 text-[10px] leading-tight text-zinc-500">{{ str_replace('_', ' ', $status) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Priority breakdown --}}
        <div class="surface p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">Tickets par priorité</h2>
            <div class="mt-4 grid grid-cols-4 gap-2 text-center text-sm">
                @foreach ($priorityCounts as $priority => $count)
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 py-2 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">{{ $count }}</div>
                        <div class="mt-0.5 text-xs text-zinc-500">{{ ucfirst($priority) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Daily volume, last 30 days - plain CSS bar chart, no JS dependency --}}
    <div class="surface p-6">
        <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">Volume de tickets (30 derniers jours)</h2>
        <div class="mt-4 flex items-end gap-1 border-b border-l pb-1 pl-1 h-40">
            @foreach ($dailyVolume as $day => $count)
                <div
                    class="flex-1 rounded-t bg-brand-400 transition hover:bg-brand-600"
                    style="height: {{ $maxDaily > 0 ? max(2, ($count / $maxDaily) * 100) : 0 }}%"
                    title="{{ $day }}: {{ $count }} ticket(s)"
                ></div>
            @endforeach
        </div>
        <div class="mt-1 flex justify-between text-xs text-zinc-400">
            <span>{{ array_key_first($dailyVolume) }}</span>
            <span>{{ array_key_last($dailyVolume) }}</span>
        </div>
    </div>

    {{-- Top categories --}}
    <div class="surface overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <h2 class="font-semibold text-zinc-900 dark:text-zinc-50">Catégories les plus utilisées</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 text-left text-xs uppercase tracking-wide text-zinc-500 dark:border-zinc-700">
                    <th class="px-6 py-3">Catégorie</th>
                    <th class="px-6 py-3 text-right">Tickets</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topCategories as $category)
                    <tr class="border-b border-zinc-100 last:border-0 dark:border-zinc-800">
                        <td class="px-6 py-3 text-zinc-800 dark:text-zinc-200">{{ $category['name'] }}</td>
                        <td class="px-6 py-3 text-right">
                            <span class="badge badge-brand">{{ $category['count'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-8 text-center text-zinc-400">Aucun ticket catégorisé pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>