<div class="max-w-3xl">
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between mb-2">
        <h1 class="text-xl font-semibold">#{{ $ticket->id }} — {{ $ticket->title }}</h1>
        <span class="badge">{{ str_replace('_', ' ', $ticket->status) }}</span>
    </div>

    <p class="text-gray-700 mb-4">{{ $ticket->description }}</p>

    <div class="grid grid-cols-2 gap-2 text-sm mb-4">
        <div><span class="font-medium">Type:</span> {{ str_replace('_', ' ', $ticket->type) }}</div>
        <div><span class="font-medium">Priority:</span> {{ ucfirst($ticket->priority) }}</div>
        <div><span class="font-medium">Requester:</span> {{ $ticket->requester->name }}</div>
        <div><span class="font-medium">Agent:</span> {{ $ticket->assignedAgent?->name ?? '—' }}</div>
        <div><span class="font-medium">Category:</span> {{ $ticket->category?->name ?? '—' }}</div>
    </div>

    @can('transition', $ticket)
        <div class="flex gap-2 items-end mb-4">
            <div>
                <label class="block text-sm font-medium">Assigned agent</label>
                <select wire:model="assignAgentId" class="border rounded px-2 py-1">
                    <option value="">Unassigned</option>
                    @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="assignAgent" class="btn btn-secondary">Assign</button>
        </div>
    @endcan

    @if ($ticket->type === 'change' && $ticket->changeDetail)
        <div class="border-l-4 border-amber-400 pl-4 mb-4 text-sm">
            <div><span class="font-medium">Risk level:</span> {{ ucfirst($ticket->changeDetail->risk_level) }}</div>
            <div><span class="font-medium">Scheduled:</span> {{ $ticket->changeDetail->scheduled_at?->format('Y-m-d H:i') ?? '—' }}</div>
            <div><span class="font-medium">Rollback plan:</span> {{ $ticket->changeDetail->rollback_plan ?? '—' }}</div>
        </div>
    @endif

    @if (! empty($this->availableTransitions))
        <div class="flex gap-2 mb-6">
            @foreach ($this->availableTransitions as $status)
                <button
                    wire:click="transitionTo('{{ $status }}')"
                    wire:confirm="Move this ticket to '{{ str_replace('_', ' ', $status) }}'?"
                    class="btn btn-secondary"
                >
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </button>
            @endforeach
        </div>
    @endif

    <h2 class="font-semibold mb-2">Attachments</h2>

    <div class="space-y-2 mb-4">
        @forelse ($ticket->attachments as $attachment)
            <div class="flex items-center justify-between border rounded px-3 py-2 text-sm">
                <div>
                    <a href="{{ route('attachments.download', $attachment) }}" class="text-blue-600">
                        {{ $attachment->original_filename }}
                    </a>
                    <span class="text-gray-500">
                        ({{ $attachment->humanSize() }} — uploaded by {{ $attachment->uploader->name }})
                    </span>
                </div>
                @if ($attachment->user_id === auth()->id() || auth()->user()->can('tickets.update_any'))
                    <button
                        wire:click="deleteAttachment({{ $attachment->id }})"
                        wire:confirm="Delete this attachment?"
                        class="text-red-600"
                    >Delete</button>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-sm">No attachments yet.</p>
        @endforelse
    </div>

    <form wire:submit="uploadAttachments" class="mb-6 space-y-2">
        <input type="file" wire:model="newAttachments" multiple class="text-sm">
        @error('newAttachments') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
        @error('newAttachments.*') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror

        <div wire:loading wire:target="newAttachments" class="text-sm text-gray-500">Uploading...</div>

        @if (! empty($newAttachments))
            <button type="submit" class="btn btn-secondary">Attach {{ count($newAttachments) }} file(s)</button>
        @endif
    </form>

    <h2 class="font-semibold mb-2">Comments</h2>

    <div class="space-y-3 mb-4">
        @forelse ($comments as $comment)
            <div class="border rounded p-3 {{ $comment->is_internal ? 'bg-yellow-50' : '' }}">
                <div class="text-sm text-gray-500 flex justify-between">
                    <span>{{ $comment->user->name }}</span>
                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                @if ($comment->is_internal)
                    <span class="text-xs text-amber-700">Internal note</span>
                @endif
                <p>{{ $comment->body }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No comments yet.</p>
        @endforelse
    </div>

    <form wire:submit="addComment" class="space-y-2">
        <textarea wire:model="commentBody" rows="3" class="w-full border rounded px-2 py-1" placeholder="Add a comment..."></textarea>
        @error('commentBody') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        @can('transition', $ticket)
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="isInternal">
                Internal note (hidden from requester)
            </label>
        @endcan

        <button type="submit" class="btn btn-primary">Post comment</button>
    </form>
</div>