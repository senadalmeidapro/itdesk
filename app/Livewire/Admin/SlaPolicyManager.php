<?php

namespace App\Livewire\Admin;

use App\Models\SlaPolicy;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SlaPolicyManager extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $priority = 'medium';

    public ?int $response_time_minutes = null;

    public ?int $resolution_time_minutes = null;

    public function mount(): void
    {
        $this->authorize('manage-settings');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'response_time_minutes' => ['required', 'integer', 'min:1'],
            'resolution_time_minutes' => ['required', 'integer', 'min:1', 'gte:response_time_minutes'],
        ];
    }

    public function edit(int $id): void
    {
        $policy = SlaPolicy::findOrFail($id);
        $this->editingId = $policy->id;
        $this->name = $policy->name;
        $this->priority = $policy->priority;
        $this->response_time_minutes = $policy->response_time_minutes;
        $this->resolution_time_minutes = $policy->resolution_time_minutes;
    }

    public function save(): void
    {
        $validated = $this->validate();

        SlaPolicy::updateOrCreate(['id' => $this->editingId], $validated);

        $this->reset(['editingId', 'name', 'priority', 'response_time_minutes', 'resolution_time_minutes']);
        $this->priority = 'medium';
        session()->flash('success', 'SLA policy saved.');
    }

    public function delete(int $id): void
    {
        SlaPolicy::findOrFail($id)->delete();
        session()->flash('success', 'SLA policy deleted.');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'name', 'priority', 'response_time_minutes', 'resolution_time_minutes']);
        $this->priority = 'medium';
    }

    public function render(): View
    {
        return view('livewire.admin.sla-policy-manager', [
            'policies' => SlaPolicy::withCount('tickets')->orderBy('priority')->get(),
        ]);
    }
}