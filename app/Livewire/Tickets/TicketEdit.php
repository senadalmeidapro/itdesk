<?php

namespace App\Livewire\Tickets;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TicketEdit extends Component
{
    public Ticket $ticket;

    public string $title = '';

    public string $description = '';

    public string $type = 'incident';

    public string $priority = 'medium';

    public ?int $category_id = null;

    // Only used when type = change
    public string $risk_level = 'medium';

    public ?string $scheduled_at = null;

    public string $rollback_plan = '';

    // Only used when type = problem
    public array $linked_incident_ids = [];

    public array $asset_ids = [];

    public function mount(Ticket $ticket): void
    {
        $this->authorize('update', $ticket);

        $this->ticket = $ticket;

        $this->title = $ticket->title;
        $this->description = $ticket->description;
        $this->type = $ticket->type;
        $this->priority = $ticket->priority;
        $this->category_id = $ticket->category_id;
        $this->linked_incident_ids = $ticket->linkedIncidents()->pluck('tickets.id')->map(fn ($id) => (int) $id)->all();
        $this->asset_ids = $ticket->assets()->pluck('assets.id')->map(fn ($id) => (int) $id)->all();

        if ($ticket->changeDetail) {
            $this->risk_level = $ticket->changeDetail->risk_level;
            $this->scheduled_at = $ticket->changeDetail->scheduled_at?->format('Y-m-d\TH:i');
            $this->rollback_plan = $ticket->changeDetail->rollback_plan ?? '';
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:incident,service_request,problem,change'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'risk_level' => ['required_if:type,change', 'in:low,medium,high'],
            'scheduled_at' => ['nullable', 'date'],
            'rollback_plan' => ['nullable', 'string'],
            'linked_incident_ids' => ['array'],
            'linked_incident_ids.*' => ['exists:tickets,id'],
            'asset_ids' => ['array'],
            'asset_ids.*' => ['exists:assets,id'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->ticket);

        $validated = $this->validate();

        $this->ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'category_id' => $validated['category_id'],
        ]);

        if ($this->ticket->type === 'problem') {
            $this->ticket->linkedIncidents()->sync($validated['linked_incident_ids'] ?? []);
        }

        $this->ticket->assets()->sync($validated['asset_ids'] ?? []);

        if ($this->ticket->type === 'change') {
            if ($this->ticket->changeDetail) {
                $this->ticket->changeDetail->update([
                    'risk_level' => $validated['risk_level'],
                    'scheduled_at' => $validated['scheduled_at'],
                    'rollback_plan' => $validated['rollback_plan'],
                ]);
            } else {
                $this->ticket->changeDetail()->create([
                    'risk_level' => $validated['risk_level'],
                    'scheduled_at' => $validated['scheduled_at'],
                    'rollback_plan' => $validated['rollback_plan'],
                ]);
            }
        }

        session()->flash('success', "Ticket #{$this->ticket->id} updated.");

        $this->redirectRoute('tickets.show', $this->ticket);
    }

    public function render(): View
    {
        return view('livewire.tickets.edit', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'assets' => Asset::orderBy('name')->get(['id', 'name', 'asset_tag']),
            'incidents' => Ticket::query()
                ->where('type', 'incident')
                ->whereKeyNot($this->ticket->id)
                ->orderByDesc('id')
                ->get(['id', 'title']),
        ]);
    }
}
