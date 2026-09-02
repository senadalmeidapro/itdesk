<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Ticket::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', Rule::in(['incident', 'service_request', 'problem', 'change'])],
            'priority' => ['sometimes', Rule::in(['low', 'medium', 'high', 'critical'])],
            'category_id' => ['nullable', 'exists:categories,id'],

            // Only relevant when type = change
            'change_details' => ['required_if:type,change', 'array'],
            'change_details.risk_level' => ['required_if:type,change', Rule::in(['low', 'medium', 'high'])],
            'change_details.scheduled_at' => ['nullable', 'date'],
            'change_details.rollback_plan' => ['nullable', 'string'],

            // Only relevant when type = problem
            'linked_incident_ids' => ['sometimes', 'array'],
            'linked_incident_ids.*' => ['exists:tickets,id'],

            // Optional asset linkage at creation
            'asset_ids' => ['sometimes', 'array'],
            'asset_ids.*' => ['exists:assets,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'change_details.required_if' => 'Change details (risk level) are required for change tickets.',
        ];
    }
}