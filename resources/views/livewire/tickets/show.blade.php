<div class="max-w-4xl space-y-6">
    <div>
        <a href="{{ route('tickets.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 transition hover:text-brand-600">
            <x-icon-arrow-left class="size-4" /> Retour aux tickets
        </a>
    </div>

    @if (session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-flow-200 bg-flow-50 p-4 text-sm text-flow-800">
            <x-icon-check class="mt-0.5 size-5 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="surface flex flex-wrap items-start justify-between gap-4 p-6">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                    #{{ $ticket->id }} — {{ $ticket->title }}
                </h1>
                <x-status-pill :status="$ticket->status" />
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if ($ticket->type === 'change')
                    <span class="badge bg-amber-50 text-amber-700">{{ str_replace('_', ' ', $ticket->type) }}</span>
                @elseif ($ticket->type === 'problem')
                    <span class="badge bg-orange-50 text-orange-700">{{ str_replace('_', ' ', $ticket->type) }}</span>
                @else
                    <span class="badge badge-gray">{{ str_replace('_', ' ', $ticket->type) }}</span>
                @endif
                @if ($ticket->isResponseBreached())
                    <span class="badge bg-red-100 text-red-700">SLA réponse dépassé</span>
                @endif
                @if ($ticket->isResolutionBreached())
                    <span class="badge bg-red-100 text-red-700">SLA résolution dépassé</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            @can('update', $ticket)
                <a href="{{ route('tickets.edit', $ticket) }}" wire:navigate class="btn btn-secondary">
                    <x-icon-pencil-square class="size-4" /> Modifier
                </a>
            @endcan
            @can('delete', $ticket)
                <button
                    wire:click="delete"
                    wire:confirm="Supprimer définitivement ce ticket ?"
                    class="btn btn-danger"
                >
                    Supprimer
                </button>
            @endcan
        </div>
    </div>

    {{-- Approval required --}}
    @if ($ticket->status === 'pending_approval' && auth()->user()->can('tickets.approve'))
        @php
            $pendingApproval = $ticket->approvals()->where('decision', 'pending')->latest('id')->first();
        @endphp
        <div class="surface border-l-4 !border-l-brand-500 p-6">
            <h2 class="flex items-center gap-2 font-semibold text-zinc-900 dark:text-zinc-50">
                <x-icon-shield-check class="size-5 text-brand-600" />
                Approbation requise
            </h2>
            @if ($pendingApproval)
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    En attente d'approbation par
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">
                        {{ $pendingApproval->approver?->name ?? 'le responsable' }}
                    </span>
                    — demandée par {{ $ticket->requester->name }} le {{ $pendingApproval->created_at?->diffForHumans() }}.
                </p>
            @else
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    Ce ticket doit être approuvé avant d'être assigné à un technicien.
                </p>
            @endif
            <div class="mt-4 flex gap-2">
                <form wire:submit="approve">
                    <button type="submit" class="btn btn-primary">Approuver</button>
                </form>
                <form wire:submit="reject">
                    <button type="submit" wire:confirm="Rejeter cette demande et fermer le ticket ?" class="btn btn-danger">Rejeter</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Description --}}
    <div class="surface p-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-500">Description</h2>
        <p class="whitespace-pre-line leading-relaxed text-zinc-800 dark:text-zinc-200">{{ $ticket->description }}</p>
    </div>

    {{-- Meta --}}
    <div class="surface p-6">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-500">Informations</h2>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm lg:grid-cols-3">
            <div>
                <dt class="text-zinc-500">Type</dt>
                <dd class="mt-0.5 font-medium capitalize text-zinc-900 dark:text-zinc-100">{{ str_replace('_', ' ', $ticket->type) }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Priorité</dt>
                <dd class="mt-0.5 font-medium capitalize text-zinc-900 dark:text-zinc-100">{{ $ticket->priority }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Catégorie</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $ticket->category?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Demandeur</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $ticket->requester->name }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Technicien</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $ticket->assignedAgent?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-zinc-500">Ouvert le</dt>
                <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $ticket->created_at?->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Assign agent --}}
    @can('transition', $ticket)
        <div class="surface p-6">
            <h2 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-zinc-500">
                <x-icon-users class="size-4" /> Affecter un technicien
            </h2>
            <div class="mt-3 flex flex-wrap items-end gap-3">
                <div class="w-64">
                    <select wire:model="assignAgentId" class="input">
                        <option value="">Non assigné</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="assignAgent" class="btn btn-secondary">Assigner</button>
            </div>
        </div>
    @endcan

    {{-- Change detail --}}
    @if ($ticket->type === 'change' && $ticket->changeDetail)
        <div class="surface border-l-4 !border-l-amber-400 p-6">
            <h2 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-amber-800">Changement</h2>
            <dl class="mt-3 grid gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-zinc-500">Niveau de risque</dt>
                    <dd class="mt-0.5 font-medium capitalize text-zinc-900 dark:text-zinc-100">{{ $ticket->changeDetail->risk_level }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Planifié à</dt>
                    <dd class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $ticket->changeDetail->scheduled_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                </div>
            </dl>
            @if ($ticket->changeDetail->rollback_plan)
                <div class="mt-3">
                    <dt class="text-sm text-zinc-500">Plan de secours</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-zinc-800 dark:text-zinc-200">{{ $ticket->changeDetail->rollback_plan }}</dd>
                </div>
            @endif
        </div>
    @endif

    {{-- Transitions --}}
    @if (! empty($this->availableTransitions))
        <div class="surface p-6">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-500">Avancer le ticket</h2>
            <div class="flex flex-wrap gap-2">
                @foreach ($this->availableTransitions as $status)
                    <button
                        wire:click="transitionTo('{{ $status }}')"
                        wire:confirm="Faire passer ce ticket à « {{ str_replace('_', ' ', $status) }} » ?"
                        class="btn btn-secondary"
                    >
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Attachments --}}
    <div class="surface p-6">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-500">
            Pièces jointes ({{ $ticket->attachments->count() }})
        </h2>
        <div class="space-y-2">
            @forelse ($ticket->attachments as $attachment)
                <div class="flex items-center justify-between gap-4 rounded-lg border border-zinc-200 px-4 py-3 text-sm dark:border-zinc-700">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950">
                            <x-icon-paperclip class="size-4" />
                        </span>
                        <div class="min-w-0">
                            <a href="{{ route('attachments.download', $attachment) }}" class="block truncate font-medium text-brand-600 hover:text-brand-700">
                                {{ $attachment->original_filename }}
                            </a>
                            <p class="text-xs text-zinc-400">{{ $attachment->humanSize() }} — ajouté par {{ $attachment->uploader->name }}</p>
                        </div>
                    </div>
                    @if ($attachment->user_id === auth()->id() || auth()->user()->can('tickets.update_any'))
                        <button
                            wire:click="deleteAttachment({{ $attachment->id }})"
                            wire:confirm="Supprimer cette pièce jointe ?"
                            class="text-sm font-medium text-red-600 hover:text-red-700"
                        >
                            Supprimer
                        </button>
                    @endif
                </div>
            @empty
                <p class="py-2 text-sm text-zinc-400">Aucune pièce jointe pour le moment.</p>
            @endforelse
        </div>

        <form wire:submit="uploadAttachments" class="mt-4 space-y-3">
            <label class="block cursor-pointer rounded-lg border-2 border-dashed border-zinc-300 p-4 text-center text-sm text-zinc-500 transition hover:border-brand-400 hover:text-brand-600 dark:border-zinc-700">
                <span class="font-medium">Ajouter des fichiers</span>
                <span class="block text-xs">PDF, images, captures d'écran…</span>
                <input type="file" wire:model="newAttachments" multiple class="sr-only">
            </label>
            @error('newAttachments')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('newAttachments.*')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div wire:loading wire:target="newAttachments" class="text-sm text-zinc-500">Envoi en cours…</div>

            @if (! empty($newAttachments))
                <button type="submit" class="btn btn-primary">Joindre {{ count($newAttachments) }} fichier(s)</button>
            @endif
        </form>
    </div>

    {{-- Comments --}}
    <div class="surface p-6">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-500">
            Commentaires ({{ $comments->count() }})
        </h2>

        <div class="space-y-3">
            @forelse ($comments as $comment)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="flex size-7 items-center justify-center rounded-full bg-brand-100 text-xs font-semibold uppercase text-brand-700 dark:bg-brand-950">
                                {{ substr($comment->user->name, 0, 1) }}
                            </span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $comment->user->name }}</span>
                            @if ($comment->is_internal)
                                <span class="badge bg-amber-100 text-amber-700">Note interne</span>
                            @endif
                        </div>
                        <span class="text-xs text-zinc-400">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-zinc-800 dark:text-zinc-200">{{ $comment->body }}</p>
                </div>
            @empty
                <p class="py-2 text-sm text-zinc-400">Aucun commentaire pour le moment.</p>
            @endforelse
        </div>

        <form wire:submit="addComment" class="mt-4 space-y-3">
            <textarea wire:model="commentBody" rows="3" class="input" placeholder="Ajouter un commentaire…"></textarea>
            @error('commentBody')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            @can('transition', $ticket)
                <label class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <input type="checkbox" wire:model="isInternal" class="size-4 accent-brand-600">
                    Note interne (masquée pour le client)
                </label>
            @endcan

            <button type="submit" class="btn btn-primary">Publier le commentaire</button>
        </form>
    </div>
</div>