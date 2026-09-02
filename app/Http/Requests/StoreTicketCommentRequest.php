<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Anyone who can view the ticket can comment on it.
        return $this->user()->can('view', $this->route('ticket'));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isStaff = $this->user()->hasAnyRole(['admin', 'agent', 'network_tech']);

        return [
            'body' => ['required', 'string'],
            // Only staff can mark a comment internal (hidden from requester).
            'is_internal' => [$isStaff ? 'sometimes' : 'prohibited', 'boolean'],
        ];
    }
}