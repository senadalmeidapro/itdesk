<div class="max-w-3xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
            Modifier l'équipement {{ $asset->asset_tag }}
        </h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $asset->name }}</p>
    </div>

    <form wire:submit="save" class="surface mt-6 space-y-6 p-8">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label">Tag d'inventaire</label>
                <input type="text" wire:model="asset_tag" class="input">
                @error('asset_tag') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Numéro de série</label>
                <input type="text" wire:model="serial_number" class="input">
                @error('serial_number') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="label">Nom</label>
            <input type="text" wire:model="name" class="input">
            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="label">Type</label>
                <select wire:model="type" class="input">
                    <option value="laptop">Portable</option>
                    <option value="desktop">Bureau</option>
                    <option value="cpu">Unité centrale</option>
                    <option value="monitor">Écran</option>
                    <option value="hard_disk">Disque dur</option>
                    <option value="keyboard">Clavier</option>
                    <option value="mouse">Souris</option>
                    <option value="printer">Imprimante</option>
                    <option value="switch">Switch</option>
                    <option value="router">Routeur</option>
                    <option value="camera">Caméra</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div>
                <label class="label">Statut</label>
                <select wire:model="status" class="input">
                    <option value="in_stock">En stock</option>
                    <option value="in_use">En usage</option>
                    <option value="repair">En réparation</option>
                    <option value="retired">Retiré</option>
                </select>
            </div>

            <div>
                <label class="label">Catégorie</label>
                <select wire:model="category_id" class="input">
                    <option value="">Aucune</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label">Fabricant</label>
                <input type="text" wire:model="manufacturer" class="input">
            </div>
            <div>
                <label class="label">Modèle</label>
                <input type="text" wire:model="model" class="input">
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label">Adresse IP</label>
                <input type="text" wire:model="ip_address" class="input">
                @error('ip_address') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Adresse MAC</label>
                <input type="text" wire:model="mac_address" placeholder="00:1A:2B:3C:4D:5E" class="input">
                @error('mac_address') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="label">Assigné à</label>
            <select wire:model="assigned_user_id" class="input">
                <option value="">Non assigné</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label">Date d'achat</label>
                <input type="date" wire:model="purchase_date" class="input">
            </div>
            <div>
                <label class="label">Fin de garantie</label>
                <input type="date" wire:model="warranty_expires_at" class="input">
                @error('warranty_expires_at') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label">Fournisseur</label>
                <input type="text" wire:model="supplier" class="input">
            </div>
            <div>
                <label class="label">Emplacement</label>
                <input type="text" wire:model="location" class="input">
            </div>
        </div>

        <div>
            <label class="label">Notes</label>
            <textarea wire:model="notes" rows="3" class="input"></textarea>
        </div>

        <div class="flex items-center gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('assets.show', $asset) }}" wire:navigate class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>