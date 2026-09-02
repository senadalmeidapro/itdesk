<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TransitionTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('transition', $this->route('ticket'));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_keys(Ticket::TRANSITIONS))],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Ticket $ticket */
            $ticket = $this->route('ticket');
            $newStatus = $this->input('status');

            if ($newStatus && ! $ticket->canTransitionTo($newStatus)) {
                $validator->errors()->add(
                    'status',
                    "Cannot transition from '{$ticket->status}' to '{$newStatus}'."
                );
            }
        });
    }
}