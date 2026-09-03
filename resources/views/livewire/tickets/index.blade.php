<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Tickets</h1>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">New ticket</a>
    </div>

    <div class="flex gap-3 mb-4">
        <select wire:model.live="status" class="border rounded px-2 py-1">
            <option value="">All statuses</option>
            @foreach (array_keys(\App\Models\Ticket::TRANSITIONS) as $status)
                <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>

        <select wire:model.live="type" class="border rounded px-2 py-1">
            <option value="">All types</option>
            <option value="incident">Incident</option>
            <option value="service_request">Service request</option>
            <option value="problem">Problem</option>
            <option value="change">Change</option>
        </select>

        <select wire:model.live="priority" class="border rounded px-2 py-1">
            <option value="">All priorities</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="critical">Critical</option>
        </select>
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">#</th>
                <th>Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Requester</th>
                <th>Agent</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">
                        <a href="{{ route('tickets.show', $ticket) }}" wire:navigate>#{{ $ticket->id }}</a>
                    </td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ str_replace('_', ' ', $ticket->type) }}</td>
                    <td><span class="badge">{{ str_replace('_', ' ', $ticket->status) }}</span></td>
                    <td>{{ ucfirst($ticket->priority) }}</td>
                    <td>{{ $ticket->requester->name }}</td>
                    <td>{{ $ticket->assignedAgent?->name ?? '—' }}</td>
                    <td>{{ $ticket->comments_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>