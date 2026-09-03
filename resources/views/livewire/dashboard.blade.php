<div class="max-w-5xl space-y-8">
    <h1 class="text-xl font-semibold">Dashboard</h1>

    {{-- Top-line SLA numbers --}}
    <div class="grid grid-cols-4 gap-4">
        <div class="border rounded p-4">
            <div class="text-sm text-gray-500">SLA compliance</div>
            <div class="text-2xl font-semibold">
                {{ $sla['compliance_rate'] !== null ? $sla['compliance_rate'].'%' : '—' }}
            </div>
            <div class="text-xs text-gray-500">{{ $sla['on_time'] }} / {{ $sla['total_with_sla'] }} resolved on time</div>
        </div>

        <div class="border rounded p-4 {{ $sla['currently_breached'] > 0 ? 'border-red-400 bg-red-50' : '' }}">
            <div class="text-sm text-gray-500">Currently breached</div>
            <div class="text-2xl font-semibold">{{ $sla['currently_breached'] }}</div>
            <div class="text-xs text-gray-500">open tickets past resolution deadline</div>
        </div>

        <div class="border rounded p-4">
            <div class="text-sm text-gray-500">Avg. resolution time</div>
            <div class="text-2xl font-semibold">
                {{ $avgResolutionHours !== null ? $avgResolutionHours.'h' : '—' }}
            </div>
        </div>

        <div class="border rounded p-4">
            <div class="text-sm text-gray-500">Open tickets</div>
            <div class="text-2xl font-semibold">
                {{ $statusCounts['open'] + $statusCounts['assigned'] + $statusCounts['in_progress'] + $statusCounts['pending'] }}
            </div>
        </div>
    </div>

    {{-- Status breakdown --}}
    <div>
        <h2 class="font-semibold mb-2">Tickets by status</h2>
        <div class="grid grid-cols-7 gap-2 text-center text-sm">
            @foreach ($statusCounts as $status => $count)
                <div class="border rounded p-2">
                    <div class="text-lg font-semibold">{{ $count }}</div>
                    <div class="text-xs text-gray-500">{{ str_replace('_', ' ', $status) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Priority breakdown --}}
    <div>
        <h2 class="font-semibold mb-2">Tickets by priority</h2>
        <div class="grid grid-cols-4 gap-2 text-center text-sm">
            @foreach ($priorityCounts as $priority => $count)
                <div class="border rounded p-2">
                    <div class="text-lg font-semibold">{{ $count }}</div>
                    <div class="text-xs text-gray-500">{{ ucfirst($priority) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Daily volume, last 30 days - plain CSS bar chart, no JS dependency --}}
    <div>
        <h2 class="font-semibold mb-2">Ticket volume (last 30 days)</h2>
        <div class="flex items-end gap-1 h-32 border-b border-l pl-1 pb-1">
            @foreach ($dailyVolume as $day => $count)
                <div
                    class="flex-1 bg-blue-400 hover:bg-blue-600"
                    style="height: {{ $maxDaily > 0 ? max(2, ($count / $maxDaily) * 100) : 0 }}%"
                    title="{{ $day }}: {{ $count }} ticket(s)"
                ></div>
            @endforeach
        </div>
        <div class="flex justify-between text-xs text-gray-500 mt-1">
            <span>{{ array_key_first($dailyVolume) }}</span>
            <span>{{ array_key_last($dailyVolume) }}</span>
        </div>
    </div>

    {{-- Top categories --}}
    <div>
        <h2 class="font-semibold mb-2">Top categories</h2>
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Category</th>
                    <th>Tickets</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topCategories as $category)
                    <tr class="border-b">
                        <td class="py-2">{{ $category['name'] }}</td>
                        <td>{{ $category['count'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="py-2 text-gray-500">No categorized tickets yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>