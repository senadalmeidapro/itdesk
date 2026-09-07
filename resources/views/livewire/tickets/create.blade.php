<div class="max-w-3xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Nouveau ticket</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Décrivez votre demande, on s'occupe du reste.</p>
    </div>

    <form wire:submit="save" class="surface mt-6 space-y-6 p-8">
        <div>
            <label class="label">{{ __('Type de demande') }}</label>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'incident' => ['label' => 'Incident', 'desc' => 'Quelque chose ne fonctionne plus'],
                    'service_request' => ['label' => 'Demande de service', 'desc' => 'Une demande d\'intervention'],
                    'problem' => ['label' => 'Problème', 'desc' => 'Panne récurrente ou générale'],
                    'change' => ['label' => 'Changement', 'desc' => 'Une modification planifiée'],
                ] as $value => $option)
                    <label class="cursor-pointer rounded-xl border border-zinc-200 p-4 transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:ring-1 has-[:checked]:ring-brand-500 dark:border-zinc-700">
                        <input type="radio" name="type" value="{{ $value }}" wire:model.live="type" class="size-4 accent-brand-600" @checked($type === $value) />
                        <span class="mt-2 block text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $option['label'] }}</span>
                        <span class="mt-0.5 block text-xs leading-relaxed text-zinc-500">{{ $option['desc'] }}</span>
                    </label>
                @endforeach
            </div>
            @error('type')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="label">{{ __('Titre') }}</label>
            <input type="text" wire:model="title" class="input" placeholder="Ex. : Imprimante de la salle 2 en panne">
            @error('title')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="label">{{ __('Description') }}</label>
            <textarea wire:model="description" rows="4" class="input" placeholder="Décrivez le problème, les symptômes, et tout ce qui peut nous aider à intervenir."></textarea>
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
            <p class="mt-1.5 text-xs text-zinc-400">Maintenez Ctrl (Cmd) pour sélectionner plusieurs équipements.</p>
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
                    <textarea wire:model="rollback_plan" rows="2" class="input" placeholder="Que fait-on si le changement échoue ?"></textarea>
                </div>
            </div>
        @endif

        <div class="flex items-center gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
            <button type="submit" class="btn btn-primary">
                <x-icon-plus class="size-4" /> Créer le ticket
            </button>
            <a href="{{ route('tickets.index') }}" wire:navigate class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>