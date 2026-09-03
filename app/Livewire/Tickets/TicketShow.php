<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketShow extends Component
{
    use WithFileUploads;

    public Ticket $ticket;

    public string $commentBody = '';

    public bool $isInternal = false;

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[] */
    public array $newAttachments = [];

    public ?int $assignAgentId = null;

    public function mount(Ticket $ticket): void
    {
        $this->authorize('view', $ticket);
        $this->ticket = $ticket;
        $this->assignAgentId = $ticket->assigned_agent_id;
    }

    /**
     * Statuses the current user is allowed to transition this ticket into,
     * given both the state machine rules and their role.
     */
    public function getAvailableTransitionsProperty(): array
    {
        if (! auth()->user()->can('transition', $this->ticket)) {
            return [];
        }

        return Ticket::TRANSITIONS[$this->ticket->status] ?? [];
    }

    public function transitionTo(string $status): void
    {
        $this->authorize('transition', $this->ticket);

        $this->ticket->transitionTo($status);
        $this->ticket->refresh();

        session()->flash('success', "Ticket moved to '{$status}'.");
    }

    /**
     * Assign (or reassign) the ticket to an agent. Same permission as
     * transitioning status - only staff can do this.
     */
    public function assignAgent(): void
    {
        $this->authorize('transition', $this->ticket);

        $this->validate([
            'assignAgentId' => ['required', 'exists:users,id'],
        ]);

        $this->ticket->update(['assigned_agent_id' => $this->assignAgentId]);
        $this->ticket->refresh();

        session()->flash('success', 'Ticket assigned.');
    }

    public function addComment(): void
    {
        $this->authorize('view', $this->ticket);

        $isStaff = auth()->user()->can('tickets.comment_internal');

        $this->validate([
            'commentBody' => ['required', 'string'],
        ]);

        $this->ticket->comments()->create([
            'user_id' => auth()->id(),
            'body' => $this->commentBody,
            'is_internal' => $isStaff && $this->isInternal,
        ]);

        $this->reset('commentBody', 'isInternal');
        $this->ticket->refresh();
    }

    /**
     * Allowed extensions kept deliberately narrow: documents, images,
     * and common log/archive formats an IT ticket would realistically need.
     * No executables.
     */
    public function uploadAttachments(): void
    {
        $this->authorize('view', $this->ticket);

        $this->validate([
            'newAttachments' => ['required', 'array', 'max:5'],
            'newAttachments.*' => [
                'file',
                'max:10240', // 10 MB per file
                'mimes:jpg,jpeg,png,gif,pdf,txt,log,csv,zip,docx,xlsx',
            ],
        ]);

        foreach ($this->newAttachments as $file) {
            $path = $file->store('ticket-attachments/'.$this->ticket->id, 'local');

            $this->ticket->attachments()->create([
                'user_id' => auth()->id(),
                'original_filename' => $file->getClientOriginalName(),
                'disk' => 'local',
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
            ]);
        }

        $this->reset('newAttachments');
        $this->ticket->refresh();

        session()->flash('success', 'Attachment(s) uploaded.');
    }

    /**
     * Only the uploader or staff can delete an attachment.
     */
    public function deleteAttachment(int $attachmentId): void
    {
        $attachment = $this->ticket->attachments()->findOrFail($attachmentId);

        abort_unless(
            $attachment->user_id === auth()->id() || auth()->user()->can('tickets.update_any'),
            403
        );

        $attachment->deleteWithFile();

        $this->ticket->refresh();

        session()->flash('success', 'Attachment deleted.');
    }

    public function render(): View
    {
        $this->ticket->load([
            'category', 'requester', 'assignedAgent', 'assets',
            'changeDetail', 'approvals.approver', 'attachments.uploader',
        ]);

        $comments = $this->ticket->comments()
            ->with('user')
            ->when(
                ! auth()->user()->can('tickets.view_all'),
                fn ($q) => $q->where('is_internal', false)
            )
            ->latest()
            ->get();

        $agents = auth()->user()->can('transition', $this->ticket)
            ? User::permission('tickets.transition')->orderBy('name')->get()
            : collect();

        return view('livewire.tickets.show', [
            'comments' => $comments,
            'agents' => $agents,
        ]);
    }
}