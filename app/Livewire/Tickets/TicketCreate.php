<?php

namespace App\Livewire\Tickets;

use App\Models\ChangeDetail;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TicketCreate extends Component
{
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

    public function mount(): void
    {
        $this->authorize('create', Ticket::class);
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

    public function save()
    {
        $validated = $this->validate();

        $ticket = DB::transaction(function () use ($validated) {
            $ticket = Ticket::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'priority' => $validated['priority'],
                'category_id' => $validated['category_id'],
                'requester_id' => auth()->id(),
                'status' => 'open',
            ]);

            $ticket->applySlaPolicy();

            if ($ticket->requiresApproval()) {
                $ticket->transitionTo('pending_approval');
            }

            if ($ticket->type === 'change') {
                ChangeDetail::create([
                    'ticket_id' => $ticket->id,
                    'risk_level' => $validated['risk_level'],
                    'scheduled_at' => $validated['scheduled_at'],
                    'rollback_plan' => $validated['rollback_plan'],
                ]);
            }

            if ($ticket->type === 'problem' && ! empty($validated['linked_incident_ids'])) {
                $ticket->linkedIncidents()->sync($validated['linked_incident_ids']);
            }

            if (! empty($validated['asset_ids'])) {
                $ticket->assets()->sync($validated['asset_ids']);
            }

            return $ticket;
        });

        session()->flash('success', "Ticket #{$ticket->id} created.");

        return redirect()->route('tickets.show', $ticket);
    }

    public function render(): View
    {
        return view('livewire.tickets.create');
    }
}