<div class="max-w-2xl">
    <h1 class="text-xl font-semibold mb-4">New ticket</h1>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="block font-medium">Title</label>
            <input type="text" wire:model="title" class="w-full border rounded px-2 py-1">
            @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Description</label>
            <textarea wire:model="description" rows="4" class="w-full border rounded px-2 py-1"></textarea>
            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <div>
                <label class="block font-medium">Type</label>
                <select wire:model.live="type" class="border rounded px-2 py-1">
                    <option value="incident">Incident</option>
                    <option value="service_request">Service request</option>
                    <option value="problem">Problem</option>
                    <option value="change">Change</option>
                </select>
            </div>

            <div>
                <label class="block font-medium">Priority</label>
                <select wire:model="priority" class="border rounded px-2 py-1">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>
        </div>

        @if ($type === 'change')
            <div class="border-l-4 border-amber-400 pl-4 space-y-3">
                <div>
                    <label class="block font-medium">Risk level</label>
                    <select wire:model="risk_level" class="border rounded px-2 py-1">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Scheduled at</label>
                    <input type="datetime-local" wire:model="scheduled_at" class="border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block font-medium">Rollback plan</label>
                    <textarea wire:model="rollback_plan" rows="2" class="w-full border rounded px-2 py-1"></textarea>
                </div>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Create ticket</button>
    </form>
</div>