<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\TransitionTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\ChangeDetail;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * List tickets. Requesters see only their own; agents/admins see all,
     * with optional filters for status/type/priority.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Ticket::class);

        $query = Ticket::query()
            ->with(['category', 'requester', 'assignedAgent'])
            ->withCount('comments');

        if (! $request->user()->hasAnyRole(['admin', 'agent', 'network_tech'])) {
            $query->where('requester_id', $request->user()->id);
        }

        $query
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->string('priority')));

        $tickets = $query->latest()->paginate(20);

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = DB::transaction(function () use ($request) {
            $ticket = Ticket::create([
                ...$request->safe()->only([
                    'title', 'description', 'type', 'priority', 'category_id',
                ]),
                'requester_id' => $request->user()->id,
                'status' => 'open',
            ]);

            if ($ticket->requiresApproval()) {
                $ticket->transitionTo('pending_approval');
            }

            if ($ticket->type === 'change' && $request->filled('change_details')) {
                ChangeDetail::create([
                    'ticket_id' => $ticket->id,
                    ...$request->safe()->only(['change_details'])['change_details'] ?? [],
                ]);
            }

            if ($ticket->type === 'problem' && $request->filled('linked_incident_ids')) {
                $ticket->linkedIncidents()->sync($request->input('linked_incident_ids'));
            }

            if ($request->filled('asset_ids')) {
                $ticket->assets()->sync($request->input('asset_ids'));
            }

            return $ticket;
        });

        $ticket->load(['category', 'requester', 'assignedAgent', 'assets']);

        return (new TicketResource($ticket))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Ticket $ticket): TicketResource
    {
        $this->authorize('view', $ticket);

        $ticket->load(['category', 'requester', 'assignedAgent', 'assets', 'comments.user', 'changeDetail'])
            ->loadCount('comments');

        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): TicketResource
    {
        $ticket->update($request->validated());

        $ticket->load(['category', 'requester', 'assignedAgent']);

        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        return response()->json(null, 204);
    }

    /**
     * Drive the ticket through its status state machine
     * (assign, start work, resolve, close, reopen).
     */
    public function transition(TransitionTicketRequest $request, Ticket $ticket): TicketResource
    {
        $ticket->transitionTo($request->validated('status'));

        $ticket->load(['category', 'requester', 'assignedAgent']);

        return new TicketResource($ticket);
    }
}