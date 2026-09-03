<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TicketIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    #[Url]
    public string $type = '';

    #[Url]
    public string $priority = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Ticket::class);
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['status', 'type', 'priority'])) {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        $query = Ticket::query()
            ->with(['category', 'requester', 'assignedAgent'])
            ->withCount('comments');

        if (! auth()->user()->can('tickets.view_all')) {
            $query->where('requester_id', auth()->id());
        }

        $tickets = $query
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->priority, fn ($q) => $q->where('priority', $this->priority))
            ->latest()
            ->paginate(15);

        return view('livewire.tickets.index', [
            'tickets' => $tickets,
        ]);
    }
}