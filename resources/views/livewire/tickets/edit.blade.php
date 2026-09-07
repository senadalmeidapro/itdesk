<div class="max-w-3xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
            Modifier le ticket #{{ $ticket->id }}
        </h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $ticket->title }}</p>
    </div>

    <form wire:submit="save" class="surface mt-6 space-y-6 p-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-zinc-500">
                <span class="font-medium text-zinc-700 dark:text-zinc-300">Type :</span>
                <span class="capitalize">{{ str_replace('_', ' ', $ticket->type) }}</span>
            </p>
            <x-status-pill :status="$ticket->status" />
        </div>

        <div>
            <label class="label">{{ __('Titre') }}</label>
            <input type="text" wire:model="title" class="input">
            @error('title')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="label">{{ __('Description') }}</label>
            <textarea wire:model="description" rows="4" class="input"></textarea>
            @error('description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label">{{ __('Priorité') }}</label>
                <select wire:model="priority" class="input">
                    <option value="low">Basse</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                    <option value="critical">Critique</option>
                </select>
            </div>

            <div>
                <label class="label">{{ __('Catégorie') }}</label>
                <select wire:model="category_id" class="input">
                    <option value="">Sans catégorie</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="label">{{ __('Équipements concernés') }}</label>
            <select wire:model="asset_ids" multiple class="input">
                @foreach ($assets as $asset)
                    <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->asset_tag }})</option>
                @endforeach
            </select>
            @error('asset_ids')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if ($type === 'problem')
            <div>
                <label class="label">{{ __('Incidents liés') }}</label>
                <select wire:model="linked_incident_ids" multiple class="input">
                    @foreach ($incidents as $incident)
                        <option value="{{ $incident->id }}">#{{ $incident->id }} — {{ $incident->title }}</option>
                    @endforeach
                </select>
                @error('linked_incident_ids')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endif

        @if ($type === 'change')
            <div class="space-y-5 rounded-xl border border-amber-200 bg-amber-50/50 p-5">
                <p class="text-sm font-semibold text-amber-900">Détails du changement</p>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="label">{{ __('Niveau de risque') }}</label>
                        <select wire:model="risk_level" class="input">
                            <option value="low">Faible</option>
                            <option value="medium">Moyen</option>
                            <option value="high">Élevé</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">{{ __('Planifié à') }}</label>
                        <input type="datetime-local" wire:model="scheduled_at" class="input">
                    </div>
                </div>
                <div>
                    <label class="label">{{ __('Plan de secours (rollback)') }}</label>
                    <textarea wire:model="rollback_plan" rows="2" class="input"></textarea>
                </div>
            </div>
        @endif

        <div class="flex items-center gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>